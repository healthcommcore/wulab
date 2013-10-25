<?php
/**
 * @file
 * Contains the Exchange scroller style plugin.
 */

/**
 * Style plugin to render each item in a grid cell.
 */
class views_plugin_style_xe_scroller extends views_plugin_style {
  /**
   * Set default options
   */
  function option_definition() {
    $options = parent::option_definition();
		$options['columns'] = array('default' => 4);

    return $options;
  }

  /**
   * Render the given style.
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['columns'] = array(
      '#type' => 'select',
      '#title' => t('Columns'),
      '#default_value' => $this->options['columns'],
			'#options' => array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
			),
    );
  }
}