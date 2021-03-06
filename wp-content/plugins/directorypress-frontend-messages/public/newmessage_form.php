<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><h2><?php _e("Send Message", 'directorypress-frontend-messages'); ?></h2><?php

if ( ! difp_current_user_can( 'send_new_message') ) {
	echo "<div class='alert alert-danger'>".__("You do not have permission to send new message!", 'directorypress-frontend-messages')."</div>";
} elseif( !empty($_POST['difp_action']) && 'newmessage' == $_POST['difp_action'] ) {
	if( difp_errors()->get_error_messages() ) {
		echo Difp_Form::init()->form_field_output('newmessage', difp_errors() );
	} else {
		echo difp_info_output();
	}
} else {
	echo Difp_Form::init()->form_field_output( 'newmessage' );
}