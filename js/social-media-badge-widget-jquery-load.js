jQuery('document').ready(function($) {
    var collapsibleVar = jQuery('#smbw_collapsible').val();  
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
    
    $('.social-accordion .ui-accordion-header.twitter').mouseover(function() {
        $(this).css('background', '#69c0e6');
    });
    
    $('.social-accordion .ui-accordion-header.facebook').mouseover(function() {
        $(this).css('background', '#0077a9');
    });
    
    $('.social-accordion .ui-accordion-header.googleplus').mouseover(function() {
        $(this).css('background', '#d03717');
    });
    
    $('.social-accordion .ui-accordion-header.linkedin').mouseover(function() {
        $(this).css('background', '#1683b1');
    });

    $('.social-accordion .ui-accordion-header.youtube').mouseover(function() {
        $(this).css('background', '#fe3031');
    });
    
    $('.social-accordion .ui-accordion-header').mouseout(function() {
        $(this).css('background', '#e6e6e6 url(http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/smoothness/images/ui-bg_glass_75_e6e6e6_1x400.png) 50% 50% repeat-x');
    });
    
});




