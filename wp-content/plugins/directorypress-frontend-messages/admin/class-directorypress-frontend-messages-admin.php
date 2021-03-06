<?php

/**
 * @package    Directorypress_Frontend_Messages
 * @subpackage Directorypress_Frontend_Messages/admin
 * @author     Designinvento <help@designinvento.net>
 */
class Directorypress_Frontend_Messages_Admin {

	private $plugin_name;
	private $version;
	
	public function __construct() {

		//$this->plugin_name = $plugin_name;
		//$this->version = $version;
		add_action('admin_menu', array($this, 'addAdminPage'));
		add_action('admin_init', array($this, 'settings_output'));
		add_filter('plugin_action_links_' . plugin_basename( DIFP_PLUGIN_FILE ), array( $this, 'add_settings_link' ) );
		add_action('difp_action_before_admin_options_save', array($this, 'recalculate_user_message_count'), 10, 2 );
	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/directorypress-frontend-messages-admin.css', array(), $this->version, 'all' );

	}
	
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/directorypress-frontend-messages-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	private static $instance;
	private $priority = 0;
	
	public static function init()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self;
            }
            return self::$instance;
        }
		
    function actions_filters()
    {
		//add_action('admin_menu', array($this, 'addAdminPage'));
		//add_action('admin_init', array($this, 'settings_output'));
		//add_filter('plugin_action_links_' . plugin_basename( DIFP_PLUGIN_FILE ), array( $this, 'add_settings_link' ) );
		//add_action('difp_action_before_admin_options_save', array($this, 'recalculate_user_message_count'), 10, 2 );
    }

    function addAdminPage()
    {
		$admin_cap = apply_filters( 'difp_admin_cap', 'manage_options' );
		
		add_submenu_page('edit.php?post_type=difp_message', __('Settings','directorypress-frontend-messages'), __('Settings','directorypress-frontend-messages'), $admin_cap, 'difp_settings', array($this, "settings_page"));
		//add_submenu_page('edit.php?post_type=difp_message', 'DesignInvento Messaging System - ' .__('Extensions','directorypress-frontend-messages'), __('Extensions','directorypress-frontend-messages'), $admin_cap, 'difp_extensions', array($this, "extensions_page"));
	
    }
	function recalculate_user_message_count( $settings, $tab ){
	
		if( 'message' == $tab && difp_get_message_view() != $settings['message_view'] ) {
			if( 'threaded' == difp_get_message_view() ){
				delete_metadata( 'user', 0, '_difp_user_message_count', '', true );
			} else {
				update_option( '_difp_message_view_changed', 1 );
				delete_metadata( 'post', 0, '_difp_last_reply_by', '', true );
			}
		}
	}
	
	public function form_fields( $section = 'general' )
	{
		$user_role = array();
		
		foreach ( get_editable_roles() as $key => $role ) {
			$user_role[$key] = $role['name'];
		}
		$pages = array( '' => __('Use Current Page', 'directorypress-frontend-messages' ));
		
		foreach ( get_pages( array( 'hierarchical' => 0 )) as $page ) {
			$pages[$page->ID] = $page->post_title;
		}
		
		$fields = array(
				//General Settings
/* 			'page_id'	=> array(
				'type'	=>	'select',
				'value' => difp_get_option('page_id', 0 ),
				'priority'	=> 2,
				'label' => __( 'DesignInvento Messaging System Page', 'directorypress-frontend-messages' ),
				'options'	=> $pages,
				'description' => __( 'Must have <code>[DIRECTORYPRESS]</code> in content.', 'directorypress-frontend-messages' )					
				), */
			'messages_page'	=> array(
				'type'	=>	'number',
				'value' => difp_get_option('messages_page',15),
				'priority'	=> 4,
				'label' => __('Messages to show per page', 'directorypress-frontend-messages'),
				'description' => __( 'Messages to show per page', 'directorypress-frontend-messages' )
				),
					
			/* 'user_page'	=> array(
				'type'	=>	'number',
				'value' => difp_get_option('user_page',50),
				'priority'	=> 6,
				'label' => __( 'Maximum user per page in Directory', 'directorypress-frontend-messages' ),
				'description' => __( 'Maximum user per page in Directory', 'directorypress-frontend-messages' )
				), */
					
			/* 'time_delay'	=> array(
				'type'	=>	'number',
				'value' => difp_get_option('time_delay',5),
				'priority'	=> 8,
				'label' => __( 'Time delay', 'directorypress-frontend-messages' ),
				'description' => __( 'Time delay between two messages send by a user in minutes (0 = No delay required)', 'directorypress-frontend-messages' )
				), */
					
			/* 'custom_css'	=> array(
				'type'	=>	'textarea',
				'value' => difp_get_option('custom_css'),
				'priority'	=> 10,
				'label' => __( 'Custom CSS', 'directorypress-frontend-messages' ),
				'description' => __( 'add or override.', 'directorypress-frontend-messages' )
				), */
					
			'editor_type'	=> array(
				'type'	=>	'select',
				'value' => difp_get_option('editor_type','wp_editor'),
				'priority'	=> 12,
				'label' => __( 'Editor Type', 'directorypress-frontend-messages' ),
				//'description' => __( 'Admin alwayes have Wp Editor.', 'directorypress-frontend-messages' ),
				'options'	=> array(
					'wp_editor'	=> __( 'Wp Editor', 'directorypress-frontend-messages' ),
					'teeny'	=> __( 'Wp Editor (Teeny)', 'directorypress-frontend-messages' ),
					'textarea'	=> __( 'Textarea', 'directorypress-frontend-messages' )
					)						
				),
				
			/* 'parent_post_status'	=> array(
				'type'	=>	'select',
				'value' => difp_get_option('parent_post_status','publish'),
				'priority'	=> 14,
				'label' => __( 'Parent Message Status', 'directorypress-frontend-messages' ),
				'description' => __( 'Parent Message status when sent from front end.', 'directorypress-frontend-messages' ),
				'options'	=> array(
					'publish'	=> __( 'Publish', 'directorypress-frontend-messages' ),
					'pending'	=> __( 'Pending', 'directorypress-frontend-messages' )
					)					
				),
				
			'reply_post_status'	=> array(
				'type'	=>	'select',
				'value' => difp_get_option('reply_post_status','publish'),
				'priority'	=> 16,
				'label' => __( 'Reply Message Status', 'directorypress-frontend-messages' ),
				'description' => __( 'Reply Message status when sent from front end.', 'directorypress-frontend-messages' ),
				'options'	=> array(
					'publish'	=> __( 'Publish', 'directorypress-frontend-messages' ),
					'pending'	=> __( 'Pending', 'directorypress-frontend-messages' )
					)					
				),
					 */
			/* 'allow_attachment'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('allow_attachment', 1),
				'priority'	=> 18,
				'class'	=> '',
				'label' => __( 'Allow Attachment?', 'directorypress-frontend-messages' ),
				'description' => __( 'Allow to attach attachment with message?', 'directorypress-frontend-messages' )
				),
			'attachment_size'	=> array(
				'type'	=>	'text',
				'value' => difp_get_option('attachment_size','4MB'),
				'priority'	=> 20,
				'label' => __( 'Maximum size of attachment?', 'directorypress-frontend-messages' ),
				'description' => __( 'Use KB, MB or GB.(eg. 4MB)', 'directorypress-frontend-messages' )
				),
			'attachment_no'	=> array(
				'type'	=>	'number',
				'value' => difp_get_option('attachment_no','4'),
				'priority'	=> 22,
				'label' => __( 'Maximum Number of attachment?', 'directorypress-frontend-messages' )
				), */
					
			/* 'hide_directory'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('hide_directory',0),
				'priority'	=> 24,
				'class'	=> '',
				'label' => __( 'Hide Directory from front end?', 'directorypress-frontend-messages' ),
				'description' => __( 'Always shown to Admins.', 'directorypress-frontend-messages' )
				), */
					
			/* 'hide_notification'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('hide_notification',0),
				'priority'	=> 28,
				'class'	=> '',
				'label' => __( 'Hide site wide notification in header?', 'directorypress-frontend-messages' )
				), */
					
			/* 'hide_branding'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('hide_branding',0),
				'priority'	=> 30,
				'class'	=> '',
				'label' => __( 'Hide Branding Footer?', 'directorypress-frontend-messages' )
				), */
			'delete_data_on_uninstall'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('delete_data_on_uninstall', false ),
				'priority'	=> 35,
				'class'	=> '',
				'label' => __( 'Remove Data on Uninstall?', 'directorypress-frontend-messages' ),
				'description' => '<div style="color:red">'. sprintf(__( 'Check this box if you would like %s to completely remove all of its data when the plugin is deleted.', 'directorypress-frontend-messages' ), difp_is_pro() ? 'DesignInvento Messaging System PRO' : 'DesignInvento Messaging System' ). '</div>'
				),
			//Recipient
			/* 'hide_autosuggest'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('hide_autosuggest',0),
				'priority'	=> 5,
				'section'	=> 'recipient',
				'class'	=> '',
				'label' => __( 'Hide Autosuggestion', 'directorypress-frontend-messages' ),
				'cb_label' => __( 'Hide Autosuggestion when typing recipient name?', 'directorypress-frontend-messages' ),
				'description' => __( 'Always shown to Admins.', 'directorypress-frontend-messages' )
				), */
				
			//Message
			/* 'message_view'	=> array(
				'type'	=>	'select',
				'value' => difp_get_message_view(),
				'priority'	=> 5,
				'section'	=> 'message',
				'label' => __( 'Message view', 'directorypress-frontend-messages' ),
				'description' => ( 'threaded' == difp_get_message_view() ) ? '' : __( 'This setting change will redirect you to update page for database update.', 'directorypress-frontend-messages' ),
				'options'	=> array(
					'threaded'	=> __( 'Threaded', 'directorypress-frontend-messages' ),
					'individual'	=> __( 'Individual', 'directorypress-frontend-messages' )
					)					
				),
				 */
			//Announcement
			
			/* 'announcements_page'	=> array(
				'type'	=>	'number',
				'value' => difp_get_option('announcements_page',15),
				'priority'	=> 5,
				'section'	=> 'announcement',
				'label' => __('Announcements per page', 'directorypress-frontend-messages'),
				'description' => __( 'Announcements to show per page', 'directorypress-frontend-messages' )
				), */
				
			/* 'notify_ann'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('notify_ann', '1' ),
				'priority'	=> 10,
				'class'	=> '',
				'section'	=> 'announcement',
				'label' => __( 'Send email?', 'directorypress-frontend-messages' ),
				'description' => __( 'Send email to all users when a new announcement is published?', 'directorypress-frontend-messages' )
				), */
			/* 'ann_to'	=> array(
				'type'	=>	'email',
				'value' => difp_get_option('ann_to', get_bloginfo('admin_email')),
				'priority'	=> 20,
				'section'	=> 'announcement',
				'label' => __( 'Valid email address for "to" field of announcement email', 'directorypress-frontend-messages' ),
				'description' => __( 'All users email will be in "Bcc" field.', 'directorypress-frontend-messages' )
				), */
						
			//Email Settings
			
			'email_content_type'	=> array(
				'type'	=>	'select',
				'value' => difp_get_option( 'email_content_type', 'plain_text' ),
				'priority'	=> 5,
				'section'	=> 'emails',
				'label' => __( 'Email Content Type', 'directorypress-frontend-messages' ),
				'options'	=> array(
					'html'	=> __( 'HTML', 'directorypress-frontend-messages' ),
					'plain_text'	=> __( 'Plain Text', 'directorypress-frontend-messages' )
					)					
				),
			
			'from_name'	=> array(
				'type'	=>	'text',
				'value' => difp_get_option('from_name', get_bloginfo('name')),
				'priority'	=> 10,
				'section'	=> 'emails',
				'label' => __( 'From Name', 'directorypress-frontend-messages' ),
				'description' => __( 'All email send by DesignInvento Messaging System plugin will have this name as sender.', 'directorypress-frontend-messages' )
				),
					
			'from_email'	=> array(
				'type'	=>	'email',
				'value' => difp_get_option('from_email', get_bloginfo('admin_email')),
				'priority'	=> 15,
				'section'	=> 'emails',
				'label' => __( 'From Email', 'directorypress-frontend-messages' ),
				'description' => __( 'All email send by DesignInvento Messaging System plugin will have this email address as sender.', 'directorypress-frontend-messages' )
				),
				
			//Security
			
			'userrole_access'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('userrole_access', array() ),
				'priority'	=> 5,
				'class'	=> '',
				'section'	=> 'security',
				'multiple' => true,
				'label' => __( 'Who can access message system?', 'directorypress-frontend-messages' ),
				'options'	=> $user_role,
				'description' => __( 'User must have access permission to send new message or reply.', 'directorypress-frontend-messages' )					
				),
				
			'userrole_new_message'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('userrole_new_message', array() ),
				'priority'	=> 10,
				'class'	=> '',
				'section'	=> 'security',
				'multiple' => true,
				'label' => __( 'Who can send new message?', 'directorypress-frontend-messages' ),
				'options'	=> $user_role					
				),
			'userrole_reply'	=> array(
				'type'	=>	'checkbox',
				'value' => difp_get_option('userrole_reply', array() ),
				'priority'	=> 15,
				'class'	=> '',
				'section'	=> 'security',
				'multiple' => true,
				'label' => __( 'Who can send reply?', 'directorypress-frontend-messages' ),
				'options'	=> $user_role					
				),
			'whitelist_username'	=> array(
				'type'	=>	'textarea',
				'value' => difp_get_option('whitelist_username'),
				'section'	=> 'security',
				'priority'	=> 20,
				'label' => __( 'Whitelist Username', 'directorypress-frontend-messages' ),
				'description' => __( 'Separated by comma. These users have all permission if blocked by role also.', 'directorypress-frontend-messages' )
				),
			'have_permission'	=> array(
				'type'	=>	'textarea',
				'value' => difp_get_option('have_permission'),
				'section'	=> 'security',
				'priority'	=> 25,
				'label' => __( 'Blacklist Username', 'directorypress-frontend-messages' ),
				'description' => __( 'Separated by comma. These users have NO permission if allowed by role also.', 'directorypress-frontend-messages' )
				),
					
			);
				
		foreach ( $user_role as $key => $role ) {
			$fields["message_box_{$key}"] = array(
					'type'	=>	'number',
					'value' => difp_get_option("message_box_{$key}",50),
					'section'	=> 'message_box',
					'label' => $role,
					'description' => sprintf(__( 'Max messages a %s can keep in box? (0 = Unlimited)', 'directorypress-frontend-messages' ), $role )
					);
					
		}
				
		$fields = apply_filters( 'difp_settings_fields', $fields );

		
		foreach ( $fields as $key => $field )
		{
			
			if( empty($field['section']) )
				$field['section'] = 'general';
				
			if ( $section != $field['section'] ){
				unset($fields[$key]);
				continue;
				}
				
			$this->priority++;
			
			$type = ! empty( $field['type'] ) ? $field['type'] : 'text';
				
			$defaults = array(
								'key'			=> $key,
								'type'			=> $type,
								'name'			=> $key,
								'class'			=> ($type == 'number' ) ? 'small-text' : 'regular-text', //sanitize_html_class()
								'id'			=> $key,
								'label'			=> '',
								'cb_label'		=> '',
								'value'			=> '',
								'placeholder' 	=> '',
								'description'	=> '',
								'priority'		=> $this->priority
								);
			$fields[$key] = wp_parse_args( $fields[$key], $defaults);
			
		}

		uasort( $fields, 'difp_sort_by_priority' );

		return $fields;
}
	
	function settings_output()
	{	
		
		//register_setting( $option_group, $option_name, $sanitize_callback = '' );
		register_setting( 'difp_settings', 'DIFP_admin_options', array( $this, 'options_sanitize') );
			
		foreach ( $this->tabs() as $slug => $tab ) {
			
			//add_settings_section($id, $title, $callback, $page);
			add_settings_section( $tab['section_id'], $tab['section_title'], $tab['section_callback'], $tab['section_page'] );
			
			
			foreach ( $this->form_fields( str_replace( 'difp_settings_', '', $tab['section_id'] )) as $key => $field ) {
			
			if( function_exists( 'difp_settings_field_output_callback_' . $field['type'] ) ) {
				$callback = 'difp_settings_field_output_callback_' . $field['type'];
			} else {
				$callback = array( $this, 'field_output');
			}
			
			//add_settings_field($id, $title, $callback, $page, $section = 'default', $args = array());
			add_settings_field($field['id'], $field['label'], $callback, $tab['section_page'], $tab['section_id'], $field);
			
			}
			
			
		}
	}
	
	function field_output( $field )
	{
		$attrib = ''; 
		 if ( ! empty( $field['required'] ) ) $attrib .= 'required = "required" ';
		 if ( ! empty( $field['readonly'] ) ) $attrib .= 'readonly = "readonly" ';
		 if ( ! empty( $field['disabled'] ) ) $attrib .= 'disabled = "disabled" ';
		 if ( ! empty( $field['minlength'] ) ) $attrib .= 'minlength = "' . absint( $field['minlength'] ) . '" ';
		 if ( ! empty( $field['maxlength'] ) ) $attrib .= 'maxlength = "' . absint( $field['maxlength'] ) . '" ';
		 
		 if ( ! empty( $field['class'] ) ){
			$field['class'] = explode( ' ', $field['class'] );
			$field['class'] = array_map( 'sanitize_html_class', $field['class'] );
			$field['class'] = implode( ' ', array_filter( $field['class'] ) );
		}
		 
		switch( $field['type'] ) {
				
				case has_action( 'difp_admin_settings_field_output_' . $field['type'] ):
				
				do_action( 'difp_admin_settings_field_output_' . $field['type'], $field );
				
				break;
		
				case 'text' :
				case 'email' :
				case 'url' :
				case 'number' :
							?><input id="<?php esc_attr_e( $field['id'] ); ?>" class="<?php echo $field['class']; ?>" type="<?php esc_attr_e( $field['type'] ); ?>" name="<?php esc_attr_e( $field['name'] ); ?>" placeholder="<?php esc_attr_e( $field['placeholder'] ); ?>" value="<?php esc_attr_e( stripslashes($field['value' ]) ); ?>" <?php echo $attrib; ?> /><?php

					break;
				case "textarea" :

							?><textarea id="<?php esc_attr_e( $field['id'] ); ?>" class="<?php echo $field['class']; ?>" cols="50" name="<?php esc_attr_e( $field['name'] ); ?>" placeholder="<?php esc_attr_e( $field['placeholder'] ); ?>" <?php echo $attrib; ?>><?php echo wp_kses_post( stripslashes($field['value' ]) ); ?></textarea><?php

					break;
					
				case "wp_editor" :
						wp_editor( wp_kses_post( stripslashes($field['value' ]) ), $field['id'], array( 'textarea_name' => $field['name'], 'editor_class' => $field['class'], 'media_buttons' => false) );

					break;
				case "teeny" :
				
							wp_editor( wp_kses_post( stripslashes($field['value' ]) ), $field['id'], array( 'textarea_name' => $field['name'], 'editor_class' => $field['class'], 'teeny' => true, 'media_buttons' => false) );

					break;
					
				case "checkbox" :
							
							if( ! empty( $field['multiple' ] ) ) {
								foreach( $field['options' ] as $key => $name ) {
								?><label><input id="<?php esc_attr_e( $field['id'] ); ?>" class="<?php echo $field['class']; ?>" name="<?php esc_attr_e( $field['name'] ); ?>[]" type="checkbox" value="<?php esc_attr_e( $key ); ?>" <?php if( in_array( $key, $field['value' ] ) ) { echo 'checked="checked"';} ?> /> <?php esc_attr_e( $name ); ?></label><br /><?php
								}
							} else {

							?><label><input id="<?php esc_attr_e( $field['id'] ); ?>" class="<?php echo $field['class']; ?>" name="<?php esc_attr_e( $field['name'] ); ?>" type="checkbox" value="1" <?php checked( '1', $field['value' ] ); ?> /> <?php esc_attr_e( $field['cb_label'] ); ?></label><?php
							}

					break;
					
				case "select" :

							?><select id="<?php esc_attr_e( $field['id'] ); ?>" class="<?php echo $field['class']; ?>" name="<?php esc_attr_e( $field['name'] ); ?>"><?php
									foreach( $field['options'] as $key => $name ) {
										?><option value="<?php esc_attr_e( $key ); ?>" <?php selected( $field['value' ], $key ); ?>><?php esc_attr_e( $name ); ?></option><?php }
							?></select><?php

					break;
				
				case "radio" :

						foreach( $field['options'] as $key => $name ) {
							?><label><input type="radio" class="<?php echo $field['class']; ?>" name="<?php esc_attr_e( $field['name'] ); ?>" value="<?php esc_attr_e( $key ); ?>" <?php checked( $field['posted-value' ], $key ); ?> /> <?php esc_attr_e( $name ); ?></label><br /><?php }
					break;
					
				default :
					printf(__('No Function or Hook defined for %s field type', 'directorypress-frontend-messages'), $field['type'] );

					break;
				
				}
					if ( ! empty($field['description']) ) {
						?><p class="description"><?php echo wp_kses_post( $field['description'] ); ?></p><?php
					}
	}
	
	function posted_value_sanitize( $value, $field )
	{
		$sanitized = $value;
		
		switch( $field['type'] ) {
		
				case 'text' :
							$sanitized = sanitize_text_field(trim( $value ));
					break;
						
				case 'email' : //sanitize_email()
							if( ! is_email( $value ) ) {
								add_settings_error( 'difp-settings', $field['id'], sprintf(__( 'Provide valid email address for %s', 'directorypress-frontend-messages' ), $field['label'] ));
								$sanitized = $field['value'];
							}
					break;
				case 'url' :
							$sanitized = esc_url( $value );
					break;
				case 'number' :
							$sanitized = absint( $value );
					break;
				case "textarea" :
				case "wp_editor" :
				case "teeny" :
							$sanitized = wp_kses_post( $value );
					break;
					
				case "checkbox" :
							if( ! empty( $field['multiple' ] ) ) {
							$sanitized = is_array( $value ) ? $value : array();
							foreach( $sanitized as $p_value ) {
								if( ! array_key_exists( $p_value, $field['options'] ) ) {
									add_settings_error( 'difp-settings', $field['id'], sprintf(__( 'Invalid value for %s', 'directorypress-frontend-messages' ), $field['label'] ));
									$sanitized = $field['value'];
								}
							}
							} else {
							$sanitized = absint( $value );
							}
					break;
					
				case "select" :
							if( ! array_key_exists( $value, $field['options'] ) ) {
								add_settings_error( 'difp-settings', $field['id'], sprintf(__( 'Invalid value for %s', 'directorypress-frontend-messages' ), $field['label'] ));
								$sanitized = $field['value'];
							}
					break;
					
				default :
						$sanitized = apply_filters( 'difp_settings_field_sanitize_filter_' . $field['type'], $value, $field );
					
					break;
				
				}
			return apply_filters( 'difp_settings_field_sanitize_filter', $sanitized, $field, $value );
	}
	
	function options_sanitize( $value )
	{
	
		if( empty( $_POST['_wp_http_referer'] ) )
		return $value;
		
		global $wp_settings_sections;
		
		wp_parse_str( $_POST['_wp_http_referer'], $referrer );

		$tab       = !empty( $referrer['tab'] ) ? $referrer['tab'] : 'general';
	
		if( empty( $wp_settings_sections['difp_settings_' . $tab] ) )
			return /** $value */ get_option('DIFP_admin_options');
	
		$posted_value = array();
	
		foreach ( (array) $wp_settings_sections['difp_settings_' . $tab] as $section ) {
				$section_tab = str_replace( 'difp_settings_', '', $section['id']);
				
				$sanitized = apply_filters( "difp_settings_section_sanitize_filter_{$section_tab}", $this->sanitize( $section_tab ), $section );
				$sanitized = apply_filters( "difp_settings_section_sanitize_filter", $sanitized, $section);
				$posted_value = wp_parse_args( $sanitized, $posted_value ); 
		}
		
		// Merge our new settings with the existing
		$settings = wp_parse_args( $posted_value, get_option('DIFP_admin_options') );
		
		$settings = apply_filters( 'difp_filter_before_admin_options_save', $settings, $tab );
		
		do_action( 'difp_action_before_admin_options_save', $settings, $tab );

		return $settings;
	}
	
	function sanitize( $section )
	{
		$posted_value = array();
		
		foreach ( (array) $this->form_fields( $section ) as $key => $field ) {
	
			$posted_value[$field['name']] = isset($_POST[$field['name']]) ? $_POST[$field['name']] : '';
	
			$posted_value[$field['name']] = $this->posted_value_sanitize( $posted_value[$field['name']], $field );
			
			}
		return $posted_value;
	}
	
	function tabs()
	{
		$tabs = array(
				'general'	=> array(
					'tab_title'			=> __('General', 'directorypress-frontend-messages'),
					'priority'			=> 5
					),
				//'recipient'	=> array(
					//'tab_title'			=> __('Recipient', 'directorypress-frontend-messages'),
					//'priority'			=> 7
				//	),
				/* 'message'	=> array(
					'tab_title'			=> __('Message', 'directorypress-frontend-messages'),
					'priority'			=> 10
					), */
				/* 'announcement'	=> array(
					'tab_title'			=> __('Announcement', 'directorypress-frontend-messages'),
					'priority'			=> 15
					), */
				'emails'	=> array(
					'tab_title'			=> __('Emails', 'directorypress-frontend-messages'),
					'priority'			=> 20
					),
				'security'	=> array(
					'tab_title'			=> __('Security', 'directorypress-frontend-messages'),
					'priority'			=> 25
					),
				'message_box'	=> array(
					'section_title'			=> __('Message Box', 'directorypress-frontend-messages'),
					'section_page'		=> 'difp_settings_message',
					'priority'			=> 15,
					'tab_output'		=> false
					)
							
				);
							
		$tabs = apply_filters('difp_admin_settings_tabs', $tabs);
						
				foreach ( $tabs as $key => $tab )
					{
				
							$defaults = array(
												'section_id'		=> 'difp_settings_' . $key,
												'section_title' 	=> '',
												'section_callback'	=> '',
												'section_page'		=> 'difp_settings_' . $key,
												'tab_output'		=> true,
												'tab_title'			=> '',
												'tab_slug'			=> $key,
												'priority'			=> 10
												);
					$tabs[$key] = wp_parse_args( $tabs[$key], $defaults);
			
				}
			uasort( $tabs, 'difp_sort_by_priority' );
							
		return $tabs;
	}
	
	function settings_page()
	{
		$active_tab = ! empty( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
		$args = array(
			'post_type'    => 'difp_message',
			'page'        	=> 'difp_settings'
			);
		
	?>
	<div class="wrap">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
				<?php /*  if( ! difp_is_pro() ) { ?>
				<div><a href="https://wordpress.org/support/plugin/DIRECTORYPRESS/reviews/?filter=5#new-post" target="_blank">like this plugin? Please consider review in WordPress.org and give 5&#9733; rating.</a></div>
				<?php } */ ?>
		<h2 class="nav-tab-wrapper">
		<?php foreach ( $this->tabs() as $key => $tab ) : 
			if( empty($tab['tab_output'])) continue;
			$args['tab'] = $tab['tab_slug']; ?>
		
		<a href="<?php echo esc_url( add_query_arg( $args, admin_url( 'edit.php' ) ) ); ?>" class="nav-tab<?php if( $active_tab == $tab['tab_slug'] ) echo ' nav-tab-active'; ?>"><?php echo $tab['tab_title']; ?></a>
		
		<?php endforeach; ?>
		</h2>
			
		<div id="tab_container">
			<?php settings_errors( /** 'difp-settings' */ ); ?>

			<form method="post" action="options.php">
			<?php 
				settings_fields( 'difp_settings' );
				do_settings_sections( "difp_settings_{$active_tab}" );
				submit_button();
			?>
			</form>
		</div><!-- #tab_container-->
		</div><!-- #post-body-content-->
		<div id="postbox-container-1" class="postbox-container">
		
		</div>
		</div><!-- #post-body-->
		<br class="clear" />
		</div><!-- #poststuff-->
	</div><!-- .wrap -->
	<?php
		
	}
	
	function add_settings_link( $links ) {
		//add settings link in plugins page
		$settings_link = '<a href="' . admin_url( 'edit.php?post_type=difp_message&page=difp_settings' ) . '">' .__( 'Settings', 'directorypress-frontend-messages' ) . '</a>';
		array_unshift( $links, $settings_link );
		
		return $links;
	}

	function extensions_page(){
	}

}
