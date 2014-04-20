<?php
/*
Plugin Name: Social Media Badge Widget
Plugin URI: http://stressfreesites.co.uk/plugins/social-media-badge-widget
Description: This plugin creates a widget which easily displays the social badge from the leading social media websites (Twitter, Facebook, LinkedIn and You Tube).
Version: 2.6.3
Author: StressFree Sites
Author URI: http://stressfreesites.co.uk
Text Domain: smbw
License: GPL2
*/

/*  Copyright 2014 StressFree Sites  (info@stressfreesites.co.uk : alex@stressfreesites.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Load admin settings page */
if ( is_admin() ) {
    require_once('social-media-badge-widget-admin.php');
}

/* Localisation of text */
function smbw_init() {
  load_plugin_textdomain( 'smbw', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action('plugins_loaded', 'smbw_init');

function smbw_enqueue_scripts() { 
    /* Load custom scripts */
    
    /* Select which scripts to load */
    $settings = get_option('smbw_settings','');

    if($settings['loadScripts']['jQuery'] === 'true'){
        if($settings['loadScripts']['jQuery-ui-core'] === 'true'){
            if($settings['loadScripts']['jQuery-ui-accordion'] === 'true'){
                wp_enqueue_script('social-media-badge-widget', plugins_url('social-media-badge-widget/js/social-media-badge-widget.min.js'), array('jquery','jquery-ui-core','jquery-ui-accordion'),'1.0',true);
            }
            else{
                wp_enqueue_script('social-media-badge-widget', plugins_url('social-media-badge-widget/js/social-media-badge-widget.min.js'), array('jquery','jquery-ui-core'),'1.0',true);
            }
        }
        else{
            if($settings['loadScripts']['jQuery-ui-accordion'] === 'true'){
                wp_enqueue_script('social-media-badge-widget', plugins_url('social-media-badge-widget/js/social-media-badge-widget.min.js'), array('jquery','jquery-ui-accordion'),'1.0',true);          
            }
            else{
                wp_enqueue_script('social-media-badge-widget', plugins_url('social-media-badge-widget/js/social-media-badge-widget.min.js'), array('jquery'),'1.0',true);
            }
        }
    }
    else{
        if($settings['loadScripts']['jQuery-ui-core'] === 'true'){
            if($settings['loadScripts']['jQuery-ui-accordion'] === 'true'){
                wp_enqueue_script('social-media-badge-widget', plugins_url('social-media-badge-widget/js/social-media-badge-widget.min.js'), array('jquery-ui-core','jquery-ui-accordion'),'1.0',true);
            }
            else{
                wp_enqueue_script('social-media-badge-widget', plugins_url('social-media-badge-widget/js/social-media-badge-widget.min.js'), array('jquery-ui-core'),'1.0',true);
            }
        }
        else{
            if($settings['loadScripts']['jQuery-ui-accordion'] === 'true'){
                wp_enqueue_script('social-media-badge-widget', plugins_url('social-media-badge-widget/js/social-media-badge-widget.min.js'), array('jquery-ui-accordion'),'1.0',true);
            }
            else{
                wp_enqueue_script('social-media-badge-widget', plugins_url('social-media-badge-widget/js/social-media-badge-widget.min.js'), array(),'1.0',true);
            }           
        }     
    }

    //pass plugin URL variable into Javascript
    wp_localize_script('social-media-badge-widget', 'website_information', array( 'plugin_url' => plugins_url() ));

}    
add_action('wp_enqueue_scripts', 'smbw_enqueue_scripts');

function smbw_enqueue_styles() {  
    /* Load selected style */
    $settings = get_option('smbw_settings','');
    if($settings['loadJqueryUI'] === 'true'){   
        switch($settings['style']){
            case 'Grey':
                wp_enqueue_style('social-media-badge-widget-jquery-ui', plugins_url('social-media-badge-widget/css/jquery-ui-grey.min.css')); 
                break;
            case 'Black':
                wp_enqueue_style('social-media-badge-widget-jquery-ui', plugins_url('social-media-badge-widget/css/jquery-ui-black.min.css'));
                break;
            case 'Blue':
                wp_enqueue_style('social-media-badge-widget-jquery-ui', plugins_url('social-media-badge-widget/css/jquery-ui-blue.min.css'));
                break;
            case 'Red':
                wp_enqueue_style('social-media-badge-widget-jquery-ui', plugins_url('social-media-badge-widget/css/jquery-ui-red.min.css'));
                break;
            case 'Green':
                wp_enqueue_style('social-media-badge-widget-jquery-ui', plugins_url('social-media-badge-widget/css/jquery-ui-green.min.css'));
                break;
            case 'Skeleton':
                wp_enqueue_style('social-media-badge-widget-jquery-ui', plugins_url('social-media-badge-widget/css/jquery-ui-skeleton.min.css'));
                wp_enqueue_style('social-media-badge-widget-skeleton-style', plugins_url('social-media-badge-widget/css/social-media-badge-widget-skeleton.min.css'));
                break;
            default:
                wp_enqueue_style('social-media-badge-widget-jquery-ui', plugins_url('social-media-badge-widget/css/jquery-ui-skeleton.min.css')); 
                wp_enqueue_style('social-media-badge-widget-skeleton-style', plugins_url('social-media-badge-widget/css/social-media-badge-widget-skeleton.min.css'));
                break;
        }
        wp_enqueue_style('social-media-badge-widget', plugins_url('social-media-badge-widget/css/social-media-badge-widget.min.css'), array('social-media-badge-widget-jquery-ui'));
    }
    else{
        wp_enqueue_style('social-media-badge-widget', plugins_url('social-media-badge-widget/css/social-media-badge-widget.min.css'), array());
    }
     
} 
add_action('wp_print_styles', 'smbw_enqueue_styles');

function smbw_activate() {
    // Get old widget information, and save in new formate in database
    
    // Retrieve old widget informaiton  
    $widget = get_option('widget_social-media-badge-widget','');

    // Retrieve new settings information
    smbw_settings_init();
    $settings = get_option('smbw_settings');
    
    if($widget != '' && isset($widget[3])){
        $settings['twitter'] = $widget[3]['twitter'];
        $settings['facebook'] = $widget[3]['facebook'];
        $settings['facebook_badge'] = $widget[3]['facebook_badge'];
        $settings['googleplus'] = $widget[3]['googleplus'];
        $settings['googleplus_profile'] = $widget[3]['googleplus_profile'];
        $settings['linkedin'] = $widget[3]['linkedin'];
        $settings['linkedin_profile'] = $widget[3]['linkedin_profile'];            
        $settings['youtube'] = $widget[3]['youtube'];
        $settings['pinterest'] = $widget[3]['pinterest'];
        $settings['flickr'] = $widget[3]['flickr'];

        $settings['createdBy'] = $widget[3]['createdBy'];
    }
    
    $settings['openSelection'] = get_option('smbw_openSelection');
    $settings['collapsible'] = get_option('smbw_collapsible');
    $settings['allClosed'] = get_option('smbw_allClosed');
    $settings['load_jquery_ui'] = get_option('smbw_load_jquery_ui');
    $settings['load_scripts'] = get_option('smbw_load_scripts');
    $settings['style'] = get_option('smbw_style');
    
    $settings['faces_facebook'] = get_option('smbw_faces_facebook');
    $settings['stream_facebook'] = get_option('smbw_stream_facebook');
    $settings['tweets'] = get_option('smbw_tweets');
    $settings['live_twitter'] = get_option('smbw_live_twitter');

    // Save settings in new format
    update_option('smbw_settings', $settings);
    
    // Delete old settings
    delete_option('widget_social-media-badge-widget');
    delete_option('smbw_openSelection');
    delete_option('smbw_collapsible');
    delete_option('smbw_allClosed');
    delete_option('smbw_load_jquery_ui');
    delete_option('smbw_load_scripts');
    delete_option('smbw_style');
    delete_option('smbw_faces_facebook');
    delete_option('smbw_stream_facebook');
    delete_option('smbw_tweets');
    delete_option('smbw_live_twitter');
    
}
register_activation_hook( __FILE__, 'smbw_activate' );
  
function smbw_admin_styles() {
   wp_enqueue_style('social-media-badge-widget-admin');
}
   
/* Message box */
function smbw_theme_admin_notice() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'smbw_theme_ignore_notice') ) {
            echo '<div class="updated"><p>'; 
            printf(__('<p>Thank you for downloading Social Media Badge Widget. We hope you enjoy using the plugin, maybe some of our <a href="http://stressfreesites.co.uk/development/?utm_source=backend&utm_medium=plugin&utm_campaign=wordpress" target="_blank">other plugins</a> would be of interest to you.</p><p>We have just launched a new Wordpress theme which might be of interest - <a href="http://www.mojo-themes.com/item/simple-setup/demo/" target="_blank">take a look</a>.</p><a href="%1$s">Hide This Notice</a>'), '?smbw_theme_nag_ignore=0');
            echo "</p></div>";
	}
}
add_action('admin_notices', 'smbw_theme_admin_notice');

function smbw_theme_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['smbw_theme_nag_ignore']) && '0' == $_GET['smbw_theme_nag_ignore'] ) {
             add_user_meta($user_id, 'smbw_theme_ignore_notice', 'true', true);
	}
}
add_action('admin_init', 'smbw_theme_nag_ignore');

/* Extending widget class to enable plugin */
class Social_Media_Badge_Widget extends WP_Widget {
    function Social_Media_Badge_Widget() {
            /* Widget settings. */
            $widget_ops = array('classname' => 'social', 'description' => 'A widget which allows social media to be embedded into the theme.');

            /* Widget control settings. */
            $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'social-media-badge-widget');

            /* Create the widget. */
            $this->WP_Widget('social-media-badge-widget', 'Social Media Badge Widget', $widget_ops, $control_ops);
    }
    
    /* Displays widget on website */
    function widget($args, $instance) {
            extract($args);

            /* User-selected settings. */
            $title = apply_filters('widget_title', $instance['title']);
            $width = $instance['width'];
            
            $settings = get_option('smbw_settings');

            $twitter = stripslashes($settings['twitter']);
            $replies_twitter = $settings['replies_twitter'];
            $colour_scheme_twitter = $settings['colour_scheme_twitter'];
            
            $facebook = stripslashes($settings['facebook']);
            $stream_facebook = $settings['stream_facebook'];
            $faces_facebook = $settings['faces_facebook'];
            $colour_scheme_facebook = $settings['colour_scheme_facebook'];
            $facebook_badge = stripslashes($settings['facebook_badge']);
            
            $googleplus = stripslashes($settings['googleplus']);
            $googleplus_profile = stripslashes($settings['googleplus_profile']);
            
            $linkedin = stripslashes($settings['linkedin']);
            $linkedin_profile = stripslashes($settings['linkedin_profile']);
            $side_linkedin = stripslashes($settings['side_linkedin']);
            
            $youtube = stripslashes($settings['youtube']);
            $pinterest = stripslashes($settings['pinterest']);
            $flickr = stripslashes($settings['flickr']);
            

            
            $icons = strtolower($settings['icons']);
            $createdBy = $settings['createdBy'];
                        
            /* Before widget (defined by themes). */
            echo $before_widget .'<div class="social-media-badge">';
            
            /* Title of widget (before and after defined by themes). */
            if ($title)
                    echo $before_title . $title . $after_title;
            
            /* Accordion creation */
            echo ('<input type="hidden" id="smbw_collapsible" value="' . $settings['collapsible'] . '" />
                   <input type="hidden" id="smbw_allClosed" value="' . $settings['allClosed'] . '" />
                   <input type="hidden" id="smbw_openSelection" value="' . $settings['openSelection'] . '" />  
                   <div class="preloader"></div>    
                   <div class="social-accordion">');

            /* Displays each Accordion tab in turn */
            if ($instance['showTwitter'] && $twitter){
                    echo ('<h3 class="twitter ' . $icons . '"><a href="#">'. __('Twitter', 'smbw') . '</a></h3><div class="twitter-content">');

                    echo('<a class="twitter-timeline" href="https://twitter.com/'.$twitter.'" data-widget-id="340472704517947394" data-screen-name="'.$twitter.'" data-theme="' . $colour_scheme_twitter . '" data-show-replies="' . $replies_twitter .'">Tweets by @'.$twitter.'</a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                         ');                        
                    
                    echo ('</div>');
            }

            if ($instance['showFacebook'] && ($facebook || $facebook_badge)){
                    echo ('<h3 class="facebook ' . $icons . '"><a href="#">' . __('Facebook', 'smbw') . '</a></h3><div class="facebook-content">');
                    if($facebook){                        
                        //adjust the height accordly depending on which options are selected.
                        if($stream_facebook === 'true' && $faces_facebook === 'true'){
                            $height = '675';
                            $frame_height = '685';
                        }
                        else if($stream_facebook === 'true' || $faces_facebook === 'true'){
                            $height = '475';
                            $frame_height = '485';
                        }
                        else{
                            $height = '160';
                            $frame_height = '170';
                        }
                        echo ('<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F'.$facebook.'&width='.$width.'&height=' . $height . '&colorscheme=' . $colour_scheme_facebook . '&show_faces=' . $faces_facebook . '&border_color&stream=' . $stream_facebook . '&header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'px; height:' . $frame_height . 'px;" allowTransparency="true"></iframe>');
                       
                    }
                    if($facebook_badge){
                        echo $facebook_badge;
                    }
                    echo ('</div>');
            }
            
            if ($instance['showGooglePlus'] && ($googleplus || $googleplus_profile)){
                    echo ('<h3 class="googleplus ' . $icons . '"><a href="#">' . __('Google+', 'smbw') . '</a></h3><div class="googleplus-content">');
                    if($googleplus){
                        echo ('<!-- Place this tag where you want the widget to render. -->
                                <div class="g-page" data-width="' . $width . '" data-href="https://plus.google.com/'. $googleplus . '" data-rel="publisher"></div>
                              ');
                    }
                    if($googleplus_profile){
                        echo ('<!-- Place this tag where you want the widget to render. -->
                                <div class="g-person" data-width="' . $width . '" data-href="https://plus.google.com/'. $googleplus_profile . '" data-rel="publisher"></div>
                              ');
                                               
                    }
                    echo('<!-- Place this tag after the last widget tag. -->
                            <script type="text/javascript">
                              window.___gcfg = {lang: \'en-GB\'};

                              (function() {
                                var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
                                po.src = \'https://apis.google.com/js/plusone.js\';
                                var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
                              })();
                            </script>');
                    echo ('</div>'); 
            }
            
            if ($instance['showLinkedIn'] && ($linkedin || $linkedin_profile)){
                    echo ('<h3 class="linkedin ' . $icons . '"><a href="#">' . __('LinkedIn', 'smbw') . '</a></h3><div class="linkedin-content ' . $side_linkedin . '"><div style="width:364px">');
                    if ($linkedin){
                        echo ('<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                            <script type="IN/CompanyProfile" data-id="'.$linkedin.'" data-format="inline"></script>');
                    }
                    if($linkedin_profile){
                        echo ('<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                                <script type="IN/MemberProfile" data-id="http://www.linkedin.com/in/'.$linkedin_profile.'" data-format="inline"></script>');
                    }
                    echo ('</div></div>');
            }
                        
            if ($instance['showYouTube'] && $youtube){
                    echo ('<h3 class="youtube ' . $icons . '"><a href="#">' . __('You Tube', 'smbw') . '</a></h3><div class="youtube-content">');
                    echo ('<iframe src="http://www.youtube.com/subscribe_widget?p='.$youtube.'" 
                                style="overflow: hidden; height: 105px; width: '.$width.'px; border: 0;" 
                                scrolling="no" frameBorder="0">
                           </iframe>');
                    echo ('</div>');
            }
            
            if ($instance['showPinterest'] && $pinterest){
                    echo ('<h3 class="pinterest ' . $icons . '"><a href="#">' . __('Pinterest', 'smbw') . '</a></h3><div class="pinterest-content">');
                    $pinterest_width = 60;
                    echo ('<a data-pin-do="embedUser" href="http://www.pinterest.com/' . $pinterest . '/" data-pin-scale-width="' . $pinterest_width . '" data-pin-scale-height="200" data-pin-board-width="' . $width . '">Visit ' . $pinterest . '\'s profile on Pinterest.</a>');
                    echo ('<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>');
                    echo ('</div>');                    
            }
            
            if ($instance['showFlickr'] && $flickr){
                    echo ('<h3 class="flickr ' . $icons . '"><a href="#">' . __('Flickr', 'smbw') . '</a></h3><div class="flickr-content">');
                    echo ('<style type="text/css"> 
                              .flickr_badge_image {margin:0px;display:inline;}
                              .flickr_badge_image img {border: 1px solid #666666 !important; padding:1px; margin:2px;}
                              #flickr_badge_wrapper {width:' . $width .';text-align:left}
                           </style>');
                    echo ('<div id="flickr_badge_wrapper"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=9&display=latest&size=s&layout=x&source=user&user='. $flickr .'"></script></div>');
                    echo ('</div>');                    
            }
            
            /* Finish the Accordion */
            echo ('</div><!-- .social-accordion -->');
            
            /* Copyright */        
            if ($createdBy === 'true'){
                    echo ('<div class="small"><p>' . __('Plugin created by', 'smbw') . ' <a href="http://stressfreesites.co.uk/plugins/social-media-badge-widget/?utm_source=frontend&utm_medium=plugin&utm_campaign=wordpress" target="_blank">StressFree Sites</a></p></div>');
            }
            
            /* After widget (defined by themes). */
            echo '</div><!-- .social-media-widget -->'.$after_widget;
    }

    /* Updating the Wordpress backend */
    function update($new_instance, $old_instance) {
            $instance = $old_instance;

            /* Strip tags (if needed) and update the widget settings. */
            $instance['title'] = sanitize_text_field($new_instance['title']);
            $instance['width'] = sanitize_text_field($new_instance['width']);
            
            $instance['showTwitter'] = $new_instance['showTwitter'];
            $instance['showFacebook'] = $new_instance['showFacebook'];
            $instance['showGooglePlus'] = $new_instance['showGooglePlus'];
            $instance['showLinkedIn'] = $new_instance['showLinkedIn'];
            $instance['showYouTube'] = $new_instance['showYouTube'];
            $instance['showPinterest'] = $new_instance['showPinterest'];
            $instance['showFlickr'] = $new_instance['showFlickr'];
            
            return $instance;
    }
    
    /* Form for the Wordpress backend */
    function form($instance) {
            /* Set up some default widget settings. */
            $defaults = array('title' => 'Stay Connected', 'width' => '190', 
                            'showTwitter' => 'true', 'showFacebook' => 'true', 'showGooglePlus' => 'true', 'showLinkedIn' => 'true', 'showYouTube' => 'true', 'showPinterest' => 'true', 'showFlickr' => 'true');
            
            /* Creation of the form */
            $instance = wp_parse_args((array) $instance, $defaults); ?>
		<h3><?php _e('General Options', 'smbw'); ?></h3>
                <p>
                    <?php _e('Please add all the social channel details through the "', 'smbw'); ?><a href="options-general.php?page=social-media-badge-widget"><?php _e('Social Media Badge Widget', 'smbw'); ?></a><?php _e('" settings page.', 'smbw'); ?>
                </p> 
                <p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'smbw'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
                        <span class="description"><?php _e('The title of the widget, leave blank for no title.', 'smbw'); ?></span>
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width', 'smbw'); ?></label>
			<input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" style="width:90%;" />
                        <span class="description"><?php _e('The number of pixels for the widget to take.', 'smbw'); ?></span>
		</p>
                <h3><?php _e('Section Display Options', 'smbw'); ?></h3>
                <p>
                    <?php _e('Select which social media channels you would like to be displayed on this widget.', 'smbw'); ?> 
                </p>
                <p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('showTwitter'); ?>" name="<?php echo $this->get_field_name('showTwitter'); ?>" value="true" <?php checked($instance['showTwitter'], 'true'); ?>/>
			<label for="<?php echo $this->get_field_id('showTwitter'); ?>"><?php _e('Display Twitter', 'bcw'); ?></label>
		</p>
                <p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('showFacebook'); ?>" name="<?php echo $this->get_field_name('showFacebook'); ?>" value="true" <?php checked($instance['showFacebook'], 'true'); ?>/>
			<label for="<?php echo $this->get_field_id('showFacebook'); ?>"><?php _e('Display Facebook', 'bcw'); ?></label>
		</p>
                <p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('showGooglePlus'); ?>" name="<?php echo $this->get_field_name('showGooglePlus'); ?>" value="true" <?php checked($instance['showGooglePlus'], 'true'); ?>/>
			<label for="<?php echo $this->get_field_id('showGooglePlus'); ?>"><?php _e('Display GooglePlus', 'bcw'); ?></label>
		</p> 
                 <p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('showLinkedIn'); ?>" name="<?php echo $this->get_field_name('showLinkedIn'); ?>" value="true" <?php checked($instance['showLinkedIn'], 'true'); ?>/>
			<label for="<?php echo $this->get_field_id('showLinkedIn'); ?>"><?php _e('Display LinkedIn', 'bcw'); ?></label>
		</p>                
                <p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('showYouTube'); ?>" name="<?php echo $this->get_field_name('showYouTube'); ?>" value="true" <?php checked($instance['showYouTube'], 'true'); ?>/>
			<label for="<?php echo $this->get_field_id('showYouTube'); ?>"><?php _e('Display You Tube', 'bcw'); ?></label>
		</p> 
                <p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('showPinterest'); ?>" name="<?php echo $this->get_field_name('showPinterest'); ?>" value="true" <?php checked($instance['showPinterest'], 'true'); ?>/>
			<label for="<?php echo $this->get_field_id('showPinterest'); ?>"><?php _e('Display Pinterest', 'bcw'); ?></label>
		</p>
                <p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('showFlickr'); ?>" name="<?php echo $this->get_field_name('showFlickr'); ?>" value="true" <?php checked($instance['showFlickr'], 'true'); ?>/>
			<label for="<?php echo $this->get_field_id('showFlickr'); ?>"><?php _e('Display Flickr', 'bcw'); ?></label>
		</p>
                <p class="description">
                   <?php _e('NOTE: sections will not be displayed if there is no social channels saved in them!', 'smbw'); ?>
                </p>
                <?php
    }
}
add_action( 'widgets_init', create_function('', 'return register_widget("Social_Media_Badge_Widget");'));
?>
