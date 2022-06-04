<?php
return array(
	'name' => esc_html__( 'Help', 'salute' ),
	'auto' => true,
	'config' => array(

		array(
			'name' => esc_html__( 'Help', 'salute' ),
			'type' => 'title',
			'desc' => '',
		),

		array(
			'name' => esc_html__( 'Help', 'salute' ),
			'type' => 'start',
			'nosave' => true,
		),
//----
		array(
			'type' => 'docs',
		),

			array(
				'type' => 'end',
			),
	),
);
