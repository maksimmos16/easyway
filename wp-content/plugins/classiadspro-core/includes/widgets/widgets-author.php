<?php

/*
	VIDEO WIDGET
*/

class Classiadspro_Widget_Author extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_author', 'description' => 'Author Widget' );
		WP_Widget::__construct( 'author', PACZ_THEME_SLUG.' - '.'author', $widget_ops );


	}



	function widget( $args, $instance ) {
		global $ALSP_ADIMN_SETTINGS;
		extract( $args );
		
		$title = $instance['title'];
		$style = isset( $instance['style'] ) ? $instance['style'] : '';
		global $post,$authordata;
		if(has_shortcode($post->post_content, 'alsp-listing')){ 
			$authorID = $GLOBALS['authorID2']; 
		}else{
			$authorID = '';
		}
		
		$avatar_id = get_user_meta( $authorID, 'avatar_id', true );
		$author_avatar_url = wp_get_attachment_image_src( $avatar_id, 'full' ); 
		$image_src_array = $author_avatar_url[0];
		$author_name = get_the_author_meta('display_name', $authorID);
		$phone_number = get_the_author_meta('user_phone', $authorID);
		//$email_id = $instance['email_id'];
		$registered = date_i18n( "M d, Y", strtotime( get_the_author_meta( 'user_registered', $authorID ) ) );
		
		$author_fb = get_the_author_meta('author_fb', $authorID);
		$author_tw = get_the_author_meta('author_tw', $authorID);
		$author_ytube = get_the_author_meta('author_ytube', $authorID);
		$author_vimeo = get_the_author_meta('author_vimeo', $authorID);
		$author_flickr = get_the_author_meta('author_flickr', $authorID);
		$author_linkedin = get_the_author_meta('author_linkedin', $authorID);
		$author_gplus = get_the_author_meta('author_gplus', $authorID);
		$author_instagram = get_the_author_meta('author_instagram', $authorID);
		$author_behance = get_the_author_meta('author_behance', $authorID);
		$author_dribbble = get_the_author_meta('author_dribbble', $authorID);
		
		require_once PACZ_THEME_PLUGINS_CONFIG . "/image-cropping.php";
		$output = '';
		global $post;
		
		$output .= $before_widget;
		if ( $title )
			$output .= $before_title . $title . $after_title;
		if(class_exists('alsp_plugin')){
			if($style == 1){
				$output .= '<div class="classiadspro-author clearfix">';
					$output .= '<div class="author-img">';
						if(!empty($image_src_array)) {
							$params = array( 'width' => 90, 'height' => 80, 'crop' => true );
							$output .= '<img src="' . bfi_thumb( $image_src_array, $params ) . '"  alt="'.$author_name.'" />';
						} else { 
							$avatar_url = pacz_get_avatar_url ( get_the_author_meta('user_email', $authorID), $size = '90' );
							$output .='<img src="'.$avatar_url.'" alt="author" />';
							}
					$output .='</div>';
					$output .= '<div class="author-content">';
						$output .= '<p class="author-name">'.$author_name.'</p>';
						$output .='<p class="author-reg-date">'. esc_html__('Member since', 'pacz').' '.$registered.'</p>';
						if($ALSP_ADIMN_SETTINGS['frontend_panel_social_links']){
							if($author_fb || $author_tw || $author_linkedin || $author_gplus || $author_ytube || $author_vimeo || $author_behance || $author_dribbble || $author_instagram || $author_flickr){
								$output .='<div class="author-social-follow">';
									$output .='<ul class="author-social-follow-ul">';
										$output .='<li class="author-social-lable">'.esc_html('Follow Me', 'pacz').'</li>';
										if(!empty($author_fb)){
											$output .='<li><a href="'.$author_fb.'" target_blank><i class="pacz-icon-facebook"></i></a></li>';
										}
										if(!empty($author_tw)){
											$output .='<li><a href="'.$author_tw.'" target_blank><i class="pacz-icon-twitter"></i></a></li>';
										}
										if(!empty($author_gplus)){
											$output .='<li><a href="'.$author_gplus.'" target_blank><i class="pacz-icon-google-plus"></i></a></li>';
										}
										if(!empty($author_linkedin)){
											$output .='<li><a href="'.$author_linkedin.'" target_blank><i class="pacz-icon-linkedin"></i></a></li>';
										}
										if(!empty($author_ytube)){
											$output .='<li><a href="'.$author_ytube.'" target_blank><i class="pacz-icon-youtube"></i></a></li>';
										}
										if(!empty($author_vimeo)){
											$output .='<li><a href="'.$author_vimeo.'" target_blank><i class="pacz-icon-vimeo-square"></i></a></li>';
										}
										if(!empty($author_instagram)){
											$output .='<li><a href="'.$author_instagram.'" target_blank><i class="pacz-icon-instagram"></i></a></li>';
										}
										if(!empty($author_flickr)){
											$output .='<li><a href="'.$author_flickr.'" target_blank><i class="pacz-icon-flickr"></i></a></li>';
										}
										if(!empty($author_behance)){
											$output .='<li><a href="'.$author_behance.'" target_blank><i class="pacz-icon-behance"></i></a></li>';
										}
										if(!empty($author_dribbble)){
											$output .='<li><a href="'.$author_dribbble.'" target_blank><i class="pacz-icon-dribbble"></i></a></li>';
										}
									$output .= '</ul>';
								$output .= '</div>';
							}
						}
					$output .= '</div>';
				$output .= '</div>';
				if(!empty($phone_number) && $ALSP_ADIMN_SETTINGS['frontend_panel_user_phone']){
					$output .= '<div class="author-phone">';
						$output .= '<a class="" href="tel:'.$phone_number.'"><i class="pacz-icon-mobile"></i>'.esc_html__('Mobile: ', 'pacz').$phone_number.'</a>';
					$output .= '</div>';
				}
				$output .= '<div class="author-btns">';
						if($ALSP_ADIMN_SETTINGS['alsp_listing_contact']){ $output .= '<div class="author-btn-holder"><a class="" data-popup-open="single_contact_form" href="#">'.esc_html__('Send message', 'pacz').'</a></div>'; }
						if($ALSP_ADIMN_SETTINGS['author_profile_view']){ $output .= '<div class="author-btn-holder"><a href="'.get_author_posts_url($authorID).'" class="">'. esc_html__('view profile', 'pacz').'</a></div>'; }
				$output .= '</div>';
				$output .= '<div class="about-social">';
				
				if(!empty($email_id)){
				if($ALSP_ADIMN_SETTINGS['frontend_panel_user_email']){ $output .= '<p class="email-id"><i class="classifieds-icon-envelope-o"></i>'.$email_id.'</p>'; }
				}
				$output .= '</div>';
			}elseif($style == 2){
				$output .= '<div class="classiadspro-author style2 clearfix">';
					$output .= '<div class="author-img">';
						if(!empty($image_src_array)) {
							$params = array( 'width' => 110, 'height' => 110, 'crop' => true );
							$output .= '<img src="' . bfi_thumb( $image_src_array, $params ).'" alt="'.$author_name.'" />';
						} else { 
							$avatar_url = pacz_get_avatar_url ( get_the_author_meta('user_email', $authorID), $size = '110' );
							$output .='<img src="'.$avatar_url.'" alt="author" />';
							}
					$output .='</div>';
					$output .= '<div class="author-content">';
						$output .= '<p class="author-name">'.$author_name.'</p>';
						$output .='<p class="author-reg-date">'.esc_html__('Member since', 'pacz').' '.$registered.'</p>';
						if($ALSP_ADIMN_SETTINGS['author_profile_view']){ $output .= '<div class="author-link"><a href="'.get_author_posts_url($authorID).'" class="">'. esc_html__('view all ads', 'pacz').'</a></div>'; }
						if($ALSP_ADIMN_SETTINGS['frontend_panel_social_links']){
							if($author_fb || $author_tw || $author_linkedin || $author_gplus || $author_ytube || $author_vimeo || $author_behance || $author_dribbble || $author_instagram || $author_flickr){
								$output .='<div class="author-social-follow">';
									$output .='<ul class="author-social-follow-ul">';
										//$output .='<li class="author-social-lable">'.esc_html('Follow Me', 'pacz').'</li>';
										if(!empty($author_fb)){
											$output .='<li><a href="'.$author_fb.'" target_blank><i class="pacz-icon-facebook"></i></a></li>';
										}
										if(!empty($author_tw)){
											$output .='<li><a href="'.$author_tw.'" target_blank><i class="pacz-icon-twitter"></i></a></li>';
										}
										if(!empty($author_gplus)){
											$output .='<li><a href="'.$author_gplus.'" target_blank><i class="pacz-icon-google-plus"></i></a></li>';
										}
										if(!empty($author_linkedin)){
											$output .='<li><a href="'.$author_linkedin.'" target_blank><i class="pacz-icon-linkedin"></i></a></li>';
										}
										if(!empty($author_ytube)){
											$output .='<li><a href="'.$author_ytube.'" target_blank><i class="pacz-icon-youtube"></i></a></li>';
										}
										if(!empty($author_vimeo)){
											$output .='<li><a href="'.$author_vimeo.'" target_blank><i class="pacz-icon-vimeo-square"></i></a></li>';
										}
										if(!empty($author_instagram)){
											$output .='<li><a href="'.$author_instagram.'" target_blank><i class="pacz-icon-instagram"></i></a></li>';
										}
										if(!empty($author_flickr)){
											$output .='<li><a href="'.$author_flickr.'" target_blank><i class="pacz-icon-flickr"></i></a></li>';
										}
										if(!empty($author_behance)){
											$output .='<li><a href="'.$author_behance.'" target_blank><i class="pacz-icon-behance"></i></a></li>';
										}
										if(!empty($author_dribbble)){
											$output .='<li><a href="'.$author_dribbble.'" target_blank><i class="pacz-icon-dribbble"></i></a></li>';
										}
									$output .= '</ul>';
								$output .= '</div>';
							}
						}
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div class="author-btns style2">';
						if($ALSP_ADIMN_SETTINGS['alsp_listing_contact']){ $output .= '<div class="author-btn-holder"><a class="" data-popup-open="single_contact_form" href="#">'.esc_html__('Send message', 'pacz').'</a></div>'; }
						if($ALSP_ADIMN_SETTINGS['alsp_listing_bidding']){ $output .= '<div class="author-btn-holder"><a data-popup-open="single_contact_form_bid" href="#" class="">'. esc_html__('Send Offer', 'pacz').'</a></div>'; }
				$output .= '</div>';
				$output .= '<div class="about-social">';
				
				if(!empty($email_id)){
				$output .= '<p class="email-id"><i class="classifieds-icon-envelope-o"></i>'.$email_id.'</p>';
				}
				$output .= '</div>';
				if(!empty($phone_number) && $ALSP_ADIMN_SETTINGS['frontend_panel_user_phone']){
					if(!empty($phone_number)){
						$output .= '<div class="author-phone style2">';
						$prephone_number = substr($phone_number, 0, 5);
						$postphone_number = substr($phone_number, 6, 30);
						$numer_pre = '<span>'.esc_html__('Click To Show Number', 'pacz').'</span>'.$prephone_number.esc_html__('xxxxxx', 'pacz');
							$output .= '<a id="number" data-pre="'.$numer_pre.'" data-last="'.$phone_number.'" class="" href="tel:'.$phone_number.'"><i class="pacz-fic-phone-call-3"></i><span class="number-main">'.$numer_pre.'</span></a>';
						$output .= '</div>';
					}
				}
				
			}else{
				
			}
		}
		$output .= $after_widget;
		if(has_shortcode($post->post_content, 'alsp-listing')){
			echo '<div>'.$output.'</div>';
		}
	}


	function update( $new_instance, $old_instance ) {
		//$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['style'] = $new_instance['style'];
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$style = isset( $instance['style'] ) ? $instance['style'] : '';
?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">Title:</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
		 <p>
    			<label for="<?php echo esc_attr($this->get_field_id( 'style' )); ?>"><?php esc_html_e('Style:', 'pacz'); ?></label>
    			<select name="<?php echo esc_attr($this->get_field_name( 'style' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'style' )); ?>" class="widefat">
    				<option value="1"<?php selected( $style, '1');?>>One</option>
    				<option value="2"<?php selected( $style, '2');?>>Two</option>
    			</select>
  		  </p>
		
		
<?php

	}
}
/***************************************************/
