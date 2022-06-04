<div class="post-row">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-media">
		<?php
			$has_post_thumb_shape = current_theme_supports( 'vamtam-post-thumb-shape' ) && apply_filters( 'vamtam-post-thumb-shape', true );
		?>
			<div class='media-inner<?php echo esc_attr( $has_post_thumb_shape ? ' vamtam-has-post-thumb-shape' : '' ); ?>'>
				<a href="<?php the_permalink() ?>" title="<?php the_title_attribute()?>">
					<?php the_post_thumbnail( 'full' ) ?>
				</a>
				<?php if ( $has_post_thumb_shape ) : ?>
					<div class="vamtam-shape"></div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="post-content-outer">
		<?php
			include locate_template( 'templates/post/header-large.php' );
			include locate_template( 'templates/post/main/actions.php' );
		?>
	</div>
</div>
