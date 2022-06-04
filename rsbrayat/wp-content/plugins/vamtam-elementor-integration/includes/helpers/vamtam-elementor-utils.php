<?php

class Vamtam_Elementor_Utils {
	public static function add_control_options( $controls_manager, $element, string $control_id, array $options, string $control_group = '', bool $replace = false ) {
		$is_group_control = ! empty( $control_group );

		if ( empty( $options ) ) {
			return;
		}

		if ( $is_group_control ) {
			$group_types = array_keys( \Elementor\Plugin::$instance->controls_manager->get_control_groups() );

			if (  ! in_array( $control_group, $group_types ) ) {
				throw new \Exception( 'Invalid group control type!' );
			}

			$control_group        = $controls_manager->get_control_groups( $control_group );
			$group_fields_updated = [];

			foreach ( $control_group->get_fields() as $key => $value ) {
				$cid = $control_id . '_' . $key;
				self::update_control_options( $controls_manager, $element, $cid, $options, $replace );
				$group_fields_updated[ $cid ] = $cid;
			}

			$base_group_control_id = "{$control_id}_{$control_group->get_type()}";
			if ( ! isset( $group_fields_updated[ $base_group_control_id ] ) ) {
				// Update group base control.
				self::update_control_options( $controls_manager, $element, $base_group_control_id, $options, $replace );
			}
		} else {
			self::update_control_options( $controls_manager, $element, $control_id, $options, $replace );
		}
	}

	public static function replace_control_options( $controls_manager, $element, string $control_id, array $options, string $control_group = '' ) {
		self::add_control_options( $controls_manager, $element, $control_id, $options, $control_group, true );
	}

	public static function remove_tabs( $controls_manager, $element, string $tab_id ) {
		if ( empty( $tab_id ) || empty( $element ) ) {
			return false;
		}

		$el_stack    = $element->get_stack();
		$el_controls = ! empty( $el_stack ) ? $el_stack['controls'] : [];

		if ( empty( $el_controls ) ) {
			return false;
		}

		// Remove controls belonging to the tabs.
		foreach ( $el_controls as $control_id => $control_data ) {
			$is_tab_control = isset( $control_data[ 'tabs_wrapper' ] ) && $control_data[ 'tabs_wrapper' ] === $tab_id;
			if ( $is_tab_control ) {
				self::remove_control( $controls_manager, $element, $control_data[ 'name' ] );
			}
		}

		// Remove tab control.
		self::remove_control( $controls_manager, $element, $tab_id );
		return true;
	}

	public static function remove_section( $controls_manager, $element, string $section_id ) {
		if ( empty( $section_id ) || empty( $element ) ) {
			return false;
		}

		$el_stack    = $element->get_stack();
		$el_controls = ! empty( $el_stack ) ? $el_stack['controls'] : [];

		if ( empty( $el_controls ) ) {
			return false;
		}

		// Remove controls belonging to the section.
		foreach ( $el_controls as $control_id => $control_data ) {
			$is_section_control = isset( $control_data[ 'section' ] ) && $control_data[ 'section' ] === $section_id;
			if ( $is_section_control ) {
				self::remove_control( $controls_manager, $element, $control_data[ 'name' ] );
			}
		}

		// Remove section control.
		self::remove_control( $controls_manager, $element, $section_id );
		return true;
	}

	public static function remove_control( $controls_manager, $element, string $control_id, string $control_group = '' ) {
		$is_group_control = ! empty( $control_group );

		if ( $is_group_control ) {
			self::remove_group_control( $controls_manager, $element, $control_id, $control_group );
			return;
		} else {
			$control_data = $controls_manager->get_control_from_stack( $element->get_unique_name(), $control_id );

			if ( is_wp_error( $control_data ) ) {
				return;
			}

			unset( $control_data['section'] );
			unset( $control_data['tab'] );

			if ( isset( $control_data['responsive'] ) && ( $control_data['responsive'] === true || ! empty( $control_data['responsive'] ) ) ) {
				$element->remove_responsive_control( $control_id );
			} else {
				$element->remove_control( $control_id );
			}

			return $control_data;
		}

		return false;
	}

	public static function remove_group_control( $controls_manager, $element, string $control_id, string $control_group = '' ) {
		if ( empty( $control_group ) ) {
			throw new \Exception( 'No group control type given!' );
			return false;
		}

		$group_types = array_keys( \Elementor\Plugin::$instance->controls_manager->get_control_groups() );

		if (  ! in_array( $control_group, $group_types ) ) {
			throw new \Exception( 'Invalid group control type!' );
			return false;
		}

		$control_group = $controls_manager->get_control_groups( $control_group );

		foreach ( $control_group->get_fields() as $key => $value ) {
			$cid = $control_id . '_' . $key;
			self::remove_control( $controls_manager, $element, $cid );
		}
		// Remove group single control.
		self::remove_control( $controls_manager, $element, "{$control_id}_{$control_group->get_type()}" );
	}

	public static function control_exists( $controls_manager, $element, string $control_id ) {
		$control_data = $controls_manager->get_control_from_stack( $element->get_unique_name(), $control_id );

		if ( is_wp_error( $control_data ) ) {
			return false;
		}

		return true;
	}

	protected static function handle_selector_option( &$control_data, $selectors, $replace ) {
		$control_selectors_val = isset( $control_data['selectors'] ) && is_array( $control_data[ 'selectors' ] ) ? reset( $control_data[ 'selectors' ] ) : '';
		$selector_val          = isset( $control_data['selector_value'] ) ? $control_data['selector_value'] : $control_selectors_val;
		$selector              = is_array( $selectors ) ? $selectors[ 0 ] : $selectors;

		if ( ! isset( $selector_val ) || empty( $selector_val ) || empty( $selector ) ) {
			return;
		}

		if ( $replace ) {
			$control_data[ 'selectors' ] = [
				$selector => $selector_val,
			];
		} else {
			$control_data[ 'selectors' ] = $control_data[ 'selectors' ] + [
				$selector => $selector_val,
			];
		}
	}

	protected static function handle_selectors_option( $control_data, &$selectors  ) {
		foreach ( $selectors as $selector => $value ) {
			// Replace some placeholder values.
			if ( $value === '{{_RESET_}}'  ) {
				$selector_val = isset( $control_data['selector_value'] ) ? $control_data['selector_value'] : ( isset( $control_data[ 'selectors' ] ) ? reset( $control_data[ 'selectors' ] ) : null );
				if ( ! isset( $selector_val ) ) {
					// There's no value to set, don't set anything.
					unset( $selectors[ $selector ] );
				} else {
					$selectors[ $selector ] = $selector_val;
				}
			}
		}
	}

	protected static function update_control_options( $controls_manager, $element, string $control_id, array $options, bool $replace ) {
		$control_data = $controls_manager->get_control_from_stack( $element->get_unique_name(), $control_id );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		foreach ( $options as $option => $option_value ) {
			if ( $option === 'selector' ) {
				self::handle_selector_option( $control_data, $option_value, $replace );
				continue;
			}

			if ( $option === 'selectors' ) {
				self::handle_selectors_option( $control_data, $option_value );
			}

			if ( is_array( $option_value ) && empty( $option_value ) ) {
				continue;
			}

			if ( is_array( $option_value ) && ! isset( $control_data[ $option ] ) ) {
				$control_data[ $option ] = [];
			}

			if ( $replace ) {
				$control_data[ $option ] = $option_value;
			} else {
				if ( isset( $control_data[ $option ] ) ) {
					if (  is_array( $control_data[ $option ] ) && is_array( $option_value ) ) {
						// Both are arrays, merge them recursively (values are being added to same keys).
						$control_data[ $option ] = array_merge_recursive($control_data[ $option ], $option_value );
					} else {
						// Add the new option value to the exisiting array.
						$control_data[ $option ] = $control_data[ $option ] + $option_value;
					}
				} else {
					$control_data[ $option ] = $option_value;
				}
			}
		}

		if ( isset( $control_data['responsive'] ) && ( $control_data['responsive'] === true || ! empty( $control_data['responsive'] ) ) ) {
			$element->update_responsive_control( $control_id, $control_data );
		} else {
			$element->update_control( $control_id, $control_data );
		}
	}
}
