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
            $('.social-accordion .ui-state-default.facebook').css('background', '#0077a9');
            $('.social-accordion .ui-state-default.facebook').not('.ui-state-active').css('background', defaultBackground);
            $('.social-accordion .ui-state-default.googleplus').css('background', '#d03717');
            $('.social-accordion .ui-state-default.googleplus').not('.ui-state-active').css('background', defaultBackground);
            $('.social-accordion .ui-state-default.linkedin').css('background', '#1683b1');
            $('.social-accordion .ui-state-default.linkedin').not('.ui-state-active').css('background', defaultBackground);
            $('.social-accordion .ui-state-default.youtube').css('background', '#fe3031');
            $('.social-accordion .ui-state-default.youtube').not('.ui-state-active').css('background', defaultBackground);

    });
});

jQuery(window).load(function () {
    defaultBackground = jQuery('.social-accordion .ui-state-default').not('.ui-state-active').css('background-color');

    jQuery('.social-accordion .ui-state-default').css('background', defaultBackground);
});