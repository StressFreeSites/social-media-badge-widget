<?php

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

if(!current_user_can('manage_options')){
    wp_die( __( 'You do not have sufficient permissions to access this page.', 'smbw-language' ) );           
}

add_option('smbw_live_twitter','false');


if(isset($_POST['submit'])){
    $openSelection = $_POST['smbw_openSelection'];
    update_option('smbw_openSelection', $openSelection);    
    if(isset($_POST['smbw_collapsible'])) {
        $collapsible = $_POST['smbw_collapsible'];
    }
    else{
        $collapsible = 'false';
    }
    update_option('smbw_collapsible', $collapsible);
    if(isset($_POST['smbw_allClosed'])){
        $allClosed = $_POST['smbw_allClosed'];
    }
    else{
        $allClosed = 'false';
    }
    update_option('smbw_allClosed', $allClosed);
    
    $tweets = $_POST['smbw_tweets'];
    update_option('smbw_tweets', $tweets);
    if(isset($_POST['smbw_live_twitter'])){
        $liveTwitter = $_POST['smbw_live_twitter'];
    }
    else{
        $liveTwitter = 'false';
    }
    update_option('smbw_live_twitter', $liveTwitter); 
    
    if(isset($_POST['smbw_stream_facebook'])) {
        $streamFacebook = $_POST['smbw_stream_facebook'];
    }
    else{
        $streamFacebook = 'false';
    }
    update_option('smbw_stream_facebook', $streamFacebook); 
    if(isset($_POST['smbw_faces_facebook'])) {
        $facesFacebook = $_POST['smbw_faces_facebook'];
    }
    else{
        $facesFacebook = 'false';
    }
    update_option('smbw_faces_facebook', $facesFacebook);  
    
    $loadJqueryUI = $_POST['smbw_load_jquery_ui'];
    update_option('smbw_load_jquery_ui', $loadJqueryUI);
    
    $style = $_POST['smbw_style'];
    update_option('smbw_style', $style); 
    
    $loadScripts = $_POST['smbw_load_scripts'];
    update_option('smbw_load_scripts', $loadScripts);

    ?>
    <div class="updated"><p><strong><?php _e('Options saved.', 'smbw-language');?></strong></p></div>
    <?php
}
else{
    $openSelection = get_option('smbw_openSelection','1');
    $collapsible = get_option('smbw_collapsible','false');
    $allClosed = get_option('smbw_allClosed','false');  
    
    $tweets = get_option('smbw_tweets','5'); 
    $liveTwitter = get_option('smbw_live_twitter','false'); 
    
    $streamFacebook = get_option('smbw_stream_facebook','true');   
    $facesFacebook = get_option('smbw_faces_facebook','false'); 
    
    $loadJqueryUI = get_option('smbw_load_jquery_ui','true');
    $style = get_option('smbw_style','Grey');
    
    $loadScripts = get_option('smbw_load_scripts', array('jQuery' => 1, 
                                                         'jQuery-ui-core' => 1,
                                                         'jQuery-ui-accordion' => 1));
}
?>
<div class="wrap">
    <?php 
    echo '<div class="created-by">' . __('Plugin created by', 'smbw-language') . '<br/><a href="http://stressfreesites.co.uk" target="_blank"><img src="' . plugins_url('social-media-badge-widget/images/stressfreesites.png') . '" /></a></div>';
        
    echo '<div id="icon-options-general" class="icon32"><br /></div><h2>' . __('Social Media Badge Widget', 'smbw-language') . '</h2>'; 
    
    echo '<div class="links"><a href="http://stressfreesites.co.uk/development" target="_blank"><img src="' . plugins_url('social-media-badge-widget/images/home_small.jpg') . '" /><a href="http://facebook.com/stressfreesites" target="_blank"><img src="' . plugins_url('social-media-badge-widget/images/facebook_small.jpg') . '" /><a href="http://twitter.com/stressfreesites" target="_blank"><img src="' . plugins_url('social-media-badge-widget/images/twitter_small.jpg') . '" /><a href="http://stressfreesites.co.uk/forums" target="_blank"><img src="' . plugins_url('social-media-badge-widget/images/support_small.jpg') . '" /></a></div>';            
    ?>
    <form name="smbw_form" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <?php echo '<h3>' . __('General Settings', 'smbw-language') . '</h3>'; ?>
        <table class="form-table">
          <tbody>
            <tr valign="top">
              <th scope="row">
                <label for="smbw_openSelection"><?php _e('Load page open on section','smbw-language'); ?></label>
              </th>
              <td>
                <select name="smbw_openSelection"> 
                    <option <?php if($openSelection == 1) echo ('SELECTED');?>>1</option>
                    <option <?php if($openSelection == 2) echo ('SELECTED');?>>2</option>
                    <option <?php if($openSelection == 3) echo ('SELECTED');?>>3</option>
                    <option <?php if($openSelection == 4) echo ('SELECTED');?>>4</option>
                    <option <?php if($openSelection == 5) echo ('SELECTED');?>>5</option>            
                 </select><p class="description"><?php _e('Opens on section number - 1 for first section, 2 for second section etc.', 'smbw-language'); ?></p>
               </td>               
            </tr>
            <tr valign="top">
              <th scope="row">
                  <?php _e('All collapsible?','smdw-language'); ?>
              </th>
              <td>
                <input class="checkbox" type="checkbox" id="smbw_collapsible" name="smbw_collapsible" <?php checked($collapsible, 'true'); ?> value="true" />
              	<label for="smbw_collapsible"><?php _e('If ticked, all sections can be closed at the same time.', 'smbw-language'); ?></label>
              </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Load page with all sections closed', 'smbw-language'); ?>
                </th>
                <td>
                    <input class="checkbox" type="checkbox" id="smbw_allClosed" name="smbw_allClosed" <?php checked($allClosed, 'true'); ?>  value="true"/>
                    <label for="smbw_allClosed"><?php _e('If ticked, the page will load with all sections closed.','smbw-language');?></label>
                    <p class="description"><?php _e('<strong>NOTE:</strong> all collapsible has to be ticked for this to work.', 'smbw-language'); ?></p>
                </td>
            </tr>           
          </tbody>
        </table>

	<hr/>
        <?php echo '<img src="' . plugins_url('/social-media-badge-widget/images/twitter.png') . '" /><h3 class="smbw-admin-title">' . __('Twitter Settings', 'smbw-language') . '</h3>'; ?>
        <table class="form-table">
          <tbody>
            <tr valign="top">
              <th scope="row">
                <?php _e('Number of tweets to show', 'smbw-language'); ?>
              </th>
              <td>
                <select name="smbw_tweets"> 
                    <option <?php if($tweets == 1) echo ('SELECTED');?>>1</option>
                    <option <?php if($tweets == 2) echo ('SELECTED');?>>2</option>
                    <option <?php if($tweets == 3) echo ('SELECTED');?>>3</option>
                    <option <?php if($tweets == 4) echo ('SELECTED');?>>4</option>
                    <option <?php if($tweets == 5) echo ('SELECTED');?>>5</option>
                    <option <?php if($tweets == 6) echo ('SELECTED');?>>6</option>
                    <option <?php if($tweets == 7) echo ('SELECTED');?>>7</option>
                    <option <?php if($tweets == 8) echo ('SELECTED');?>>8</option>
                    <option <?php if($tweets == 9) echo ('SELECTED');?>>9</option>
                    <option <?php if($tweets == 10) echo ('SELECTED');?>>10</option>                     
                 </select>  
              </td>
            </tr>
            <tr>
              <th scope="row">
                  <?php _e('Live Twitter stream', 'smbw-language'); ?>
              </th>
              <td>
                  <input class="checkbox" type="checkbox" id="smbw_live_twitter" name="smbw_live_twitter" <?php checked($liveTwitter, 'true'); ?> value="true" />
                  <label for="smbw_live_twitter"><?php _e('If ticked, tweets which are created after page load will appear.', 'smbw-language'); ?></label><br />           
                  <p class="description"><?php _e('<strong>NOTE:</strong> only the last three tweets will be displayed.', 'smbw-language'); ?></p>
              </td>
            </tr>       
         </tbody>
        </table>

        <hr/>
        <?php echo '<img src="' . plugins_url('/social-media-badge-widget/images/facebook.png') . '" /><h3 class="smbw-admin-title">' . __('Facebook Settings', 'smbw-language') . '</h3>'; ?>
        <table class="form-table">
          <tbody>
            <tr valign="top">
              <th scope="row">
                  <?php _e('Show stream', 'smbw-language'); ?>
              </th>
              <td>
                  <input class="checkbox" type="checkbox" id="smbw_stream_facebook" name="smbw_stream_facebook" <?php checked($streamFacebook, 'true'); ?> value="true" />
                  <label for="smbw_stream_facebook"><?php _e('If ticked, the newsfeed from the Facebook page will be displayed.', 'smbw-language'); ?></label><br />           
              </td>
            </tr>
            <tr>
              <th scope="row">
                  <?php _e('Show faces', 'smbw-language'); ?>
              </th>
              <td>
                  <input class="checkbox" type="checkbox" id="smbw_faces_facebook" name="smbw_faces_facebook" <?php checked($facesFacebook, 'true'); ?> value="true" />
                  <label for="smbw_faces_facebook"><?php _e('If ticked, faces of some of the fans of the Facebook page will be displayed.', 'smbw-language'); ?></label><br />           
              </td>
            </tr>       
         </tbody>
        </table>
        
        <hr/>
        <?php echo '<h3 class="smbw-admin-title">' . __('Style Settings', 'smbw-language') . '</h3>'; ?>
        <table class="form-table">
          <tbody>
            <tr valign="top">
              <th scope="row">
                  <?php _e('Load jQuery UI styling', 'smbw-language'); ?>
              </th>
              <td>
                  <input class="checkbox" type="checkbox" id="smbw_load_jquery_ui" name="smbw_load_jquery_ui" <?php checked($loadJqueryUI, 'true'); ?> value="true" />
                  <label for="smbw_load_jquery_ui"><?php _e('If another plugin or your theme already has jQuery UI loaded (incorrectly) then untick this to stop the plugin\'s styling overriding and interferaring. NOTE: this will make the selection below redundant', 'smbw-language'); ?></label><br />           
              </td>               
            </tr>
            <tr valign="top">
              <th scope="row">
                <label for="smbw_style"><?php _e('Widget Style','smbw-language'); ?></label>
              </th>
              <td>
                <select name="smbw_style"> 
                    <option <?php if($style == 'Grey') echo ('SELECTED');?>>Grey</option>
                    <option <?php if($style == 'Black') echo ('SELECTED');?>>Black</option>
                    <option <?php if($style == 'Blue') echo ('SELECTED');?>>Blue</option>          
                 </select><p class="description"><?php _e('Change the widget style to match your website', 'smbw-language'); ?></p>
               </td>               
            </tr>       
         </tbody>
        </table>
        
        <hr/>
        <?php echo '<h3 class="smbw-admin-title">' . __('System Settings', 'smbw-language') . '</h3>'; ?>
        <table class="form-table">
          <tbody>
            <tr valign="top">
              <th scope="row">
                  <?php _e('Load jQuery and jQuery UI scripts', 'smbw-language'); ?>
              </th>
              <td>                  
                  <input type="checkbox" name="smbw_load_scripts[jQuery]" value="1" <?php checked( 1 == $loadScripts['jQuery'] ); ?> />
                  <label for="smbw_load_scripts[jQuery]">jQuery</label><br/>
                  <input type="checkbox" name="smbw_load_scripts[jQuery-ui-core]" value="1" <?php checked( 1 == $loadScripts['jQuery-ui-core'] ); ?> />
                  <label for="smbw_load_scripts[jQuery-ui-core]">jQuery-UI-Core</label><br/>
                  <input type="checkbox" name="smbw_load_scripts[jQuery-ui-accordion]" value="1" <?php checked( 1 == $loadScripts['jQuery-ui-accordion'] ); ?> />
                  <label for="smbw_load_scripts[jQuery-ui-accordion]">jQuery-UI-Accordion</label><br/>
                  <p class="description"><?php _e('If another plugin or your theme already has jQuery, jQuery UI or jQuery UI Accordion loaded (incorrectly) then untick the corresponding script to stop the plugin\'s loading it twice causing it not to work.', 'smbw-language'); ?></p>           
              </td>               
            </tr>
      
         </tbody>
        </table>
        
        <hr/>       
        <p class="submit">
            <input type="submit" name="submit" class="button-primary" value="<?php _e('Update Options', 'smbw-language') ?>" />
        </p>
    </form>
    <?php 
    echo '<h3>' . __('Troubleshooting', 'smbw-language') . '</h3>'; 
    echo '<h4>' . __('If the widget does not display correctly' , 'smbw-language') . '</h4>';
    echo '<p class="description">' . __('If this happen it means that you have a theme or plugin which loads jQuery or jQuery UI incorrectly. To resolve this untick the options jQuery, jQuery UI and jQuery UI Accordion. See if that makes the widget display correctly. If it doesn\'t try ticking jQuery UI Accordion, then checking, then ticking jQuery UI and so on.' , 'smbw-language') . '</p>';           
    echo '<h4>' . __('If the widget interferes with the styling of other areas of your website', 'smbw-language') . '</h4>';
    echo '<p class="description">' . __('If this happens you do not need the default styling of the widet. To resolve this untick the styling option load jQuery UI styling.' , 'smbw-language') . '</p>';           
        

?>
