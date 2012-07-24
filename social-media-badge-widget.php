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

function enqueue_scripts() {
    /* Load preinstalled scripts from Wordpress */
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-accordion');
    
    /* Load custom scripts */
    wp_enqueue_script('jquery-load', plugins_url('social-media-badge-widget/js/jquery-load.js'), array('jquery-ui-accordion')); 

    /* Load custom styling */
    wp_enqueue_style('jquery-style', plugins_url('social-media-badge-widget/css/jquery-ui-style.css'));
    wp_enqueue_style('widget-style', plugins_url('social-media-badge-widget/css/social-media-badge-widget-style.css'), array('jquery-style')); 
}    
add_action('wp_enqueue_scripts', 'enqueue_scripts');

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
            echo ('<div class="social-accordion">');

            /* Displays each Accordion tab in turn */
            if ($twitter){
                    echo ('<h3 class="twitter"><a href="#">Twitter</a></h3><div>');
                    echo ('<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
                           <script>
                            new TWTR.Widget({
                            version: 2,
                            type: "profile",
                            rpp: 4,
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
                                live: false,
                                behavior: "all"
                            }
                            }).render().setUser("'.$twitter.'").start();
                           </script>');
                    echo ('</div>');
            }

            if ($facebook){
                    echo ('<h3 class="facebook"><a href="#">Facebook</a></h3><div>');
                    echo ('<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F'.$facebook.'&width='.$width.'&height=590&colorscheme=light&show_faces=true&border_color&stream=true&header=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'px; height:590px;" allowTransparency="true"></iframe>');
                    echo ('</div>');
            }
            
            if ($googleplus){
                    echo ('<h3 class="googleplus"><a href="#">Google+</a></h3><div style="overflow:visible;"><div style="width:300px">');
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
                    echo ('<h3 class="linkedin"><a href="#">LinkedIn</a></h3><div style="overflow:visible;"><div style="width:364px">');
                    echo ('<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                            <script type="IN/CompanyProfile" data-id="'.$linkedin.'" data-format="inline"></script>');
                    echo ('</div></div>');
            }
                        
            if ($youtube){
                    echo ('<h3 class="youtube"><a href="#">You Tube</a></h3><div>');
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
                    echo ('<div class="small"><p>Plugin created by <a href="http://stressfreesites.co.uk/plugins/social-media-badge-widget" target="_blank">StressFree Sites</a></p></div>');
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
            $defaults = array('title' => 'Stay Connected', 'twitter' => '', 'facebook' => '', 'linkedin' => '', 'youtube' => '');
            
            /* Creation of the form */
            $instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('width'); ?>">Width of Widget:</label>
			<input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('twitter'); ?>">Twitter Username (with no '@' sign):</label>
			<input id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" value="<?php echo $instance['twitter']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('facebook'); ?>">Facebook Business Page (name as it appears in URL):</label>
			<input id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" value="<?php echo $instance['facebook']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('googleplus'); ?>">Google+ Business Page (insert company ID, <a href="https://developers.google.com/+/plugins/badge/" target="_blank">get ID here</a>):</label>
			<input id="<?php echo $this->get_field_id('googleplus'); ?>" name="<?php echo $this->get_field_name('googleplus'); ?>" value="<?php echo $instance['googleplus']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('linkedin'); ?>">LinkedIn Company (insert company ID, <a href="https://developer.linkedin.com/company-id-lookup" target="_blank">find out here</a>):</label>
			<input id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" value="<?php echo $instance['linkedin']; ?>" style="width:90%;" />
		</p>
                <p>
			<label for="<?php echo $this->get_field_id('youtube'); ?>">You Tube Channel (name as it appears in URL):</label>
			<input id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" value="<?php echo $instance['youtube']; ?>" style="width:90%;" />
		</p>
                <p>
			<input class="checkbox" type="checkbox" <?php checked($instance['createdby'], 'on'); ?> id="<?php echo $this->get_field_id('createdby'); ?>" name="<?php echo $this->get_field_name('createdby'); ?>" checked/>
			<label for="<?php echo $this->get_field_id('show_map'); ?>">Display created by? Please only remove this after making a <a href="http://stressfreesites.co.uk/plugins/social-media-badge-widget" target="_blank">donation</a> so we can continue making plugins like these.</label>
		</p>
                <?php
    }
}
add_action( 'widgets_init', create_function('', 'return register_widget("Social_Media_Badge_Widget");'));
?>
