<?php
$config  = array(
	'title' => sprintf( '%s Employees Options', PACZ_THEME_NAME ),
	'id' => 'pacz-metaboxes-notab',
	'pages' => array(
		'employees'
	),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'core'
);
$options = array(
	array(
		"name" => esc_html__( "Employee Position", "classiadspro" ),
		"subtitle" => esc_html__( "Position in the company", "classiadspro" ),
		"id" => "_position",
		"default" => "",
		"type" => "text"
	),
	array(
		"name" => esc_html__( "Description", "classiadspro" ),
		"subtitle" => esc_html__( "short description about the team member.", "classiadspro" ),
		"desc" => esc_html__( "You are allowed to use HTML code as well as shortcodes.", "classiadspro" ),
		"id" => "_desc",
		"default" => "",
		"type" => "editor"
	),
	array(
		"name" => esc_html__( "Email Address", "classiadspro" ),
		"subtitle" => esc_html__( "Employee email address", "classiadspro" ),
		"id" => "_email",
		"default" => "",
		"type" => "text"
	),
	array(
		"name" => esc_html__( "Website", "classiadspro" ),
		"subtitle" => esc_html__( "Personal Blog or website", "classiadspro" ),
		"desc" => esc_html__( "Please enter full URL of this social network include http://", "classiadspro" ),
		"id" => "_website",
		"default" => "",
		"type" => "text"
	),
		array(
		"name" => esc_html__( "Facebook", "classiadspro" ),
		"subtitle" => esc_html__( "Social Network", "classiadspro" ),
		"desc" => esc_html__( "Please enter full URL of this social network include http://", "classiadspro" ),
		"id" => "_facebook",
		"default" => "",
		"type" => "text"
	),

		array(
		"name" => esc_html__( "Twitter", "classiadspro" ),
		"subtitle" => esc_html__( "Social Network", "classiadspro" ),
		"desc" => esc_html__( "Please enter full URL of this social network include http://", "classiadspro" ),
		"id" => "_twitter",
		"default" => "",
		"type" => "text"
	),
		array(
		"name" => esc_html__( "Linked In", "classiadspro" ),
		"subtitle" => esc_html__( "Social Network", "classiadspro" ),
		"desc" => esc_html__( "Please enter full URL of this social network include http://", "classiadspro" ),
		"id" => "_linkedin",
		"default" => "",
		"type" => "text"
	),

	array(
		"name" => esc_html__( "Instagram", "classiadspro" ),
		"subtitle" => esc_html__( "Social Network", "classiadspro" ),
		"desc" => esc_html__( "Please enter full URL of this social network include http://", "classiadspro" ),
		"id" => "_instagram",
		"default" => "",
		"type" => "text"
	),

	array(
		"name" => esc_html__( "Dribbble", "classiadspro" ),
		"subtitle" => esc_html__( "Social Network", "classiadspro" ),
		"desc" => esc_html__( "Please enter full URL of this social network include http://", "classiadspro" ),
		"id" => "_dribbble",
		"default" => "",
		"type" => "text"
	),
		array(
		"name" => esc_html__( "Google Plus", "classiadspro" ),
		"subtitle" => esc_html__( "Social Network", "classiadspro" ),
		"desc" => esc_html__( "Please enter full URL of this social network include http://", "classiadspro" ),
		"id" => "_googleplus",
		"default" => "",
		"type" => "text"
	),

	array(
		"name" => esc_html__( "Pinterest", "classiadspro" ),
		"subtitle" => esc_html__( "Social Network", "classiadspro" ),
		"desc" => esc_html__( "Please enter full URL of this social network include http://", "classiadspro" ),
		"id" => "_pinterest",
		"default" => "",
		"type" => "text"
	),
);
new pacz_metaboxesGenerator( $config, $options );
