<?php

/**
 * Controls attached to core sections
 *
 * @package vamtam/salute
 */


return array(
	array(
		'label'     => esc_html__( 'Header Logo Type', 'salute' ),
		'id'        => 'header-logo-type',
		'type'      => 'switch',
		'transport' => 'postMessage',
		'section'   => 'title_tagline',
		'choices'   => array(
			'image'      => esc_html__( 'Image', 'salute' ),
			'site-title' => esc_html__( 'Site Title', 'salute' ),
		),
		'priority' => 8,
	),
);


