<?php

/**
 * Post heade template
 *
 * @package vamtam/salute
 */

global $post;

$title = get_the_title();

if ( ! empty( $title ) ) :
	?>
		<header class="single">
			<div class="content">
				<h3>
					<a href="<?php the_permalink() ?>" title="<?php the_title_attribute()?>"><?php the_title(); ?></a>
				</h3>
			</div>
		</header>
	<?php
endif;


