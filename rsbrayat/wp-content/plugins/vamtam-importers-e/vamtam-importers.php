<?php

/*
Plugin Name: VamTam Importers (E)
Description: This plugin is used in order to import the sample content for VamTam themes
Version: 1.1.3
Author: VamTam
Author URI: http://vamtam.com
*/

class Vamtam_Importers_E {
	public static $import_attachments;

	public function __construct() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ), 1 );
		add_action( 'plugins_loaded', array( __CLASS__, 'plugins_loaded' ) );
		add_action( 'vamtam_attacments_import_completed', array( __CLASS__, 'elementor_remap' ) );

		if ( ! class_exists( 'Vamtam_Updates_3' ) ) {
			require 'vamtam-updates/class-vamtam-updates.php';
		}

		new Vamtam_Updates_3( __FILE__ );

		add_filter( 'heartbeat_received', [ __CLASS__, 'attachment_progress_heartbeat_callback' ], 10, 2 );
	}

	public static function admin_init() {
		add_action( 'vamtam_before_content_import', array( __CLASS__, 'before_content_import' ) );
		add_action( 'vamtam_after_content_import', array( __CLASS__, 'after_content_import' ) );

		require_once 'importers/importer/importer.php';
		require 'importers/revslider/importer.php';
		require 'importers/ninja-forms/importer.php';
	}

	public static function plugins_loaded() {
		include 'wp-background-process/wp-async-request.php';
		include 'wp-background-process/wp-background-process.php';
		include 'vamtam-import-attachments.php';

		self::$import_attachments = new Vamtam_Import_Attachments();
	}

	/**
	 * Initialize thumbnail generation
	 */
	protected static function process_attachments() {
		delete_option( 'vamtam_import_attachments_url_remap' );
		update_option( 'vamtam_import_attachments_done', 0 );

		$attachments = get_option( 'vamtam_import_attachments_todo' );

		foreach ( $attachments['attachments'] as $attachment_data ) {
			self::$import_attachments->push_to_queue( [
				'step' => 1,
				'data' => [
					'attachment' => $attachment_data,
					'base_url'   => $attachments['base_url'],
				],
			] );
		}

		self::$import_attachments->save()->dispatch();
	}

	public static function get_attachment_progress() {
		$remaining = (int) self::$import_attachments->get_queue_length();

		$text = $remaining > 0 ?
			sprintf( esc_html__( '%d remaining', 'wpv' ), $remaining ) :
			esc_html__( 'all done', 'wpv' );

		return compact( 'text', 'remaining' );
	}

	public static function attachment_progress_heartbeat_callback( $response, $data ) {
		if ( empty( $data['vamtam_attachment_import_poll'] ) ) {
			return $response;
		}

		$attachments = get_option( 'vamtam_import_attachments_todo' )['attachments'];

		$total = is_countable( $attachments ) ? count( $attachments ) : 0;

		$progress = self::get_attachment_progress();

		$response['vamtam_attachment_import_progress'] = $progress['text'];

		// faster updates if we are expecting updates
		if ( $total > 0 ) {
			$response['heartbeat_interval'] = 5;
		}

		// slow down updates if we're done
		if ( $progress['remaining'] === 0 ) {
			$response['heartbeat_interval'] = 30;
		}

		return $response;
	}

	public static function process_post_additional_data( $post, $post_id, $post_exists, $processed_authors, &$featured_images = null ) {
		$comment_post_ID = $post_id;

		if ( ! isset( $post['terms'] ) ) {
			$post['terms'] = array();
		}

		$post['terms'] = apply_filters( 'wp_import_post_terms', $post['terms'], $post_id, $post );

		// add categories, tags and other terms
		if ( ! empty( $post['terms'] ) ) {
			$terms_to_set = array();
			foreach ( $post['terms'] as $term ) {
				// back compat with WXR 1.0 map 'tag' to 'post_tag'
				$taxonomy = ( 'tag' == $term['domain'] ) ? 'post_tag' : $term['domain'];
				$term_exists = term_exists( $term['slug'], $taxonomy );
				$term_id = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
				if ( ! $term_id ) {
					$t = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
					if ( ! is_wp_error( $t ) ) {
						$term_id = $t['term_id'];
						do_action( 'wp_import_insert_term', $t, $term, $post_id, $post );
					} else {
						printf( __( 'Failed to import %s %s', 'wordpress-importer' ), esc_html($taxonomy), esc_html($term['name']) );
						if ( defined('IMPORT_DEBUG') && IMPORT_DEBUG )
							echo ': ' . $t->get_error_message();
						echo '<br />';
						do_action( 'wp_import_insert_term_failed', $t, $term, $post_id, $post );
						continue;
					}
				}
				$terms_to_set[$taxonomy][] = intval( $term_id );
			}

			foreach ( $terms_to_set as $tax => $ids ) {
				$tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
				do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $post );
			}
			unset( $post['terms'], $terms_to_set );
		}

		if ( ! isset( $post['comments'] ) )
			$post['comments'] = array();

		$post['comments'] = apply_filters( 'wp_import_post_comments', $post['comments'], $post_id, $post );

		// add/update comments
		if ( ! empty( $post['comments'] ) ) {
			$num_comments = 0;
			$inserted_comments = array();
			foreach ( $post['comments'] as $comment ) {
				$comment_id	= $comment['comment_id'];
				$newcomments[$comment_id]['comment_post_ID']      = $comment_post_ID;
				$newcomments[$comment_id]['comment_author']       = $comment['comment_author'];
				$newcomments[$comment_id]['comment_author_email'] = $comment['comment_author_email'];
				$newcomments[$comment_id]['comment_author_IP']    = $comment['comment_author_IP'];
				$newcomments[$comment_id]['comment_author_url']   = $comment['comment_author_url'];
				$newcomments[$comment_id]['comment_date']         = $comment['comment_date'];
				$newcomments[$comment_id]['comment_date_gmt']     = $comment['comment_date_gmt'];
				$newcomments[$comment_id]['comment_content']      = $comment['comment_content'];
				$newcomments[$comment_id]['comment_approved']     = $comment['comment_approved'];
				$newcomments[$comment_id]['comment_type']         = $comment['comment_type'];
				$newcomments[$comment_id]['comment_parent'] 	  = $comment['comment_parent'];
				$newcomments[$comment_id]['commentmeta']          = isset( $comment['commentmeta'] ) ? $comment['commentmeta'] : array();

				if ( isset( $processed_authors[$comment['comment_user_id']] ) )
					$newcomments[$comment_id]['user_id'] = $processed_authors[$comment['comment_user_id']];
			}
			ksort( $newcomments );

			foreach ( $newcomments as $key => $comment ) {
				// if this is a new post we can skip the comment_exists() check
				if ( ! $post_exists || ! comment_exists( $comment['comment_author'], $comment['comment_date'] ) ) {
					if ( isset( $inserted_comments[$comment['comment_parent']] ) )
						$comment['comment_parent'] = $inserted_comments[$comment['comment_parent']];
					$comment = wp_filter_comment( $comment );
					$inserted_comments[$key] = wp_insert_comment( $comment );
					do_action( 'wp_import_insert_comment', $inserted_comments[$key], $comment, $comment_post_ID, $post );

					foreach( $comment['commentmeta'] as $meta ) {
						$value = maybe_unserialize( $meta['value'] );
						add_comment_meta( $inserted_comments[$key], $meta['key'], $value );
					}

					$num_comments++;
				}
			}
			unset( $newcomments, $inserted_comments, $post['comments'] );
		}

		if ( ! isset( $post['postmeta'] ) ) {
			$post['postmeta'] = array();
		}

		$post['postmeta'] = apply_filters( 'wp_import_post_meta', $post['postmeta'], $post_id, $post );

		// add/update post meta
		if ( ! empty( $post['postmeta'] ) ) {
			foreach ( $post['postmeta'] as $meta ) {
				$key = apply_filters( 'import_post_meta_key', $meta['key'], $post_id, $post );
				$value = false;

				if ( '_edit_last' == $key ) {
					if ( isset( $processed_authors[intval($meta['value'])] ) ) {
						$value = $processed_authors[intval($meta['value'])];
					} else {
						$key = false;
					}
				}

				if ( $key ) {
					// export gets meta straight from the DB so could have a serialized string
					if ( ! $value ) {
						$value = maybe_unserialize( $meta['value'] );
					}

					if ( is_string( $value ) ) {
						$result = json_decode( $value );
						if ( json_last_error() === JSON_ERROR_NONE ) {
							// usage of wp_slash()/wp_json_encode copied from Elementor\Core\Base\Document::save_elements()
							// do not change without checking what Elementor does
							$value = wp_slash( $value );
						}
					}

					add_post_meta( $post_id, $key, $value );
					do_action( 'import_post_meta', $post_id, $key, $value );

					// if the post has a featured image, take note of this in case of remap
					if ( '_thumbnail_id' == $key && is_array( $featured_images ) ) {
						printf( __( 'Setting featured image for %s', 'wpv' ), $post_id );
						$featured_images[$post_id] = (int) $value;
					}
				}
			}
		}
	}

	public static function before_content_import() {
		wp_suspend_cache_invalidation( true );

		self::generic_option_import( 'jetpack', array( __CLASS__, 'jetpack_import' ) );
		self::generic_option_import( 'the-events-calendar', array( __CLASS__, 'tribe_events_import' ) );
		self::generic_option_import( 'instagram-feed', [ __CLASS__, 'instagram_feed_import' ] );

		self::generic_option_import( 'elementor-settings' );
		self::generic_option_import( 'woocommerce-settings' );

		wp_suspend_cache_invalidation( false );
	}

	public static function after_content_import() {
		wp_set_sidebars_widgets( [
			'wp_inactive_widgets' => [],
			'array_version' => 3,
		] );

		$map = get_option( 'vamtam_last_import_map' );

		$wpforms = get_option( 'wpforms_settings', [] );
		$wpforms['disable-css'] = 2;
		update_option( 'wpforms_settings', $wpforms );

		self::process_attachments();

		self::elementor_import();

		self::elementor_remap();

		self::wc_booking();

		self::give_wp( $map );
	}

	public static function set_menu_locations() {
		$map  = get_option( 'vamtam_last_import_map', false );
		$path = VAMTAM_SAMPLES_DIR . 'theme-mods.json';

		if ( $map && ! get_theme_mod( 'vamtam_force_demo_menu', false ) && file_exists( $path ) ) {
			$theme_mods = json_decode( file_get_contents( $path ), true );

			if ( isset( $theme_mods['nav_menu_locations'] ) ) {
				foreach ( $theme_mods['nav_menu_locations'] as $location => $term_id ) {
					if ( isset( $map['terms'][ (int)$term_id ] ) ) {
						$theme_mods['nav_menu_locations'][ $location ] = $map['terms'][ (int)$term_id ];
					}
				}
			}

			foreach ( $theme_mods as $opt_name => $mod_val ) {
				set_theme_mod( $opt_name, $mod_val );
			}

			set_theme_mod( 'vamtam_force_demo_menu', true );
		}
	}

	public static function generic_option_import( $file, $callback = null ) {
		$path = VAMTAM_SAMPLES_DIR . $file . '.json';

		if ( file_exists( $path ) ) {
			$settings = json_decode( file_get_contents( $path ), true );

			foreach ( $settings as $opt_name => $opt_val ) {
				update_option( $opt_name, $opt_val );
			}

			if ( ! is_null( $callback ) ) {
				call_user_func( $callback );
			}
		}
	}

	public static function instagram_feed_import() {
		$opts = [];
		if ( function_exists( 'sbi_get_database_settings' ) ) {
			$opts = sbi_get_database_settings();
		} else {
			$opts = get_option( 'sb_instagram_settings', [] );
		}

		if ( isset( $opts['connected_accounts'] ) ) {
			foreach ( $opts['connected_accounts'] as $acc => $acc_data) {
				$username = $acc_data['username'];
				if ( $username === 'vamtam.themes' ) {
					$url = $acc_data['profile_picture'];
					if ( ! empty( $url ) ) {
						$folder_name = '';
						if ( ! defined( 'SBI_UPLOADS_NAME' ) ) {
							$folder_name = 'sb-instagram-feed-images';
						} else {
							$folder_name = SBI_UPLOADS_NAME;
						}

						$upload         = wp_upload_dir();
						$full_file_name = trailingslashit( $upload['basedir'] ) . trailingslashit( $folder_name ) . $username  . '.jpg';
						$samples_logo   = VAMTAM_SAMPLES_DIR . 'vamtam.themes.jpg';

						// Get profile pic from fb/insta.
						$image_editor = wp_get_image_editor( $url );
						if ( ! is_wp_error( $image_editor ) ) {
							$saved_image = $image_editor->save( $full_file_name );

							if ( ! $saved_image ) {
								// Try local.
								if ( file_exists( $samples_logo ) ) {
									$image_editor = wp_get_image_editor( $samples_logo );

									if ( ! is_wp_error( $image_editor ) ) {
										$saved_image = $image_editor->save( $full_file_name );
									}
								}
							}
						} else {
							// Try local.
							if ( file_exists( $samples_logo ) ) {
								$image_editor = wp_get_image_editor( $samples_logo );

								if ( ! is_wp_error( $image_editor ) ) {
									$saved_image = $image_editor->save( $full_file_name );
								}
							}
						}

						break;
					}
				}
			}
		}
	}

	public static function jetpack_import() {
		Jetpack::load_modules();

		if ( class_exists( 'Jetpack_Portfolio' ) ) {
			Jetpack_Portfolio::init()->register_post_types();
		}

		if ( class_exists( 'Jetpack_Testimonial' ) ) {
			Jetpack_Testimonial::init()->register_post_types();
		}
	}

	public static function elementor_import() {
		$conditions = get_option( 'elementor_pro_theme_builder_conditions' );
		$map        = get_option( 'vamtam_last_import_map', false );

		foreach ( $conditions as $location => $cnd ) {
			$new_cnd = [];

			foreach ( $cnd as $old_id => $what ) {
				$new_cnd[ $map['posts'][ (int) $old_id ] ] = $what;
			}

			$conditions[ $location ] = $new_cnd;
		}

		update_option( 'elementor_pro_theme_builder_conditions', $conditions );

		$path = VAMTAM_SAMPLES_DIR . 'elementor-settings.json';

		if ( file_exists( $path ) ) {
			$settings = json_decode( file_get_contents( $path ), true );

			$kit = $settings['elementor_active_kit'];

			update_option( 'elementor_active_kit', $map['posts'][ (int) $kit ] );
		}
	}

	public static function elementor_remap() {
		$map = get_option( 'vamtam_last_import_map', false );

		$posts = get_posts( array(
			'post_type'      => get_post_types(),
			'posts_per_page' => -1,
			'meta_query'     => [
				'relation'  => 'AND',
				'existance' => [
					'key'     => '_elementor_data',
					'compare' => 'EXISTS',
				],
				'notempty' => [
					'key'     => '_elementor_data',
					'compare' => '!=',
					'value'   => '',
				],
			],
			'orderby' => 'ID',
			'order' => 'ASC',
		) );

		// loop through the Elementor data for all pages and map old post/term IDs to the new ones (after import)
		foreach ( $posts as $post ) {
			$data = get_post_meta( $post->ID, '_elementor_data', true );

			if ( ! $data ) {
				$meta = get_post_meta( $post->ID );

				if ( isset( $meta[ '_elementor_data' ] ) ) {
					$data = $meta[ '_elementor_data' ][0];
				} else {
					echo "missing _elementor_data for {$post->ID} {$post->post_type}\n";
					var_dump($data);
					unset( $data );
				}
			}

			if ( isset( $data ) ) {
				$data = json_decode( $data, true );

				if ( is_array( $data ) ) {
					$data = self::process_elementor_remap( $data, $map );

					// usage of wp_slash()/wp_json_encode copied from Elementor\Core\Base\Document::save_elements()
					// do not change without checking what Elementor does
					$data = wp_slash( wp_json_encode( $data ) );

					// handle templates inserted as shortcodes
					$data = preg_replace_callback( '/(?<=\[elementor-template id=.")\d+/', function( $matches ) use ( $map ) {
						return $map['posts'][ (int) $matches[0] ];
					}, $data );

					update_post_meta( $post->ID, '_elementor_data', $data );
				}
			}
		}
	}

	public static function process_elementor_remap( $elements, &$map ) {
		foreach ( $elements as &$el ) {
			if ( isset( $el['elements'] ) ) {
				$el['elements'] = self::process_elementor_remap( $el['elements'], $map );
			}

			// WC Categories
			if ( $el['elType'] === 'widget' && $el['widgetType'] === 'wc-categories' && ! empty( $el['settings']['categories'] ) ) {
				$el['settings']['categories'] = array_map( function( $cid ) use ($map) {
					return $map['terms'][ (int) $cid ];
				}, $el['settings']['categories'] );
			}
			// WC Products
			if ( $el['elType'] === 'widget' && $el['widgetType'] === 'woocommerce-products' ) {
				foreach ( [ 'include', 'exclude' ] as $option ) {
					if ( ! empty( $el['settings']["query_${option}_term_ids"] ) ) {
						foreach ( $el['settings']["query_${option}_term_ids"] as $index => $cid ) {
							if ( isset( $map['terms'][ (int) $cid ] ) ) {
								$el['settings']["query_${option}_term_ids"][ $index ] = strval( $map['terms'][ (int) $cid ] );
							}
						}
					}
				}
			}
			// Image Carousel.
			if ( $el['elType'] === 'widget' && $el['widgetType'] === 'image-carousel' && ! empty( $el['settings']['carousel'] ) ) {
				$el['settings']['carousel'] = array_map( function( $item ) use ($map) {
					$map_id = isset( $map['posts'][ (int) $item['id'] ] ) ? $map['posts'][ (int) $item['id'] ] : null;
					if ( isset( $map_id ) && is_numeric( $map_id ) ) {
						$item['id'] = (int) $map_id;
					} else if ( ! isset( $map_id ) ) {
						$item['id'] = 987654;
					}

					return $item;
				}, $el['settings']['carousel'] );
			}
			// Gallery.
			if ( $el['elType'] === 'widget' && $el['widgetType'] === 'gallery' && ! empty( $el['settings']['gallery'] ) ) {
				$el['settings']['gallery'] = array_map( function( $item ) use ($map) {
					$map_id = isset( $map['posts'][ (int) $item['id'] ] ) ? $map['posts'][ (int) $item['id'] ] : null;
					if ( isset( $map_id ) && is_numeric( $map_id ) ) {
						$item['id'] = (int) $map_id;
					} else if ( ! isset( $map_id ) ) {
						$item['id'] = 987654;
					}

					return $item;
				}, $el['settings']['gallery'] );
			}
		}

		return $elements;
	}

	public static function wc_booking() {
		$path = VAMTAM_SAMPLES_DIR . 'woocommerce-booking.json';

		if ( file_exists( $path ) ) {
			$map = get_option( 'vamtam_last_import_map', false );

			$settings = json_decode( file_get_contents( $path ), true );

			global $wpdb;

			foreach ( $settings as $row ) {
				$row = array_map( 'intval', $row  );

				if ( isset( $map['posts'][ $row['product_id'] ] ) ) {
					unset( $row['ID'] );

					$row['product_id'] = $map['posts'][ $row['product_id'] ];
					$row['resource_id'] = $map['posts'][ $row['resource_id'] ];

					$wpdb->insert( "{$wpdb->prefix}wc_booking_relationships", $row, [ '%d', '%d', '%d' ] );
				} else {
					echo "product {$row['product_id']} not in content.xml, skipping\n";
				}
			}
		}
	}

	public static function give_wp( $map ) {
		$path = VAMTAM_SAMPLES_DIR . 'give-data.json';

		if ( file_exists( $path ) ) {
			$data = json_decode( file_get_contents( $path ), true );

			global $wpdb;

			foreach ( $data as $table => $rows ) {
				foreach ( $rows as $row ) {
					if ( isset( $map['posts'][ $row['form_id'] ] ) ) {
						unset( $row['meta_id'] );
						$row['form_id'] = $map['posts'][ $row['form_id'] ];

						$wpdb->insert( "{$wpdb->prefix}give_{$table}", $row, [ '%d', '%s', '%s' ] );
					} else {
						echo "give-wp form {$row['form_id']} not in content.xml, skipping\n";
					}
				}
			}
		}
	}

	public static function tribe_events_import() {
		// no cache to regenerate at this time
	}

	/**
	 * @return string
	 */
	static public function fix_serialized( $src ) {
		if ( empty( $src ) ) {
			return $src;
		}

		$data = maybe_unserialize( $src );

		// return if maybe_unserialize() returns an object or array, this is good.
		if( is_object( $data ) || is_array( $data ) ) {
			return $data;
		}

		$data = preg_replace_callback( '!s:(\d+):([\\\\]?"[\\\\]?"|[\\\\]?"((.*?)[^\\\\])[\\\\]?");!s', array( __CLASS__, 'fix_serial_callback' ), $src );

		if ( ! isset( $data ) && strlen( $data ) === 0 ) {
			return $src;
		}

		return $data;
	}

	/**
	 * @return string
	 */
	static public function fix_serial_callback( $matches ) {
		if ( ! isset( $matches[3] ) ) {
			return $matches[0];
		}

		return 's:' . strlen( self::unescape_mysql( $matches[3] ) ) . ':"' . self::unescape_quotes( $matches[3] ) . '";';
	}

	/**
	 * Unescape to avoid dump-text issues.
	 *
	 * @access private
	 * @return string
	 */
	static private function unescape_mysql( $value ) {
		return str_replace( array( "\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"' ),
			array( "\\",   "\0",  "\n",  "\r",  "\x1a", "'", '"' ),
		$value );
	}

	/**
	 * Fix strange behaviour if you have escaped quotes in your replacement.
	 *
	 * @access private
	 * @return string
	 */
	static private function unescape_quotes( $value ) {
		return str_replace( '\"', '"', $value );
	}
}

new Vamtam_Importers_E;
