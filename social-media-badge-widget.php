<?php
/*
Plugin Name: Social Media Badge Widget
Plugin URI: http://stressfreesites.co.uk/plugins/social-media-badge-widget
Description: This plugin creates a widget which easily displays the social badge from the leading social media websites (Twitter, Facebook, LinkedIn and You Tube).
Version: 2.4
Author: StressFree Sites
Author URI: http://stressfreesites.co.uk
License: GPL2
*/

/*  Copyright 2012 StressFree Sites  (info@stressfreesites.co.uk : alex@stressfreesites.co.uk)

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

/* Localisation of text */
function smbw_init() {
  load_plugin_textdomain( 'smbw-language', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action('plugins_loaded', 'smbw_init');

function smbw_enqueue_scripts() { 
    /* Load custom scripts */
    
    /* Select which scripts to load */
    $loadScripts = get_option('smbw_load_scripts', array('jQuery' => 1, 
                                                     'jQuery-ui-core' => 1,
                                                     'jQuery-ui-accordion' => 1));

    if(isset($loadScripts['jQuery'])){
        if(isset($loadScripts['jQuery-ui-core'])){
            if(isset($loadScripts['jQuery-ui-accordion'])){
                wp_enqueue_script('social-media-badge-widget-jquery-load', plugins_url('social-media-badge-widget/js/social-media-badge-widget-jquery-load.js'), array('jquery','jquery-ui-core','jquery-ui-accordion'),'1.0',true);
            }
            else{
                wp_enqueue_script('social-media-badge-widget-jquery-load', plugins_url('social-media-badge-widget/js/social-media-badge-widget-jquery-load.js'), array('jquery','jquery-ui-core'),'1.0',true);
            }
        }
        else{
            if(isset($loadScripts['jQuery-ui-accordion'])){
                wp_enqueue_script('social-media-badge-widget-jquery-load', plugins_url('social-media-badge-widget/js/social-media-badge-widget-jquery-load.js'), array('jquery','jquery-ui-accordion'),'1.0',true);          
            }
            else{
                wp_enqueue_script('social-media-badge-widget-jquery-load', plugins_url('social-media-badge-widget/js/social-media-badge-widget-jquery-load.js'), array('jquery'),'1.0',true);
            }
        }
    }
    else{
        if(isset($loadScripts['jQuery-ui-core'])){
            if(isset($loadScripts['jQuery-ui-accordion'])){
                wp_enqueue_script('social-media-badge-widget-jquery-load', plugins_url('social-media-badge-widget/js/social-media-badge-widget-jquery-load.js'), array('jquery-ui-core','jquery-ui-accordion'),'1.0',true);
            }
            else{
                wp_enqueue_script('social-media-badge-widget-jquery-load', plugins_url('social-media-badge-widget/js/social-media-badge-widget-jquery-load.js'), array('jquery-ui-core'),'1.0',true);
            }
        }
        else{
            if(isset($loadScripts['jQuery-ui-accordion'])){
                wp_enqueue_script('social-media-badge-widget-jquery-load', plugins_url('social-media-badge-widget/js/social-media-badge-widget-jquery-load.js'), array('jquery-ui-accordion'),'1.0',true);
            }
            else{
                wp_enqueue_script('social-media-badge-widget-jquery-load', plugins_url('social-media-badge-widget/js/social-media-badge-widget-jquery-load.js'), array(),'1.0',true);
            }           
        }     
    }

    //pass plugin URL variable into Javascript
    wp_localize_script('social-media-badge-widget-jquery-load', 'website_information', array( 'plugin_url' => plugins_url() ));

}    
add_action('wp_enqueue_scripts', 'smbw_enqueue_scripts');

function smbw_enqueue_styles() { 
    /* Load custom styling */
    
    /* Load selected style */
    $loadJqueryUI = get_option('smbw_load_jquery_ui','true');
    if($loadJqueryUI){
        $style = get_option('smbw_style','Grey');
        switch($style){
            case 'Grey':
                wp_enqueue_style('social-media-badge-widget-jquery-ui-style', plugins_url('social-media-badge-widget/css/jquery-ui.css')); 
                break;
            case 'Black':
                wp_enqueue_style('social-media-badge-widget-jquery-ui-style', plugins_url('social-media-badge-widget/css/jquery-ui-black.css'));
                break;
            case 'Blue':
                wp_enqueue_style('social-media-badge-widget-jquery-ui-style', plugins_url('social-media-badge-widget/css/jquery-ui-blue.css'));
                break;
            default:
                wp_enqueue_style('social-media-badge-widget-jquery-ui-style', plugins_url('social-media-badge-widget/css/jquery-ui.css')); 
                break;
        }
        wp_enqueue_style('social-media-badge-widget-style', plugins_url('social-media-badge-widget/css/social-media-badge-widget-style.css'), array('social-media-badge-widget-jquery-ui-style'));
    }
    else{
        wp_enqueue_style('social-media-badge-widget-style', plugins_url('social-media-badge-widget/css/social-media-badge-widget-style.css'), array());
    }
     
} 
add_action('wp_print_styles', 'smbw_enqueue_styles');

/* Admin page functionality */
function smbw_admin(){
    include ('social-media-badge-widget-admin.php');
}
function smbw_admin_init(){   
    wp_register_style('social-media-badge-widget-style-admin', plugins_url('social-media-badge-widget/css/social-media-badge-widget-style-admin.css'));
}
add_action('admin_init', 'smbw_admin_init');

function smbw_admin_actions(){
   /* Register our plugin page */
   $page = add_options_page('Social Media Badge Widget','Social Media Badge Widget', 'manage_options', 'socialmediabadgewidget', 'smbw_admin');

   /* Using registered $page handle to hook stylesheet loading */
   add_action('admin_print_styles-' . $page, 'smbw_admin_styles');
    
}
add_action('admin_menu','smbw_admin_actions');
   
function smbw_admin_styles() {
   wp_enqueue_style('social-media-badge-widget-style-admin');
}
   
/* Message box */
function smbw_theme_admin_notice() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'smbw_theme_ignore_notice') ) {
            echo '<div class="updated"><p>'; 
            printf(__('<p>Thank you for downloading Social Media Badge Widget. We hope you enjoy using the plugin, maybe some of our <a href="http://stressfreesites.co.uk/development" target="_blank">other plugins</a> would be of interest to you.</p><p>We have just launched a new Wordpress theme which might be of interest - <a href="http://greatestwordpresstheme.com" target="_blank">take a look</a>.</p><a href="%1$s">Hide This Notice</a>'), '?smbw_theme_nag_ignore=0');
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

//function smbw_datadisplay($count = 1){
//    $smbwdb = new wpdb(get_option('smbw_dbuser'));
//    $data = $smbwdb->get_var("SQL");
//}

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
            $twitter = $instance['twitter'];
            $twitter_widget_id = $instance['twitter_widget_id'];
            $facebook = $instance['facebook'];
            $facebook_badge = $instance['facebook_badge'];
            $googleplus = $instance['googleplus'];
            $linkedin = $instance['linkedin'];
            $linkedin_profile = $instance['linkedin_profile'];            
            $youtube = $instance['youtube'];
            $pinterest = $instance['pinterest'];
            $flickr = $instance['flickr'];
            
            $createdby = isset($instance['createdby']) ? $instance['createdby'] : false;
            
            /* Before widget (defined by themes). */
            echo $before_widget .'<div class="social-media-badge">';
            
            /* Title of widget (before and after defined by themes). */
            if ($title)
                    echo $before_title . $title . $after_title;
            
            /* Accordion creation */
            echo ('<input type="hidden" id="smbw_collapsible" value="' . get_option('smbw_collapsible') . '" />
                   <input type="hidden" id="smbw_allClosed" value="' . get_option('smbw_allClosed') . '" />
                   <input type="hidden" id="smbw_openSelection" value="' . get_option('smbw_openSelection') . '" />    
                   <div class="social-accordion">');

            /* Displays each Accordion tab in turn */
            if ($twitter){
                    echo ('<h3 class="twitter"><a href="#">'. __('Twitter', 'smbw-language') . '</a></h3><div>');
                    if($twitter_widget_id){
                        echo('<a class="twitter-timeline" href="https://twitter.com/'.$twitter.'" data-widget-id="'.$twitter_widget_id.'">Tweets by @'.$twitter.'</a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                            ');                        
                    }
                    else{
                        echo ('<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
                               <script>
                                new TWTR.Widget({
                                version: 2,
                                type: "profile",
                                rpp: ' . get_option('smbw_tweets', '5') . ',
                                interval: 30000,
                                width: '.($width-2).',
                                height: 300,
                                theme: {
                                    shell: {
                                    background: "#69c0e6",
                                    color: "#ffffff"
                                    },
                                    tweets: {
                                    background: "#ffffff",
                                    color: "#333333",
                                    links: "#009ACD"
                                    }
                                },
                                features: {
                                    scrollbar: false,
                                    loop: false,
                                    live: ' . get_option('smbw_live_twitter', 'false') . ',
                                    behavior: "all"
                                }
                                }).render().setUser("'.$twitter.'").start();
                               </script>');      
                    }
                    echo ('</div>');
            }

            if ($facebook || $facebook_badge){
                    echo ('<h3 class="facebook"><a href="#">' . __('Facebook', 'smbw-language') . '</a></h3><div>');
                    if($facebook){
                        $stream_facebook = get_option('smbw_stream_facebook', 'true');
                        $faces_facebook = get_option('smbw_faces_facebook', 'false');
                        //adjust the height accordly depending on which options are selected.
                        if($stream_facebook == 'true' && $faces_facebook == 'true'){
                            $height = '675';
                            $frame_height = '685';
                        }
                        else if($stream_facebook == 'true' || $faces_facebook == 'true'){
                            $height = '475';
                            $frame_height = '485';
                        }
                        else{
                            $height = '160';
                            $frame_height = '170';
                        }
                        echo ('<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F'.$facebook.'&width='.$width.'&height=' . $height . '&colorscheme=light&show_faces=' . $faces_facebook . '&border_color&stream=' . $stream_facebook . '&header=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'px; height:' . $frame_height . 'px;" allowTransparency="true"></iframe>');
                       
                    }
                    if($facebook_badge){
                        echo $facebook_badge;
                    }
                    echo ('</div>');
            }
            
            if ($googleplus){
                    echo ('<h3 class="googleplus"><a href="#">' . __('Google+', 'smbw-language') . '</a></h3><div style="overflow:visible;"><div style="width:300px">');
                    echo ('<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
                            <g:plus href="https://plus.google.com/'.$googleplus.'"></g:plus>');
                    echo ('</div></div>');
            }
            
            if ($linkedin || $linkedin_profile){
                    echo ('<h3 class="linkedin"><a href="#">' . __('LinkedIn', 'smbw-language') . '</a></h3><div style="overflow:visible;"><div style="width:364px">');
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
                        
            if ($youtube){
                    echo ('<h3 class="youtube"><a href="#">' . __('You Tube', 'smbw-language') . '</a></h3><div>');
                    echo ('<iframe src="http://www.youtube.com/subscribe_widget?p='.$youtube.'" 
                                style="overflow: hidden; height: 105px; width: '.$width.'px; border: 0;" 
                                scrolling="no" frameBorder="0">
                           </iframe>');
                    echo ('</div>');
            }
            
            if ($pinterest){
                    echo ('<h3 class="pinterest"><a href="#">' . __('Pinterest', 'smbw-language') . '</a></h3><div>');
                    $pinterest_width = $width - 20;
                    echo ('<a data-pin-do="embedUser" href="http://pinterest.com/' . $pinterest . '" data-pin-board-width="' . $pinterest_width . '"></a>');
                    echo ('<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>');
////                    echo ('<script type="text/javascript">
////                            (function (w, d, load) {
////                             var script, 
////                             first = d.getElementsByTagName("SCRIPT")[0],  
////                             n = load.length, 
////                             i = 0,
////                             go = function () {
////                               for (i = 0; i < n; i = i + 1) {
////                                 script = d.createElement("SCRIPT");
////                                 script.type = "text/javascript";
////                                 script.async = true;
////                                 script.src = load[i];
////                                 first.parentNode.insertBefore(script, first);
////                               }
////                             }
////                             if (w.attachEvent) {
////                               w.attachEvent("onload", go);
////                             } else {
////                               w.addEventListener("load", go, false);
////                             }
////                            }(window, document, 
////                             ["//assets.pinterest.com/js/pinit.js"]
////                            ));    
////                          </script>');
                    echo ('</div>');                    
            }
            
            if ($flickr){
                    echo ('<h3 class="flickr"><a href="#">' . __('Flickr', 'smbw-language') . '</a></h3><div>');
                    echo ('<style type="text/css"> 
                            .flickr_badge_image {margin:0px;display:inline;}
                            .flickr_badge_image img {border: 0px solid #666666 !important; padding:1px; margin:2px;}
                            #flickr_badge_wrapper {width:'.$width.';text-align:left}
                           </style>');
                    echo ('<div id="flickr_badge_wrapper"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=9&display=latest&size=s&layout=x&source=user&user='. $flickr .'"></script></div>');
                    echo ('</div>');                    
            }
            
            /* Finish the Accordion */
            echo ('</div><!-- .social-accordion -->');
            
            /* Copyright */
            if ($createdby){
                    echo ('<div class="small"><p>' . __('Plugin created by', 'smbw-language') . ' <a href="http://stressfreesites.co.uk/plugins/social-media-badge-widget" target="_blank">StressFree Sites</a></p></div>');
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
            $instance['twitter'] = sanitize_text_field($new_instance['twitter']);
            $instance['twitter_widget_id'] = sanitize_text_field($new_instance['twitter_widget_id']);
            $instance['facebook'] = sanitize_text_field($new_instance['facebook']);
            $instance['facebook_badge'] = $new_instance['facebook_badge'];
            $instance['googleplus'] = sanitize_text_field($new_instance['googleplus']);
            $instance['linkedin'] = sanitize_text_field($new_instance['linkedin']);
            $instance['linkedin_profile'] = sanitize_text_field($new_instance['linkedin_profile']);            
            $instance['youtube'] = sanitize_text_field($new_instance['youtube']);
            $instance['pinterest'] = strip_tags($new_instance['pinterest']);
            $instance['flickr'] = sanitize_text_field($new_instance['flickr']);
            $instance['createdby'] = $new_instance['createdby'];
            return $instance;
    }
    
    /* Form for the Wordpress backend */
    function form($instance) {
            /* Set up some default widget settings. */
            $defaults = array('title' => 'Stay Connected', 'width' => '190', 'twitter' => '', 'facebook' => '', 'googleplus' => '', 'linkedin' => '', 'youtube' => '', 'pinterest' => '', 'createdby' => 'off');
            
            /* Creation of the form */
            $instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width of Widget', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter Username (with no \'@\' sign)', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" value="<?php echo $instance['twitter']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('twitter_widget_id'); ?>"><?php _e('Twitter Widget ID - OPTIONAL (for new Twitter widget, add the widget ID found in the code after ', 'smbw-language'); ?><a href="http://twitter.com/settings/widgets/" target=_blank"><?php _e('creating the widget.', 'smbw-language'); ?></a></label>
			<input id="<?php echo $this->get_field_id('twitter_widget_id'); ?>" name="<?php echo $this->get_field_name('twitter_widget_id'); ?>" value="<?php echo $instance['twitter_widget_id']; ?>" style="width:90%;" />
		</p>                
                <p>
			<label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook Page (insert URL after .com/)', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" value="<?php echo $instance['facebook']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('facebook_badge'); ?>"><?php _e('Facebook Badge (Create a badge ', 'smbw-language');?><a href="http://facebook.com/badges" target="_blank"><?php _e('here', 'smbw-language'); ?></a><?php _e(' then press other to see code. Finally, copy code into box below.)', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('facebook_badge'); ?>" name="<?php echo $this->get_field_name('facebook_badge'); ?>" value="<?php esc_html_e($instance['facebook_badge']); ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google+ (insert ID for page or profile, this is the number in the URL when viewing your Google+ page or profile)', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('googleplus'); ?>" name="<?php echo $this->get_field_name('googleplus'); ?>" value="<?php echo $instance['googleplus']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('LinkedIn Company (insert company ID, ', 'smbw-language'); ?> <a href="https://developer.linkedin.com/plugins/company-profile-plugin" target="_blank"><?php _e('get ID here ', 'smbw-language'); ?></a><?php _e(' by typing in your company name then press get code and find the ID in the code.)', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" value="<?php echo $instance['linkedin']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('linkedin_profile'); ?>"><?php _e('LinkedIn Profile (insert public profile URL after the .com/in/)', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('linkedin_profile'); ?>" name="<?php echo $this->get_field_name('linkedin_profile'); ?>" value="<?php echo $instance['linkedin_profile']; ?>" style="width:90%;" />
		</p>                
                <p>
			<label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('You Tube Channel (insert URL after .com/)', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" value="<?php echo $instance['youtube']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('pinterest'); ?>"><?php _e('Pinterest (insert username as it appears in URL after .com/)', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('pinterest'); ?>" name="<?php echo $this->get_field_name('pinterest'); ?>" value="<?php echo $instance['pinterest']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e('Flickr (insert user ID including the bit after the @)', 'smbw-language'); ?></label>
			<input id="<?php echo $this->get_field_id('flickr'); ?>" name="<?php echo $this->get_field_name('flickr'); ?>" value="<?php echo $instance['flickr']; ?>" style="width:90%;" />
		</p>                
                <p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('createdby'); ?>" name="<?php echo $this->get_field_name('createdby'); ?>" <?php checked($instance['createdby'], 'on'); ?>/>
			<label for="<?php echo $this->get_field_id('createdby'); ?>"><?php _e('Display created by? Please only remove this after making a', 'smbw-language'); ?> <a href="http://stressfreesites.co.uk/plugins/social-media-badge-widget" target="_blank"><?php _e('donation', 'smbw-language'); ?></a><?php _e('so we can continue making plugins like these.', 'smbw-language'); ?></label>
		</p>
                <?php
    }
}
add_action( 'widgets_init', create_function('', 'return register_widget("Social_Media_Badge_Widget");'));
?>
