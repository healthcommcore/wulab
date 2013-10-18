<?php
  $form['layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'settings',
    '#tree' => TRUE,
  );
	
	$form['layout']['footer_columns'] = array(
		'#type' => 'select',
		'#title' => t('Footer columns visible'),
		'#default_value' => isset($settings['layout']['footer_columns']) ? $settings['layout']['footer_columns'] : 3,
		'#options' => array(
      1 => '1',
      2 => '2',
      3 => '3',
      4 => '4',
    ),
	);

  $form['layout']['responsive_menu'] = array(
    '#type' => 'fieldset',
    '#title' => t('Responsive menu')
  );
  $form['layout']['responsive_menu']['type'] = array(
    '#type' => 'select',
    '#title' => t('Type'),
    '#default_value' => isset($settings['layout']['responsive_menu']['type']) ? $settings['layout']['responsive_menu']['type'] : 'collapse',
    '#empty_option' => t('None'),
    '#options' => array(
      'collapse' => t('Collapsing (default)'),
      'select' => t('Select list'),
    ),
  );

  $form['layout']['responsive_menu']['trigger'] = array(
    '#type' => 'radios',
    '#title' => t('Trigger'),
    '#description' => t('<em>Automatic</em> activates the responsive menu when the width of the menu items exceeds the width of the menu. <em>Breakpoint</em> activates the responsive menu when the breakpoint width exceeds the width of the window.'),
    '#default_value' => isset($settings['layout']['responsive_menu']['trigger']) ? $settings['layout']['responsive_menu']['trigger'] : 'auto',
    '#options' => array(
      'auto' => t('Automatic (default)'),
      'breakpoint' => t('Breakpoint'),
    ),
  );

  // Integer validate!
  $form['layout']['responsive_menu']['breakpoint'] = array(
    '#type' => 'textfield',
    '#title' => t('Breakpoint'),
    '#description' => t('Leave empty to use default.'),
    '#field_suffix' => t('px'),
    '#default_value' => isset($settings['layout']['responsive_menu']['breakpoint']) ? $settings['layout']['responsive_menu']['breakpoint'] : '',
    '#states' => array(
      'visible' => array(
        ':input[name="layout[responsive_menu][trigger]"]' => array('value' => 'breakpoint')
      )
    )
  );