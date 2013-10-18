(function ($) {

/**
 * Summaries for the vertical tabs.
 */
Drupal.behaviors.exchangeFieldsetSummaries = {
  attach: function (context) {
    // Logo summary
    $('fieldset#edit-logo', context).drupalSetSummary(function (context) {
      if ($('#edit-default-logo', context).attr('checked')) {
        return Drupal.t('Using default');
      }
      else {
        return Drupal.t('Using custom logo');
      }
    });
    // Favicon summary
    $('fieldset#edit-favicon', context).drupalSetSummary(function (context) {
      if ($('#edit-default-favicon', context).attr('checked')) {
        return Drupal.t('Using default');
      }
      else {
        return Drupal.t('Using custom favicon');
      }
    });
  }
};

})(jQuery);