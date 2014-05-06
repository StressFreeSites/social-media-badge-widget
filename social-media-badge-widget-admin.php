<?php
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

define('SMBW_DEFAULT_TAB', 'social-media-settings');

function smbw_settings_styles() {
   wp_enqueue_style('social-media-badge-widget-admin', plugins_url('social-media-badge-widget/css/social-media-badge-widget-admin.min.css'));
   
   // Load Jquery UI
   wp_enqueue_style('social-media-badge-widget-admin-jquery-ui', plugins_url('social-media-badge-widget/css/jquery-ui-admin.min.css'));   
}
function smbw_settings_scripts() {
   wp_enqueue_script('jquery');
   wp_enqueue_script('jquery-ui-core');
   wp_enqueue_script('jquery-ui-accordion');
   wp_enqueue_script('social-media-badge-widget-admin', plugins_url('social-media-badge-widget/js/social-media-badge-widget-admin.min.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-accordion'), '1.0', true);
}
function smbw_settings_page_init() {   
    // Register our plugin page
    $settings_page = add_options_page('Social Media Badge Widget','Social Media Badge Widget', 'manage_options', 'social-media-badge-widget', 'smbw_display_settings_page');

    // Add in check to see if the page is being saved
    add_action('load-' . $settings_page, 'smbw_update_settings');

    // Using registered $page handle to hook stylesheet loading 
    add_action('admin_print_styles-' . $settings_page, 'smbw_settings_styles');     
    
    // Using registered $page handle to hook stylesheet loading 
    add_action('admin_print_scripts-' . $settings_page, 'smbw_settings_scripts');     
}
add_action('admin_menu', 'smbw_settings_page_init');

function smbw_settings_init() {
    $settings = get_option('smbw_settings');
    if ( empty( $settings ) ) {
            $settings = array(
                          'twitter' => '', 'facebook' => '', 'facebook_badge' => '', 'googleplus' => '', 'googleplus_profile' => '', 'linkedin' => '', 'linkedin_profile' => '', 'youtube' => '', 'pinterest' => '', 'flickr' => '',
                          'colour_scheme_twitter' => 'light', 'replies_twitter' => 'false', 'replies_facebook' => 'false', 'colour_scheme_facebook' => 'light', 'faces_facebook' => 'false', 'stream_facebook' => 'true', 'side_linkedin' => 'left',
                          'openSelection' => '1', 'collapsible' => 'true', 'allClosed' => 'true', 'style' => 'Skeleton', 'icons' => 'Colours', 'createdBy' => 'true',
                          'loadJqueryUI' => 'true', 'loadScripts' => array('jQuery' => 'true',  'jQuery-ui-core' => 'true', 'jQuery-ui-accordion' => 'true')
                            );
            add_option('smbw_settings', $settings, '', 'yes');
    }	
}
add_action('init', 'smbw_settings_init');

function smbw_update_settings() {
    if(isset($_POST['Submit'])) {
        check_admin_referer('smbw-settings-page');
        smbw_save_settings();
        
        // Create redirect URL
        $url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
        wp_redirect(admin_url('options-general.php?page=social-media-badge-widget&'.$url_parameters));
        
        exit;
    }
}

function smbw_save_settings() {
    global $pagenow;
    $settings = get_option('smbw_settings');

    if($pagenow == 'options-general.php' && $_GET['page'] == 'social-media-badge-widget') { 
        if (isset ($_GET['tab'])){
            $tab = $_GET['tab']; 
        }
        else {
            $tab = SMBW_DEFAULT_TAB; 
        }
        switch($tab) { 
            case 'social-media-settings':
                $settings['twitter'] = sanitize_text_field($_POST['smbw_twitter']);
                if(isset($_POST['smbw_replies_twitter'])) {
                    $settings['replies_twitter'] = sanitize_text_field($_POST['smbw_replies_twitter']);
                }
                else{
                    $settings['replies_twitter'] = 'false';
                }
                $settings['colour_scheme_twitter'] = $_POST['smbw_colour_scheme_twitter'];
                
                $settings['facebook'] = sanitize_text_field($_POST['smbw_facebook']);
                if(isset($_POST['smbw_stream_facebook'])) {
                    $settings['stream_facebook'] = sanitize_text_field($_POST['smbw_stream_facebook']);
                }
                else{
                    $settings['stream_facebook'] = 'false';
                }
                if(isset($_POST['smbw_faces_facebook'])) {
                    $settings['faces_facebook'] = sanitize_text_field($_POST['smbw_faces_facebook']);
                }
                else {
                    $settings['faces_facebook'] = 'false';
                }              
                $settings['colour_scheme_facebook'] = $_POST['smbw_colour_scheme_facebook'];
                $settings['facebook_badge'] = $_POST['smbw_facebook_badge'];
                
                $settings['googleplus'] = sanitize_text_field($_POST['smbw_googleplus']);
                $settings['googleplus_profile'] = sanitize_text_field($_POST['smbw_googleplus_profile']);
                
                $settings['linkedin'] = sanitize_text_field($_POST['smbw_linkedin']);
                $settings['linkedin_profile'] = sanitize_text_field($_POST['smbw_linkedin_profile']);
                $settings['side_linkedin'] = sanitize_text_field($_POST['smbw_side_linkedin']);
                
                $settings['youtube'] = sanitize_text_field($_POST['smbw_youtube']);
                $settings['pinterest'] = sanitize_text_field($_POST['smbw_pinterest']);
                $settings['flickr'] = sanitize_text_field($_POST['smbw_flickr']);
                break; 
            case 'style-settings': 
                $settings['style'] = $_POST['smbw_style'];
                $settings['icons'] = $_POST['smbw_icons'];
                $settings['openSelection'] = $_POST['smbw_openSelection'];
                
                if(isset($_POST['smbw_collapsible'])) {
                    $settings['collapsible'] = $_POST['smbw_collapsible'];
                }
                else {
                    $settings['collapsible'] = 'false';
                }
                
                if(isset($_POST['smbw_allClosed'])) {
                    $settings['allClosed'] = $_POST['smbw_allClosed'];
                }
                else {
                    $settings['allClosed'] = 'false';
                }
                
                if(isset($_POST['smbw_createdBy'])) {
                    $settings['createdBy'] = $_POST['smbw_createdBy'];   
                }
                else {
                    $settings['createdBy'] = 'false';
                }
                break;
            case 'system-settings' : 
                if(isset($_POST['smbw_loadJqueryUI'])) {
                    $settings['loadJqueryUI'] = $_POST['smbw_loadJqueryUI'];
                }
                else {
                    $settings['loadJqueryUI'] = 'false';
                }
                if(isset($_POST['smbw_loadScripts']['jQuery'])) {
                    $settings['loadScripts']['jQuery'] = $_POST['smbw_loadScripts']['jQuery'];  
                }
                else {
                    $settings['loadScripts']['jQuery'] = 'false';
                }
                if(isset($_POST['smbw_loadScripts']['jQuery-ui-core'])) {
                    $settings['loadScripts']['jQuery-ui-core'] = $_POST['smbw_loadScripts']['jQuery-ui-core'];  
                }
                else {
                    $settings['loadScripts']['jQuery-ui-core'] = 'false';
                }
                if(isset($_POST['smbw_loadScripts']['jQuery-ui-accordion'])) {
                    $settings['loadScripts']['jQuery-ui-accordion'] = $_POST['smbw_loadScripts']['jQuery-ui-accordion'];  
                }
                else {
                    $settings['loadScripts']['jQuery-ui-accordion'] = 'false';
                }
                break;
        }
    }

    if(!current_user_can('unfiltered_html')) {
        if($settings['twitter']) {
            $settings['twitter'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['twitter'])));
        }
        if($settings['facebook']) {
            $settings['facebook'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['facebook'])));
        }
        if($settings['facebook_badge']) {
            $settings['facebook_badge'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['facebook_badge'])));
        }
        if($settings['googleplus']) {
            $settings['googleplus'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['googleplus'])));
        }
        if($settings['googleplus_profile']) {
            $settings['googleplus_profile'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['googleplus_profile'])));
        }
        if($settings['linkedin']) {
            $settings['linkedin'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['linkedin'])));
        }
        if($settings['linkedin_profile']) {
            $settings['linkedin_profile'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['linkedin_profile'])));
        }
        if($settings['youtube']) {
            $settings['youtube'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['youtube'])));
        }
        if($settings['pinterest']) {
            $settings['pinterest'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['pinterest'])));
        }
        if($settings['flickr']) {
            $settings['flickr'] = stripslashes(esc_textarea(wp_filter_post_kses($settings['flickr'])));
        }
    }

    update_option('smbw_settings', $settings);
}

function smbw_display_settings_tabs($current) { 
    $tabs = array('social-media-settings' => 'Social Media Settings',
                  'style-settings' => 'Style Settings',
                  'system-settings' => 'System Settings',
                  'troubleshooting' => 'Troubleshooting'); 

    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo '<a class="nav-tab' . $class . '" href="?page=social-media-badge-widget&tab=' . $tab . '">' . $name . '</a>';   
    }
    echo '</h2>';
}

function smbw_display_settings_page() {
    global $pagenow;
    $settings = get_option('smbw_settings');

    ?>
    <div class="wrap">
        <div class="created-by">
            <?php _e('Plugin created by', 'smbw'); ?><br/><a href="http://stressfreesites.co.uk/?utm_source=backend&utm_medium=plugin&utm_campaign=wordpress" target="_blank"><img src="<?php echo(plugins_url('social-media-badge-widget/images/stressfreesites.png')); ?>" /></a>
        </div>
        <div id="icon-options-general" class="icon32"><br /></div>
        <h2>
            <?php _e('Social Media Badge Widget', 'smbw') ?>
        </h2>
        <div class="links">
            <a href="http://stressfreesites.co.uk/development/?utm_source=backend&utm_medium=plugin&utm_campaign=wordpress" target="_blank"><img src="<?php echo(plugins_url('social-media-badge-widget/images/home_small.jpg')); ?>" /></a>
            <a href="http://facebook.com/stressfreesites" target="_blank"><img src="<?php echo(plugins_url('social-media-badge-widget/images/facebook_small.jpg')); ?>" /></a>
            <a href="http://twitter.com/stressfreesites" target="_blank"><img src="<?php echo(plugins_url('social-media-badge-widget/images/twitter_small.jpg')); ?>" /></a>
            <a href="http://stressfreesites.co.uk/forums" target="_blank"><img src="<?php echo(plugins_url('social-media-badge-widget/images/support_small.jpg')); ?>" /></a>
        </div>       	
        <?php

        if (!isset($_GET['tab'])){
             $_GET['tab'] = SMBW_DEFAULT_TAB;
        }
        smbw_display_settings_tabs($_GET['tab']);
        
        ?>
        <div id="poststuff">
            <form method="post" action="<?php admin_url('options-general.php?page=social-media-badge-widget'); ?>">
                    <?php
                    wp_nonce_field('smbw-settings-page'); 

                    if ($pagenow == 'options-general.php' && $_GET['page'] == 'social-media-badge-widget'){ 
                            
                            $tab = $_GET['tab']; 

                            echo '<table class="form-table">';
                            switch($tab) {
                                    case 'social-media-settings':
                                        echo '<h2 class="smbw-admin-title">' . __('Social Media Settings', 'smbw') . '</h2>';
                                        ?>
                                        <div id="accordion">
                                            <h3 class="smbw-admin-title"><img src="<?php echo(plugins_url('/social-media-badge-widget/images/twitter.png')); ?>" class="icon" alt="Twitter"/><?php _e('Twitter Settings', 'smbw'); ?></h3>
                                            <div>
                                                <label for="smbw_twitter"><?php _e('Twitter','smbw'); ?></label><input id="smbw_twitter" name="smbw_twitter" value="<?php echo $settings['twitter']; ?>" />
                                                <div class="clear"></div> 
                                                <p class="description"> <?php _e('Insert without the \'@\'', 'smbw'); ?></p>                                           
                                                <label for="smbw_colour_scheme_twitter"><?php _e('Colour scheme', 'smbw'); ?></label> 
                                                <select name="smbw_colour_scheme_twitter"> 
                                                        <option <?php if($settings['colour_scheme_twitter'] == 'light') echo ('SELECTED');?> value="light">Light</option>
                                                        <option <?php if($settings['colour_scheme_twitter'] == 'dark') echo ('SELECTED');?> value="dark">Dark</option>
                                                </select>
                                                <div class="clear"></div>
                                                <label for="smbw_replies_twitter"><?php _e('Show Replies?','smbw'); ?></label>          
                                                <input class="checkbox" type="checkbox" id="smbw_replies_twitter" name="smbw_replies_twitter" <?php checked($settings['replies_twitter'], 'true'); ?> value="true" /><p class="label"><?php _e('If ticked, replies will be shown in the Twitter widget.', 'smbw'); ?></p> 
                                            </div>
                                            <h3 class="smbw-admin-title"><img src="<?php echo(plugins_url('/social-media-badge-widget/images/facebook.png')); ?>" class="icon" alt="Facebook"/><?php _e('Facebook Settings', 'smbw'); ?></h3>
                                            <div>
                                                <label for="smbw_facebook"><?php _e('Facebook','smbw'); ?></label><input id="smbw_facebook" name="smbw_facebook" value="<?php echo $settings['facebook']; ?>" />
                                                <div class="clear"></div>
                                                <p class="description"><?php _e('Insert the business page URL (the part at the end, after facebook.com/)', 'smbw'); ?></p>           
                                                <label for="smbw_colour_scheme_facebook"><?php _e('Colour scheme', 'smbw'); ?></label> 
                                                <select name="smbw_colour_scheme_facebook"> 
                                                        <option <?php if($settings['colour_scheme_facebook'] == 'light') echo ('SELECTED');?> value="light">Light</option>
                                                        <option <?php if($settings['colour_scheme_facebook'] == 'dark') echo ('SELECTED');?> value="dark">Dark</option>
                                                </select>
                                                <div class="clear"></div>
                                                <label for="smbw_stream_facebook"><?php _e('Show Stream?','smbw'); ?></label>          
                                                <input class="checkbox" type="checkbox" id="smbw_stream_facebook" name="smbw_stream_facebook" <?php checked($settings['stream_facebook'], 'true'); ?> value="true" /><p class="label"><?php _e('If ticked, the newsfeed from the Facebook business page will be shown.', 'smbw'); ?></p> 
                                                <div class="clear"></div>
                                                <label for="smbw_faces_facebook"><?php _e('Show Faces?', 'smbw'); ?></label>
                                                <input class="checkbox" type="checkbox" id="smbw_faces_facebook" name="smbw_faces_facebook" <?php checked($settings['faces_facebook'], 'true'); ?> value="true" /><p class="label"><?php _e('If ticked, faces of some of the fans of the Facebook business page will be displayed.', 'smbw'); ?></label><br />           
                                                <div class="clear"></div>
                                                <label for="smbw_facebook_badge"><?php _e('Facebook Badge','smbw'); ?></label><textarea id="smbw_facebook_badge" name="smbw_facebook_badge"><?php echo esc_html_e(stripslashes($settings['facebook_badge'])); ?></textarea>
                                                <div class="clear"></div> 
                                                <p class="description"><?php _e('Create a badge ', 'smbw');?><a href="http://facebook.com/badges" target="_blank"><?php _e('here', 'smbw'); ?></a><?php _e(' then press \'other\' to see code. Finally, copy the code into box above.', 'smbw'); ?></p>
                                            </div>
                                            <h3 class="smbw-admin-title"><img src="<?php echo(plugins_url('/social-media-badge-widget/images/googleplus.png')); ?>" class="icon" alt="Google+"/><?php _e('Google+ Settings', 'smbw'); ?></h3>
                                            <div>
                                                <label for="smbw_googleplus"><?php _e('Google+','smbw'); ?></label><input id="smbw_googleplus" name="smbw_googleplus" value="<?php echo $settings['googleplus']; ?>" />
                                                <div class="clear"></div>                                          
                                                <p class="description"><?php _e('Insert ID for page, this is the number in the URL when viewing your Google+ page.', 'smbw'); ?></p>
                                                <label for="smbw_googleplus_profile"><?php _e('Google+ Profile','smbw'); ?></label><input id="smbw_googleplus_profile" name="smbw_googleplus_profile" value="<?php echo $settings['googleplus_profile']; ?>" />
                                                <div class="clear"></div>   
                                                <p class="description"><?php _e('Insert ID profile, this is the number in the URL when viewing your Google+ profile.', 'smbw'); ?></p>
                                            </div>
                                            <h3 class="smbw-admin-title"><img src="<?php echo(plugins_url('/social-media-badge-widget/images/linkedin.png')); ?>" class="icon" alt="LinkedIn"/><?php _e('LinkedIn Settings', 'smbw'); ?></h3>
                                            <div>
                                                <label for="smbw_linkedin"><?php _e('LinkedIn','smbw'); ?></label><input id="smbw_linkedin" name="smbw_linkedin" value="<?php echo $settings['linkedin']; ?>" />
                                                <div class="clear"></div>  
                                                <p class="description"><?php _e('Insert company ID, get ID ', 'smbw'); ?><a href="https://developer.linkedin.com/plugins/company-profile-plugin" target="_blank"><?php _e('here ', 'smbw'); ?></a><?php _e(' by typing in your company name then press get code. Finally, find the ID in the code.', 'smbw'); ?></p>		
                                                <label for="smbw_linkedin_profile"><?php _e('LinkedIn Profile','smbw'); ?></label><input id="smbw_linkedin_profile" name="smbw_linkedin_profile" value="<?php echo $settings['linkedin_profile']; ?>" />
                                                <div class="clear"></div>  
                                                <p class="description"><?php _e('Insert public profile URL, after the linkedin.com/in/', 'smbw'); ?></p>
                                                <label for="smbw_side_linkedin"><?php _e('Popout side', 'smbw'); ?></label> 
                                                <select name="smbw_side_linkedin"> 
                                                        <option <?php if($settings['side_linkedin'] == 'left') echo ('SELECTED');?> value="left">Left</option>
                                                        <option <?php if($settings['side_linkedin'] == 'right') echo ('SELECTED');?> value="right">Right</option>
                                                </select>
                                            </div>
                                            <h3 class="smbw-admin-title"><img src="<?php echo(plugins_url('/social-media-badge-widget/images/youtube.png')); ?>" class="icon" alt="You Tube"/><?php _e('You Tube Settings', 'smbw'); ?></h3>
                                            <div>
                                                <label for="smbw_youtube"><?php _e('You Tube','smbw'); ?></label><input id="smbw_youtube" name="smbw_youtube" value="<?php echo $settings['youtube']; ?>" />
                                                <div class="clear"></div> 
                                                <p class="description"><?php _e('Insert the channel URL, part after the youtube.com/', 'smbw'); ?></p>
                                            </div>
                                            <h3 class="smbw-admin-title"><img src="<?php echo(plugins_url('/social-media-badge-widget/images/pinterest.png')); ?>" class="icon" alt="Pinterest"/><?php _e('Pinterest Settings', 'smbw'); ?></h3>
                                            <div>
                                                <label for="smbw_pinterest"><?php _e('Pinterest','smbw'); ?></label><input id="smbw_pinterest" name="smbw_pinterest" value="<?php echo $settings['pinterest']; ?>" />
                                                <div class="clear"></div>  
                                                <p class="description"><?php _e('Insert username, as it appears in URL after pinterest.com/', 'smbw'); ?></p>	
                                            </div>
                                            <h3 class="smbw-admin-title"><img src="<?php echo(plugins_url('/social-media-badge-widget/images/flickr.png')); ?>" class="icon" alt="Flickr"/><?php _e('Flickr Settings', 'smbw'); ?></h3>
                                            <div>
                                                <label for="smbw_flickr"><?php _e('Flickr','smbw'); ?></label><input id="smbw_flickr" name="smbw_flickr" value="<?php echo $settings['flickr']; ?>" />
                                                <div class="clear"></div> 
                                                <p class="description"><?php _e('Insert user ID including the bit after the \'@\'', 'smbw'); ?></p>
                                            </div>
                                        </div><!-- accordion -->
                                        <?php                                       
                                        break; 
                                    case 'style-settings': 
                                        echo '<h2 class="smbw-admin-title">' . __('Style Settings', 'smbw') . '</h2>'; 
                                        ?>
                                        <tbody>
                                            <tr valign="top">
                                              <th scope="row">
                                                <label for="smbw_style"><?php _e('Widget Style','smbw'); ?></label>
                                              </th>
                                              <td>
                                                <select name="smbw_style"> 
                                                    <option <?php if($settings['style'] == 'Grey') echo ('SELECTED');?>>Grey</option>
                                                    <option <?php if($settings['style'] == 'Black') echo ('SELECTED');?>>Black</option>
                                                    <option <?php if($settings['style'] == 'Blue') echo ('SELECTED');?>>Blue</option>
                                                    <option <?php if($settings['style'] == 'Red') echo ('SELECTED');?>>Red</option>
                                                    <option <?php if($settings['style'] == 'Green') echo ('SELECTED');?>>Green</option>
                                                    <option <?php if($settings['style'] == 'Skeleton') echo ('SELECTED');?>>Skeleton</option>
                                                 </select><p class="description"><?php _e('Change the widget style to match your website - Skeleton will display minimal styling.', 'smbw'); ?></p>
                                               </td>               
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row">
                                                    <label for="smbw_icons"><?php _e('Icon Set','smbw'); ?></label>
                                                </th>
                                                <td>
                                                    <select name="smbw_icons"> 
                                                        <option <?php if($settings['icons'] == 'Colours') echo ('SELECTED');?>>Colours</option>
                                                        <option <?php if($settings['icons'] == 'Grey') echo ('SELECTED');?>>Grey</option>
                                                    </select><p class="description"><?php _e('Change which icon set to use.', 'smbw'); ?></p> 
                                                </td>
                                            </tr> 
                                            <tr valign="top">
                                              <th scope="row">
                                                <label for="smbw_openSelection"><?php _e('Load page open on section','smbw'); ?></label>
                                              </th>
                                              <td>
                                                <select name="smbw_openSelection"> 
                                                    <option <?php if($settings['openSelection'] == 1) echo ('SELECTED');?>>1</option>
                                                    <option <?php if($settings['openSelection'] == 2) echo ('SELECTED');?>>2</option>
                                                    <option <?php if($settings['openSelection'] == 3) echo ('SELECTED');?>>3</option>
                                                    <option <?php if($settings['openSelection'] == 4) echo ('SELECTED');?>>4</option>
                                                    <option <?php if($settings['openSelection'] == 5) echo ('SELECTED');?>>5</option>            
                                                 </select><p class="description"><?php _e('Opens on section number - 1 for first section, 2 for second section etc.', 'smbw'); ?></p>
                                               </td>               
                                            </tr>
                                            <tr valign="top">
                                              <th scope="row">
                                                  <?php _e('All collapsible?','smdw'); ?>
                                              </th>
                                              <td>
                                                <input class="checkbox" type="checkbox" id="smbw_collapsible" name="smbw_collapsible" <?php checked($settings['collapsible'], 'true'); ?> value="true" />
                                                <label for="smbw_collapsible"><?php _e('If ticked, all sections can be closed at the same time.', 'smbw'); ?></label>
                                              </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row">
                                                    <?php _e('Load page with all sections closed', 'smbw'); ?>
                                                </th>
                                                <td>
                                                    <input class="checkbox" type="checkbox" id="smbw_allClosed" name="smbw_allClosed" <?php checked($settings['allClosed'], 'true'); ?>  value="true"/>
                                                    <label for="smbw_allClosed"><?php _e('If ticked, the page will load with all sections closed.','smbw');?></label>
                                                    <p class="description"><?php _e('<strong>NOTE:</strong> all collapsible has to be ticked for this to work.', 'smbw'); ?></p>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row">
                                                    <?php _e('Display Created By','smbw'); ?>
                                                </th>
                                                <td>
                                                    <input class="checkbox" type="checkbox" id="smbw_createdBy" name="smbw_createdBy" value="true" <?php checked($settings['createdBy'], 'true'); ?> />
                                                    <label for="smbw_createdBy"><?php _e('Please only remove this after making a ', 'smbw'); ?><a href="http://stressfreesites.co.uk/plugins/social-media-badge-widget/?utm_source=backend&utm_medium=plugin&utm_campaign=wordpress" target="_blank"><?php _e('donation', 'smbw'); ?></a>, <?php _e('so we can continue making plugins like these.', 'smbw'); ?></label>
                                                </td>
                                            </tr>
                                        </tbody>    
                                        <?php
                                        break;
                                    case 'system-settings': 
                                        echo '<h2 class="smbw-admin-title">' . __('System Settings', 'smbw') . '</h2>'; 
                                        ?>
                                        <tbody>
                                           <tr valign="top">
                                              <th scope="row">
                                                  <?php _e('Load jQuery UI styling', 'smbw'); ?>
                                              </th>
                                              <td>
                                                  <input class="checkbox" type="checkbox" id="smbw_loadJqueryUi" name="smbw_loadJqueryUI" <?php checked($settings['loadJqueryUI'], 'true'); ?> value="true" />
                                                  <label for="smbw_loadJqueryUI"><?php _e('If another plugin or your theme already has jQuery UI loaded (incorrectly) then untick this to stop the plugin\'s styling overriding and interferaring. NOTE: this will make many of the styling option redundant.', 'smbw'); ?></label><br />           
                                              </td>               
                                           </tr>
                                           <tr valign="top">
                                              <th scope="row">
                                                  <?php _e('Load jQuery and jQuery UI scripts', 'smbw'); ?>
                                              </th>
                                              <td>                  
                                                  <input type="checkbox" name="smbw_loadScripts[jQuery]" value="true" <?php checked($settings['loadScripts']['jQuery'], 'true'); ?> />
                                                  <label for="smbw_loadScripts[jQuery]">jQuery</label><br/>
                                                  <input type="checkbox" name="smbw_loadScripts[jQuery-ui-core]" value="true" <?php checked($settings['loadScripts']['jQuery-ui-core'], 'true'); ?> />
                                                  <label for="smbw_loadScripts[jQuery-ui-core]">jQuery-UI-Core</label><br/>
                                                  <input type="checkbox" name="smbw_loadScripts[jQuery-ui-accordion]" value="true" <?php checked($settings['loadScripts']['jQuery-ui-accordion'], 'true'); ?> />
                                                  <label for="smbw_loadScripts[jQuery-ui-accordion]">jQuery-UI-Accordion</label><br/>
                                                  <p class="description"><?php _e('If another plugin or your theme already has jQuery, jQuery UI or jQuery UI Accordion loaded (incorrectly) then untick the corresponding script to stop the plugin\'s loading it twice causing it not to work.', 'smbw'); ?></p>           
                                              </td>               
                                           </tr>
                                        </tbody>
                                        <?php
                                        break;
                                    case 'troubleshooting': 
                                        echo '<h2 class="smbw-admin-title">' . __('Troubleshooting', 'smbw') . '</h2>';
                                        echo '<p><span>' . __('If the widget does not display correctly', 'smbw-languaage') . '</span><p>';
                                        echo '<p class="description">' . __('If this happen it means that you have a theme or plugin which loads jQuery or jQuery UI incorrectly. To resolve this untick the options jQuery, jQuery UI and jQuery UI Accordion. See if that makes the widget display correctly. If it doesn\'t try ticking jQuery UI Accordion, then checking, then ticking jQuery UI and so on.' , 'smbw') . '</p>';           
                                        echo '<hr />';
                                        echo '<p><span>' . __('If the widget interferes with the styling of other areas of your website', 'smbw') . '</span><p>';
                                        echo '<p class="description">' . __('If this happens you do not need the default styling of the widet. To resolve this untick the styling option load jQuery UI styling.' , 'smbw') .'</p>';   
                                        break;
                            }
                            echo '</table>';
                    }
                    if($tab != 'troubleshooting'){
                        ?>
                        <input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
                        <?php
                    }
                    ?>
            </form>
            <hr />
            <div class="donate">
                <h3>Help us develop the plugin further</h3>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="6HK26SVJPG2BG">
                    <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>
            <h3>Let others know about this plugin</h3>
            <a href="https://twitter.com/share" class="twitter-share-button" data-via="StressFreeSites" data-size="large" data-count="none" data-hashtags="wordpress">Tweet</a><br/>
            <div class="fb-share-button" data-href="http://stressfreesites.co.uk/social-media-badge-widget/" data-width="75" data-type="button"></div>            
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        </div>
    </div>
<?php
}
?>