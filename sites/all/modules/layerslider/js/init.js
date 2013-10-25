/**
 * @file
 * Initializes LayerSlider.
 */
jQuery(document).ready(function($) {
  $('.layerslider').each(function() {
    var id = $(this).attr('id');

    $(this).layerSlider({
        animateFirstLayer       : false,
        responsive              : Drupal.settings['layerslider'][id]['responsive'],
        autoStart               : Drupal.settings['layerslider'][id]['autoStart'],
        autoPlayVideos          : Drupal.settings['layerslider'][id]['autoPlayVideos'],
        pauseOnHover            : Drupal.settings['layerslider'][id]['pauseOnHover'],
        keybNav                 : Drupal.settings['layerslider'][id]['keybNav'],
        navStartStop            : Drupal.settings['layerslider'][id]['navStartStop'],
        navPrevNext             : Drupal.settings['layerslider'][id]['navPrevNext'],
        navButtons              : Drupal.settings['layerslider'][id]['navButtons'],
        hoverPrevNext           : true,
        hoverBottomNav          : true,
        globalBGImage           : Drupal.settings['layerslider'][id]['globalBGImage'],
        skinsPath               : Drupal.settings['layerslider']['skinsPath'],
        skin                    : 'exchange',
        slideDirection          : Drupal.settings['layerslider'][id]['slideDirection'],
        slideDelay              : Drupal.settings['layerslider'][id]['slideDelay'],
        durationIn              : Drupal.settings['layerslider'][id]['durationIn'],
        durationOut             : Drupal.settings['layerslider'][id]['durationOut'],
        easingIn                : Drupal.settings['layerslider'][id]['easingIn'],
        easingOut               : Drupal.settings['layerslider'][id]['easingOut'],
        delayIn                 : Drupal.settings['layerslider'][id]['delayIn'],
        delayOut                : Drupal.settings['layerslider'][id]['delayOut'],
        showCircleTimer         : false, // only works with jQuery 1.8 and up
        showBarTimer            : Drupal.settings['layerslider'][id]['showBarTimer'],
        thumbnailNavigation     : 'disabled',
    });
  });
});