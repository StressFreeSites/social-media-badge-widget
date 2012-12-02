<?php
/*
Plugin Name: Social Media Badge Widget
Plugin URI: http://stressfreesites.co.uk/plugins/social-media-badge-widget
Description: This plugin creates a widget which easily displays the social badge from the leading social media websites (Twitter, Facebook, LinkedIn and You Tube).
Version: 1.1
Author: StressFree Sites
Author URI: http://stressfreesites.co.uk
License: GPL2
*/

/*  Copyright 2012 StressFree Sites  (info@stressfreesites.co.uk : alex@stressfreesites.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
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
    wp_enqueue_script('social-media-badge-widget-jquery-load', plugins_url('social-media-badge-widget/js/social-media-badge-widget-jquery-load.js'), array('jquery','jquery-ui-core','jquery-ui-accordion'),'1.0',true); 
}    
add_action('wp_enqueue_scripts', 'smbw_enqueue_scripts');

function smbw_enqueue_styles() { 
    /* Load custom styling */
    wp_enqueue_style('jquery-ui-style', plugins_url('social-media-badge-widget/css/jquery-ui.css')); 
    wp_enqueue_style('social-media-badge-widget-style', plugins_url('social-media-badge-widget/css/social-media-badge-widget-style.css'), array('jquery-ui-style')); 
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
            $facebook = $instance['facebook'];
            $googleplus = $instance['googleplus'];
            $linkedin = $instance['linkedin'];
            $youtube = $instance['youtube'];
            $createdby = isset($instance['createdby']) ? $instance['createdby'] : false;

            /* Before widget (defined by themes). */
            echo $before_widget;
            
            /* Title of widget (before and after defined by themes). */
            if ($title)
                    echo $before_title . $title . $after_title;
            
            /* Accordion creation */
            echo ('<input type="hidden" id="smbw_collapsible" value="' . get_option('smbw_collapsible') . '">
                   <input type="hidden" id="smbw_allClosed" value="' . get_option('smbw_allClosed') . '">
                   <input type="hidden" id="smbw_openSelection" value="' . get_option('smbw_openSelection') . '">    
                    <div class="social-accordion">');

            /* Displays each Accordion tab in turn */
            if ($twitter){
                    echo ('<h3 class="twitter"><a href="#">'. __('Twitter', 'smbw-language') . '</a></h3><div>');
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
                    echo ('</div>');
            }

            if ($facebook){
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
                    echo ('<h3 class="facebook"><a href="#">' . __('Facebook', 'smbw-language') . '</a></h3><div>');
                    echo ('<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F'.$facebook.'&width='.$width.'&height=' . $height . '&colorscheme=light&show_faces=' . $faces_facebook . '&border_color&stream=' . $stream_facebook . '&header=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'px; height:' . $frame_height . 'px;" allowTransparency="true"></iframe>');
                    echo ('</div>');
            }
            
            if ($googleplus){
                    echo ('<h3 class="googleplus"><a href="#">' . __('Google+', 'smbw-language') . '</a></h3><div style="overflow:visible;"><div style="width:300px">');
                    echo ('<div class="g-plus" data-href="//plus.google.com/'.$googleplus.'?rel=publisher"></div>
                           <script type="text/javascript">
                            (function() {
                                var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
                                po.src = "https://apis.google.com/js/plusone.js";
                                var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
                            })();
                           </script>');
                    echo ('</div></div>');
            }
            
            if ($linkedin){
                    echo ('<h3 class="linkedin"><a href="#">' . __('LinkedIn', 'smbw-language') . '</a></h3><div style="overflow:visible;"><div style="width:364px">');
                    echo ('<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                            <script type="IN/CompanyProfile" data-id="'.$linkedin.'" data-format="inline"></script>');
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
            
            /* Finih the Accordion */
            echo ('</div><!-- Social Accordion -->');
            
            /* Copyright */
            if ($createdby){
                    echo ('<div class="small"><p>' . __('Plugin created by', 'smbw-language') . ' <a href="http://stressfreesites.co.uk/plugins/social-media-badge-widget" target="_blank">StressFree Sites</a></p></div>');
            }
            
            /* After widget (defined by themes). */
            echo $after_widget;
           
    }

    /* Updating the Wordpress backend */
    function update($new_instance, $old_instance) {
            $instance = $old_instance;

            /* Strip tags (if needed) and update the widget settings. */
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['width'] = strip_tags($new_instance['width']);
            $instance['twitter'] = strip_tags($new_instance['twitter']);
            $instance['facebook'] = strip_tags($new_instance['facebook']);
            $instance['googleplus'] = strip_tags($new_instance['googleplus']);
            $instance['linkedin'] = strip_tags($new_instance['linkedin']);
            $instance['youtube'] = strip_tags($new_instance['youtube']);
            $instance['createdby'] = $new_instance['createdby'];
            return $instance;
    }
    
    /* Form for the Wordpress backend */
    function form($instance) {
            /* Set up some default widget settings. */
            $defaults = array('title' => 'Stay Connected', 'width' => '190', 'twitter' => '', 'facebook' => '', 'googleplus' => '', 'linkedin' => '', 'youtube' => '', 'createdby' => 'off');
            
            /* Creation of the form */
            $instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'smbw-language'); ?>:</label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width of Widget', 'smbw-language'); ?>:</label>
			<input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter Username (with no \'@\' sign)', 'smbw-language'); ?>:</label>
			<input id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" value="<?php echo $instance['twitter']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook Business Page (name as it appears in URL)', 'smbw-language'); ?>:</label>
			<input id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" value="<?php echo $instance['facebook']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google+ Business Page (insert company ID', 'smbw-language'); ?>, <a href="https://developers.google.com/+/plugins/badge/" target="_blank"><?php _e('get ID here', 'smbw-language'); ?></a>):</label>
			<input id="<?php echo $this->get_field_id('googleplus'); ?>" name="<?php echo $this->get_field_name('googleplus'); ?>" value="<?php echo $instance['googleplus']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('LinkedIn Company (insert company ID', 'smbw-language'); ?>, <a href="https://developer.linkedin.com/company-id-lookup" target="_blank"><?php _e('find out here', 'smbw-language'); ?></a>):</label>
			<input id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" value="<?php echo $instance['linkedin']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('You Tube Channel (name as it appears in URL)', 'smbw-language'); ?>:</label>
			<input id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" value="<?php echo $instance['youtube']; ?>" style="width:90%;" />
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
