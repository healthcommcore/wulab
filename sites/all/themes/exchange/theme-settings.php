<?php
/**
 * Implementation of hook_form_system_theme_settings_alter()
 */
function exchange_form_system_theme_settings_alter(&$form, &$form_state) {
  // Check if jQuery update is enabled
  if (!module_exists('jquery_update')) {
    drupal_set_message(
      t('<a href="@link" target="_blank">jQuery Update</a> is required for certain Twitter Bootstrap functionality. Exchange can be used
      without it if functionality is not needed.', array('@link' => 'http://drupal.org/project/jquery_update')),
      'warning'
    );
  }
  
	// Add #process function which will move the color fieldset to 
	// vertical tabs and sort vertical tabs alphabetically
	$form['#process'][] = 'exchange_process_form';
	
	// Attachments for the form
	$form['#attached'] = array(
		'css' => array(drupal_get_path('theme', 'exchange') . '/admin.css'),
		'js' => array(drupal_get_path('theme', 'exchange') . '/js/admin.js',),
	);
	$form['#after_build'][] = 'exchange_after_build';

	// Include theme-settings.php into build_info. Otherwise it doesn't get included in the submit
	// process and leads to undefined validate and submit functions error.
	$form_state['build_info']['files'][] = drupal_get_path('theme', 'exchange') . '/theme-settings.php';

	// Define custom validate and submit handlers
  $form['#validate'][] = 'exchange_settings_validate';
  $form['#submit'][] = 'exchange_settings_submit';

  // Load settings
	$settings['toggle_breadcrumb_current_page'] = theme_get_setting('toggle_breadcrumb_current_page', 'exchange');
	$settings['toggle_breadcrumb'] = theme_get_setting('toggle_breadcrumb', 'exchange');
	$settings['toggle_frontpage_title'] = theme_get_setting('toggle_frontpage_title', 'exchange');
	$settings['typography'] = theme_get_setting('typography', 'exchange');
	$settings['slider'] = theme_get_setting('slider', 'exchange');
	$settings['style'] = theme_get_setting('style', 'exchange');
	$settings['layout'] = theme_get_setting('layout', 'exchange');
	
	$form['settings'] = array(
		'#type' => 'vertical_tabs',
	);
	
	$theme_path = drupal_get_path('theme', 'exchange');
	include_once($theme_path . '/includes/exchange.layout.inc');
	include_once($theme_path . '/includes/exchange.typography.inc');
	include_once($theme_path . '/includes/exchange.style.inc');

	if (variable_get('exchange_theme_layerslider', FALSE)) {
		include_once($theme_path . '/includes/exchange.slider.inc');
	} else {
		$form['slider'] = array(
			'#type' => 'fieldset',
			'#title' => t('LayerSlider'),
			'#collapsible' => TRUE,
			'#collapsed' => TRUE,
			'#group' => 'settings',
			'#tree' => TRUE,
		);
		$form['slider']['notification'] = array(
			'#markup' => '<p>' . t("The theme layer LayerSlider implementation has been deprecated as of 1.3.0 and replaced by the LayerSlider module. Refer to <a href='@link'>the documentation</a>.", array('@link' => 'http://docs.aye.fi/exchange/layerslider')) . '</p>',
		);
	}
	
  // Move Toggle display fieldset to vertical tabs
  $form['theme_settings'] += array(
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'settings',
  );
	
  // Add Breadcrumb to the Toggle display fieldset
  $form['theme_settings']['toggle_breadcrumb'] = array(
    '#type' => 'checkbox',
    '#title' => t('Breadcrumb'),
    '#default_value' => $settings['toggle_breadcrumb'],
  );
	
  // Add Current Page in the Breadcrumb to the Toggle display fieldset
  $form['theme_settings']['toggle_breadcrumb_current_page'] = array(
    '#type' => 'checkbox',
    '#title' => t('Current page in the breadcrumb'),
    '#default_value' => $settings['toggle_breadcrumb_current_page'],
  );
	
  // Add Frontpage title to the Toggle display fieldset
  $form['theme_settings']['toggle_frontpage_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Title on the front page'),
    '#default_value' => $settings['toggle_frontpage_title'],
  );
  
  // Move Logo image settings fieldset to vertical tabs
  $form['logo']['#title'] = t('Logo');
  $form['logo'] += array(
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'settings',
  );
  // Remove 'theme-settings-bottom' class as it makes layout break
  $form['logo']['#attributes']['class'] = array();
  
  // Move Shortcut icon settings fieldset to vertical tabs
  $form['favicon']['#title'] = t('Favicon');
  $form['favicon'] += array(
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'settings',
  );
}

/**
 * Act after the form has been build
 */
function exchange_after_build(&$form, &$form_state) {
	// Add libraries here. If they are included in the exchange_form_system_theme_settings(), they
	// are not being loaded if form validation fails and thus renders slider editor unusable.
	drupal_add_library('system', 'ui.sortable');
	drupal_add_library('system', 'ui.draggable');
	drupal_add_library('system', 'ui.dialog');
	
	return $form;
}

/**
 * Process theme settings form
 */
function exchange_process_form($form) {
	if (module_exists('color')) {
		// Move the color fieldset to the vertical tabs
		$form['color']['#collapsible'] = TRUE;
		$form['color']['#collapsed'] = TRUE;
		$form['color']['#group'] = 'settings';

		// Make color fields that will be calculated inaccessible for the user
		if (isset($form['color']['palette'])) {
			$form['color']['palette']['headerbordertop']['#access'] = FALSE;
			$form['color']['palette']['headerborderbottom']['#access'] = FALSE;
			$form['color']['palette']['navbordertop']['#access'] = FALSE;
			$form['color']['palette']['navborderbottom']['#access'] = FALSE;
			$form['color']['palette']['navborderleft']['#access'] = FALSE;
			$form['color']['palette']['navborderright']['#access'] = FALSE;
		}
	}
  
	// Sort vertical tabs alphabetically
	$tabs = array();
  foreach ($form as $key => $item) {
    if (isset($item['#group']) && $item['#group'] == 'settings') {
			$tabs[$key] = $item;
			unset($form[$key]);
		}
  }
	
  uasort($tabs, 'exchange_sort_vtabs');
	
	$i = 0;
	foreach ($tabs as &$tab) {
		$tab['#weight'] = $i;
		$i++;
	}
	
	$form = array_merge($tabs, $form);
	
  return $form;
}

/**
 * Sort vertical tabs alphabetically
 */
function exchange_sort_vtabs($a, $b) {
  return strcasecmp($a['#title'], $b['#title']);
}

/**
 * Validate handler for the settings form
 */
function exchange_settings_validate($form, &$form_state) {
  // Calculate colors that are not entered by user. This includes mainly borders
  // of elements which are overlays and multiplies of bottom colors.
  if (module_exists('color')) {
		$theme_path = drupal_get_path('theme', 'exchange');
		include_once($theme_path . '/includes/exchange.utilities.inc');

		$palette = $form_state['values']['palette'];

		$header_border_top = Color::overlay($palette['headertop'], '#FFFFFF', 0.3);

		// Header bottom border needs to be darker than nav_top and header_bottom.
		// We need to compare which one of header_bottom and nav_top is darker
		// and multiply with that one.
		$darker_color = Color::compare(array($palette['navtop'], $palette['headerbottom']), 'darker');
		$header_border_bottom = Color::multiply($darker_color, '#000000', 0.3);

		// To get good engraved effect to the menu, we need to calculate average
		// of nav_top and nav_bottom
		$nav_average = Color::average(array($palette['navtop'], $palette['navbottom']));
		$nav_border_right = Color::multiply($nav_average, '#000000', 0.3);
		$nav_border_left = Color::overlay($nav_average, '#FFFFFF', 0.3);

		$nav_border_top = Color::overlay($palette['navtop'], '#FFFFFF', 0.25);
		$nav_border_bottom = Color::multiply($palette['navbottom'], '#000000', 0.3);

		// Set values
		form_set_value($form['color']['palette']['headerbordertop'], $header_border_top, $form_state);
		form_set_value($form['color']['palette']['headerborderbottom'], $header_border_bottom, $form_state);
		form_set_value($form['color']['palette']['navbordertop'], $nav_border_top, $form_state);
		form_set_value($form['color']['palette']['navborderbottom'], $nav_border_bottom, $form_state);
		form_set_value($form['color']['palette']['navborderleft'], $nav_border_left, $form_state);
		form_set_value($form['color']['palette']['navborderright'], $nav_border_right, $form_state);
  }

	// Remove preceding comma from the uncollapsed fieldsets data
	$uncollapsed = '';
	if (isset($form_state['values']['slider']['uncollapsed'])) {
		$uncollapsed = $form_state['values']['slider']['uncollapsed'];
		if (substr($uncollapsed, 0, 1) == ',') {
			$uncollapsed = substr($form_state['values']['slider']['uncollapsed'], 1);
		}
	}

	// Set redirect to the active tab
	if (!isset($form_state['redirect'])) {
		$active_tab = $form_state['values']['settings__active_tab'];
		if ($active_tab == 'edit-slider' && isset($form_state['values']['slider']['layers']['slider__layers__active_tab'])) {
			$active_tab = $form_state['values']['slider']['layers']['slider__layers__active_tab'];
		}
		$form_state['redirect'] = array(
			current_path(),
			array(
				'fragment' => $active_tab,
				'query' => array('uncollapsed' => $uncollapsed),
			),
		);
	}

	// Check for a new uploaded global background.
	$validators = array('file_validate_is_image' => array());

	// Process LayerSlider
	// As of 1.3.0 the theme layer implementation is deprecated
  if (variable_get('exchange_theme_layerslider', FALSE)) {
	  $layer_defaults = array(
	    'slidedirection' => 'right',
	    'slidedelay' => 4000,
	    'durationin' => 1000,
	    'durationout' => 1000,
	    'easingin' => 'easeInOutQuint',
	    'easingout' => 'easeInOutQuint',
	    'delayin' => 0,
	    'delayout' => 0,
	  );
	
	  // Unset unnecessary data
	  unset($form_state['values']['slider']['add']);
		unset($form_state['values']['slider']['layers']['slider__layers__active_tab']);

		// Load settings
		$settings['slider'] = theme_get_setting('slider', 'exchange');

		$file = file_save_upload("backgrounds-global", $validators);
		if (isset($file)) {
			// File upload was attempted.
			if ($file) {
				// Delete old image if exists
				if (isset($form_state['values']['slider']['config']['background'])) {
					@drupal_unlink($form_state['values']['slider']['config']['background']);
				}
				
				// Put the temporary file in form_values so we can save it on submit.
				$form_state['values']['slider']['config']['background'] = $file;
			}
			else {
				// File upload failed.
				form_set_error("backgrounds-global", t('The background could not be uploaded.'));
			}
		}
	
		// Process layers
		foreach ($form_state['values']['slider']['layers'] as $lid => &$layer) {
			if (is_array($layer)) {
	      // Check for a new uploaded background.
	      $file = file_save_upload("backgrounds-{$lid}", $validators);
	      if (isset($file)) {
	        // File upload was attempted.
	        if ($file) {
	          // Put the temporary file in form_values so we can save it on submit.
	          $layer['background'] = $file;
						
						// Delete old image if exists
						if (isset($layer['background'])) {
							@drupal_unlink($layer['background']);
						}
	        }
	        else {
	          // File upload failed.
	          form_set_error("backgrounds-{$lid}", t('The background could not be uploaded.'));
	        }
	      }
				
				// Move layer properties from table to layer
				if (isset($layer['tables']['layer']['rows'][0])) {
					foreach ($layer['tables']['layer']['rows'][0] as $cell_name => $cell) {
	          // Do not save the value if it matches the default value and is not empty
	          if (!isset($layer_defaults[$cell_name]) || $layer_defaults[$cell_name] != $cell) {
	            $layer[$cell_name] = $cell;
	          }
					}
				}
				
				// Process sublayers
				if (isset($layer['sublayers'])) {
					foreach ($layer['sublayers'] as $sid => &$sublayer) {
						foreach ($sublayer['properties'] as $key => &$property) {
							if (empty($property) || $property == '_none') {
								unset($sublayer['properties'][$key]);
							}
							
							unset($property);
						}
						
						// Check for a new uploaded sublayer.
						$file = file_save_upload("sublayers-{$lid}-{$sid}", $validators);
						if (isset($file)) {
							// File upload was attempted.
							if ($file) {
								// Put the temporary file in form_values so we can save it on submit.
								$layer['sublayers'][$sid]['image']['file'] = $file;
								// Delete old image if exists
								@drupal_unlink($sublayer['image']['path']);
							}
							else {
								// File upload failed.
								form_set_error("sublayers-{$lid}-{$sid}", t('The sublayer could not be uploaded.'));
							}
						}
						
						unset($sublayer);
					}
				}
				
				// Unset unnecessary data
				unset($layer['tables']);
				unset($layer['delete']);
				unset($layer['background_upload']);
				unset($layer['create_sublayer']);
			}
			
			unset($layer);
		}

		// Sort layers by weight
		usort($form_state['values']['slider']['layers'], 'exchange_sort_layers');
	}
	
	// Load settings
  $settings['typography'] = theme_get_setting('typography', 'exchange');
	
	// Check that font values are numerical
	foreach ($form_state['values']['typography']['elements'] as $key => $element) {
		if (!is_numeric($element['size'][$element['unit']])) {
			form_set_error('typography][elements][body][size][em', t('Font size has to be numerical value.'));
		}
	}
	
	// Check for a new uploaded background (style section).
	$file = file_save_upload('custom-background', $validators);
	if (isset($file)) {
		// File upload was attempted.
		if ($file) {
			// Put the temporary file in form_values so we can save it on submit.
			$form_state['values']['style']['custom_bg']['file'] = $file;
			// Delete old image if exists
			@drupal_unlink($form_state['values']['style']['custom_bg']['path']);
		}
		else {
			// File upload failed.
			form_set_error('custom-background', t('The custom background could not be uploaded.'));
		}
	}
}

/**
 * Sort layers by weight
 */
function exchange_sort_layers($a, $b) {
	if ($a['weight'] == $b['weight']) {
		return 0;
	}
	return ($a['weight'] < $b['weight']) ? -1 : 1;
}

/**
 * Submit handler for the settings form
 */
function exchange_settings_submit($form, &$form_state) {
	// Process LayerSlider
	// As of 1.3.0 the theme layer implementation is deprecated
	if (variable_get('exchange_theme_layerslider', FALSE)) {
		// If the user uploaded a new background, save it to a permanent location.
		if (isset($form_state['values']['slider']['config']['background']) && is_object($form_state['values']['slider']['config']['background'])) {
			$file = $form_state['values']['slider']['config']['background'];
			$form_state['values']['slider']['config']['background'] = file_unmanaged_copy($file->uri, 'public://exchange/' . $file->filename);
		}
	
	  foreach ($form_state['values']['slider']['layers'] as $lid => &$layer) {
	    // If the user uploaded a new background, save it to a permanent location.
	    if (isset($layer['background']) && is_object($layer['background'])) {
				$file = $layer['background'];
	      $layer['background'] = file_unmanaged_copy($file->uri, 'public://exchange/' . $file->filename);
	    }
	    
	    if (isset($layer['sublayers'])) {
	      foreach ($layer['sublayers'] as $sid => &$sublayer) {
	        // If the user uploaded a new sublayer image, save it to a permanent location.
	        if (isset($sublayer['image']['file']) && is_object($sublayer['image']['file'])) {
						$file = $sublayer['image']['file'];
	          $sublayer['image']['path'] = file_unmanaged_copy($file->uri, 'public://exchange/' . $file->filename);
						
						// Unset unnecessary data
	          unset($form_state['values']['slider']['layers'][$lid]['sublayers'][$sid]['image']['file']);
	          unset($form_state['values']['slider']['layers'][$lid]['sublayers'][$sid]['image']['upload']);
	        }
	      }
	    }
	  }
	}
	
	// If the user uploaded a new custom background, save it to a permanent location.
	if (isset($form_state['values']['style']['custom_bg']['file']) && is_object($form_state['values']['style']['custom_bg']['file'])) {
		$file = $form_state['values']['style']['custom_bg']['file'];
		$form_state['values']['style']['custom_bg']['path'] = file_unmanaged_copy($file->uri, 'public://exchange/' . $file->filename);
		
		// Unset unnecessary data
		unset($form_state['values']['style']['custom_bg']['file']);
		unset($form_state['values']['style']['custom_bg']['upload']);
	}
  
  // Get theme name
  $theme = $form_state['build_info']['args'][0];
	
  // Delete old files.
	$path = variable_get('theme_' . $theme . '_files_directory');
  @file_unmanaged_delete_recursive($path);
	
  // Prepare target location for generated files.
  $id = $theme . '-' . substr(hash('sha256', serialize($theme) . microtime()), 0, 8);
  $path  = 'public://exchange/' . $id;
  file_prepare_directory($path, FILE_CREATE_DIRECTORY);
  variable_set('theme_' . $theme . '_files_directory', $path);
	
	// Load used Google Web Fonts
	$gwf_path = './' . drupal_get_path('theme', 'exchange') . '/includes/webfonts.json';
	$file = file_get_contents($gwf_path);
	$gwf = json_decode($file);
	$web_fonts = array();
	foreach ($gwf->items as $item) {
		 $web_fonts[] = $item->family;
	}
	$used_web_fonts = array();
  
  // Create CSS syntax
  $css = array();
  foreach ($form_state['values']['typography']['elements'] as $selector => $element) {
    // Replace id_ with '#'. It cannot be used directly in the form structure
    // since Drupal prefixes properties with #.
    $selector = str_replace('id_', '#', $selector);

    $properties['font-family'] = $element['font'];
    
    $properties['font-size'] = $element['size'][$element['unit']];
    $properties['font-size'] .= $element['unit'];
		if ($element['bold'] == 1) {
			$properties['font-weight'] = 'bold';
		}
		else {
			$properties['font-weight'] = 'normal';
		}
    
    // Syntax for properties
    foreach ($properties as $property => $value) {
      $properties[$property] = "{$property}: {$value};";
    }
    
    // Syntax for element
    $css[$selector] = $selector . '{' . implode('', $properties) . '}';
		
		// Check if the font is Google Web Font. We need to strip fallback font 
		// and apostrophes for matching
		$font = str_replace(array("'", ', sans-serif'), array('', ''), $element['font']);
		
		// If element is using Web Font, add it to the $used_web_fonts
		if (in_array($font, $web_fonts) && !in_array(urlencode($font), $used_web_fonts)) {
			$used_web_fonts[] = urlencode($font);
		}
  }
	
	// Get contents of Google Web Fonts. We cannot use @import directly since
	// Firefox has really strict file origin policy.
	if (!empty($used_web_fonts)) {
		$gwf_url = 'http://fonts.googleapis.com/css?family=' . implode('|', $used_web_fonts);
		$gwf_contents = file_get_contents($gwf_url);
	}
		
  // Save to file
  $css = implode("\n", $css);
  if (isset($gwf_contents)) {
  	$css .= "\n" . $gwf_contents;
  }
  $file_name = $theme . '.fonts.css';
  $filepath = "{$path}/{$file_name}";
  file_save_data($css, $filepath, FILE_EXISTS_REPLACE);
}

/**
 * Load a list of Google Web Fonts
 */
function exchange_load_gwf() {
  $path = './' . drupal_get_path('theme', 'exchange') . '/includes/webfonts.json';
  $file = file_get_contents($path);
  return json_decode($file);
}

function exchange_layer_crud($form, &$form_state) {
	$name = $form_state['triggering_element']['#name'];
	$exploded_name = explode('-', $form_state['triggering_element']['#name']);
	$name = isset($exploded_name[1]) ? $exploded_name[0] : $name;
	
	switch ($name) {
		case 'create':
      $layer_count = isset($form_state['values']['slider']['layers']) ? count($form_state['values']['slider']['layers']) : 0;
			$form_state['values']['slider']['layers'][] = array(
        'title' => t('Layer #@number', array('@number' => $layer_count + 1)),
				'sublayers' => array(),
      );
			
			break;
		case 'delete':
			$lid = $form_state['clicked_button']['#parents'][2];
			$layer = $form_state['values']['slider']['layers'][$lid];
		
			// Delete layer background
			@drupal_unlink($layer['background']);
			
			// Delete sublayer images
			if (isset($layer['sublayers'])) {
				foreach ($layer['sublayers'] as $sublayer) {
					// Delete sublayer from file system
					@drupal_unlink($sublayer['image']['path']);
				}
			}
			
			unset($form_state['values']['slider']['layers'][$lid]);
			// If there is no layers left, add one empty
			if (empty($form_state['values']['slider']['layers'])) {
				$form_state['values']['slider']['layers'][0] = array(
					'title' => t('Layer #@number', array('@number' => 1))
				);
			}
			
			break;
	}
	
	end($form_state['values']['slider']['layers']);
	$form_state['lid'] = key($form_state['values']['slider']['layers']);
	
	variable_set($form_state['values']['var'], $form_state['values']);
	
	// Set redirect to the active tab
	$index = count($form_state['values']['slider']['layers']) - 1;
	$form_state['redirect'] = array(
		current_path(),
		array(
			'fragment' => "edit-slider-layers-{$index}",
		),
	);
	
	// Remove preceding comma from the uncollapsed fieldsets data
	$uncollapsed = $form_state['values']['slider']['uncollapsed'];
	if (substr($uncollapsed, 0, 1) == ',') {
		$uncollapsed = substr($form_state['values']['slider']['uncollapsed'], 1);
	}
	
	if (!empty($uncollapsed)) {
		$form_state['redirect'][1]['query']['uncollapsed'] = $uncollapsed;
	}
}

/**
 * Delete global background
 */
function exchange_global_background_delete($form, &$form_state) {
  // Delete file from file system
  @drupal_unlink($form_state['values']['slider']['config']['background']);
  
  // Unset background and save changes
  unset($form_state['values']['slider']['config']['background']);
  variable_set($form_state['values']['var'], $form_state['values']);
  
  drupal_set_message(t('Background deleted.'));
	
	$form_state['redirect'] = array(
		current_path(),
		array(
			'fragment' => "edit-slider-layers-{$lid}",
		),
	);
	
	// Remove preceding comma from the uncollapsed fieldsets data
	$uncollapsed = $form_state['values']['slider']['uncollapsed'];
	if (substr($uncollapsed, 0, 1) == ',') {
		$uncollapsed = substr($form_state['values']['slider']['uncollapsed'], 1);
	}
	
	if (!empty($uncollapsed)) {
		$form_state['redirect'][1]['query']['uncollapsed'] = $uncollapsed;
	}
}

/**
 * Delete layer background
 */
function exchange_background_delete($form, &$form_state) {
  $lid = $form_state['clicked_button']['#parents'][2];
  
  // Delete file from file system
  @drupal_unlink($form_state['values']['slider']['layers'][$lid]['background']);
  
  // Unset background and save changes
  unset($form_state['values']['slider']['layers'][$lid]['background']);
  variable_set($form_state['values']['var'], $form_state['values']);
  
  drupal_set_message(t('Background deleted.'));
	
	$form_state['redirect'] = array(
		current_path(),
		array(
			'fragment' => "edit-slider-layers-{$lid}",
		),
	);
	
	// Remove preceding comma from the uncollapsed fieldsets data
	$uncollapsed = $form_state['values']['slider']['uncollapsed'];
	if (substr($uncollapsed, 0, 1) == ',') {
		$uncollapsed = substr($form_state['values']['slider']['uncollapsed'], 1);
	}
	
	if (!empty($uncollapsed)) {
		$form_state['redirect'][1]['query']['uncollapsed'] = $uncollapsed;
	}
}

/**
 * Add sublayer
 */
function exchange_sublayer_create($form, &$form_state) {
	$lid = $form_state['triggering_element']['#parents'][2];
	
	$sublayer_count = 0;
	if (isset($form_state['values']['slider']['layers'][$lid]['sublayers'])) {
		$sublayer_count = count($form_state['values']['slider']['layers'][$lid]['sublayers']);
	}
	
	$form_state['values']['slider']['layers'][$lid]['sublayers'][] = array(
		'title' => t('Sublayer #@number', array('@number' => $sublayer_count + 1)),
		'x' => 50,
		'y' => 50,
		'weight' => 0,
	);
	$sid = count($form_state['values']['slider']['layers'][$lid]['sublayers']) - 1;
	
	variable_set($form_state['values']['var'], $form_state['values']);
	
	$form_state['redirect'] = array(
		current_path(),
		array(
			'fragment' => "edit-slider-layers-{$lid}",
		),
	);
	
	// Uncollapse newly created sublayer
	$uncollapsed = "edit-slider-layers-{$lid}-sublayers-{$sid},";
	$uncollapsed .= $form_state['values']['slider']['uncollapsed'];
	
	if (!empty($uncollapsed)) {
		$form_state['redirect'][1]['query']['uncollapsed'] = $uncollapsed;
	}
}

/**
 * Delete sublayer
 */
function exchange_sublayer_delete($form, &$form_state) {
	$lid = $form_state['triggering_element']['#parents'][2];
	$sid = $form_state['triggering_element']['#parents'][4];
  
  // Delete file from file system
	@drupal_unlink($form_state['values']['slider']['layers'][$lid]['sublayers'][$sid]['image']['path']);
  
  // Unset sublayer and save changes
	unset($form_state['values']['slider']['layers'][$lid]['sublayers'][$sid]);
	variable_set($form_state['values']['var'], $form_state['values']);
  
  drupal_set_message(t('Sublayer deleted.'));
	
	$form_state['redirect'] = array(
		current_path(),
		array(
			'fragment' => "edit-slider-layers-{$lid}",
		),
	);
	
	// Remove preceding comma from the uncollapsed fieldsets data
	$uncollapsed = $form_state['values']['slider']['uncollapsed'];
	if (substr($uncollapsed, 0, 1) == ',') {
		$uncollapsed = substr($form_state['values']['slider']['uncollapsed'], 1);
	}
	
	if (!empty($uncollapsed)) {
		$form_state['redirect'][1]['query']['uncollapsed'] = $uncollapsed;
	}
}