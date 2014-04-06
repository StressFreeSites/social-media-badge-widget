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

var defaultBackground;

jQuery('document').ready(function($) {
    
    var collapsibleVar = $('#smbw_collapsible').val();  
    
    if(collapsibleVar == "true"){
        collapsibleVar = true;     
    }else{ 
        collapsibleVar = false;
    }
    
    var allClosed = $('#smbw_allClosed').val();
    var openSelection = $('#smbw_openSelection').val();
    var active = 0;
    if(allClosed == "true"){
        active = false;     
    }
    else{
        active = openSelection - 1;
    }
     
    $('.social-accordion').accordion({ active: active, autoHeight: false, collapsible: collapsibleVar});   
    
    $('.social-accordion').mouseout(function() {
            $('.social-accordion .ui-state-default.twitter').css('background', '#69c0e6');
            $('.social-accordion .ui-state-default.twitter').not('.ui-state-active').css('background', defaultBackground);
            $('.social-accordion .ui-state-default.facebook').css('background', '#005789');
            $('.social-accordion .ui-state-default.facebook').not('.ui-state-active').css('background', defaultBackground);
            $('.social-accordion .ui-state-default.googleplus').css('background', '#e04727');
            $('.social-accordion .ui-state-default.googleplus').not('.ui-state-active').css('background', defaultBackground);
            $('.social-accordion .ui-state-default.linkedin').css('background', '#1683b1');
            $('.social-accordion .ui-state-default.linkedin').not('.ui-state-active').css('background', defaultBackground);
            $('.social-accordion .ui-state-default.youtube').css('background', '#fe3031');
            $('.social-accordion .ui-state-default.youtube').not('.ui-state-active').css('background', defaultBackground);
            $('.social-accordion .ui-state-default.pinterest').css('background', '#db3037');
            $('.social-accordion .ui-state-default.pinterest').not('.ui-state-active').css('background', defaultBackground);
            $('.social-accordion .ui-state-default.flickr').css('background', '#fb4aa6');
            $('.social-accordion .ui-state-default.flickr').not('.ui-state-active').css('background', defaultBackground);
    });
    
    defaultBackground = $('.social-accordion .ui-state-default').not('.ui-state-active').css('background-color');

    $('.social-accordion .ui-state-default').css('background', defaultBackground);
    
    $('.social-media-badge .preloader').css('display', 'none');
    $('.social-media-badge .social-accordion').css('display', 'block');  
});

jQuery(window).load(function () {
});