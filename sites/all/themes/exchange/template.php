<?php

/**
 * Override of theme_breadcrumb()
 */
function exchange_breadcrumb($variables) {
	$settings['toggle_breadcrumb_current_page'] = theme_get_setting('toggle_breadcrumb_current_page', 'exchange');
	$settings['toggle_breadcrumb'] = theme_get_setting('toggle_breadcrumb', 'exchange');
	
	if ($settings['toggle_breadcrumb'] == 0) {
		return NULL;
	}

  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
		
		// Add current item to the breadcrumb if enabled
		if ($settings['toggle_breadcrumb_current_page'] == 1) {
			$breadcrumb[] = '<span class="current">' . drupal_get_title() . '</span>';
		}

    $output .= '<div class="breadcrumb">' . implode(' &raquo; ', $breadcrumb) . '</div>';
    return $output;
  }
}

/**
 * Override of them_menu_link
function exchange_menu_link(array $variables){
	echo '<p>';
	print_r($variables);
	echo '</p>';
}
 */
/*
function exchange_menu_item($mid, $children='', $leaf=TRUE, $extraclass=''){
	//get current menu item
	$item = menu_get_item($mid); 
	//decide whether to add the active class to this menu item
	if((drupal_get_normal_path($item['path']) == $_GET['q']) ||
		(strpos(drupal_get_path_alias($_GET['q']), drupal_get_path_alias($item['path'])) == 0)
		|| (drupal_is_front_page() && $item['path'] == '<front>')){
			$active_class =' active';
		}
	else{
		$active_class = '';
	}
	return '<li class="'. ($leaf ? 'leaf' : ($children ? 'expanded' : 'collapsed')) . ($extraclass ? ' ' . $extraclass : '') . $active_class . '">' . menu_item_link($mid, TRUE, $extraclass) . $children . "</li>\n";
}
 */


/**
 * Preprocess variables for html.tpl.php
 */
function exchange_preprocess_html(&$variables) {
  // Load settings
  $settings['typography'] = theme_get_setting('typography', 'exchange');
  $settings['slider'] = theme_get_setting('slider', 'exchange');
  $settings['layout'] = theme_get_setting('layout', 'exchange');
  $settings['style'] = theme_get_setting('style', 'exchange');
	
	// Set body background
	if ($settings['style']['background'] == 'custom') {
		$url = file_create_url($settings['style']['custom_bg']['path']);
		$repeat = $settings['style']['custom_bg']['repeat'];
		$pos = $settings['style']['custom_bg']['y_position'] . ' ' . $settings['style']['custom_bg']['x_position'];
		$variables['attributes_array']['style'] = "background:url({$url}) {$repeat} {$pos};";
	} else {
		$variables['classes_array'][] = $settings['style']['background'];
	}
  
  // Make $base_path available to html.tpl.php
  global $base_path;
  $variables['base_path'] = $base_path;
  
  // Add jQuery UI effects (includes easing functionality which is 
  // needed for drop down menus and slider)
  drupal_add_library('system', 'effects');
  
  // Add Twitter Bootstrap functions if jquery_update has been enabled
  if (module_exists('jquery_update')) {
    drupal_add_js(drupal_get_path('theme', 'exchange') . '/js/bootstrap.min.js', array('group' => JS_THEME, 'every_page' => TRUE));
    drupal_add_js(drupal_get_path('theme', 'exchange') . '/js/exchange.bootstrap.js', array('group' => JS_THEME, 'every_page' => TRUE));
  }
  
	// Add slider settings
	if (variable_get('exchange_theme_layerslider', FALSE)) {
	  drupal_add_js(array('exchange' => array('autostart' => $settings['slider']['config']['autostart'])), 'setting');
	  drupal_add_js(array('exchange' => array('pauseonhover' => $settings['slider']['config']['pauseonhover'])), 'setting');
	  drupal_add_js(array('exchange' => array('autoplayvideos' => $settings['slider']['config']['autoplayvideos'])), 'setting');
	}

  // Add responsive settings
  $responsive_menu_type = isset($settings['layout']['responsive_menu']['type']) ? $settings['layout']['responsive_menu']['type'] : 'collapse';
  drupal_add_js(array('exchange' => array('responsive_menu_type' => $responsive_menu_type)), 'setting');

  $responsive_menu_trigger = isset($settings['layout']['responsive_menu']['trigger']) ? $settings['layout']['responsive_menu']['trigger'] : 'auto';
  drupal_add_js(array('exchange' => array('responsive_menu_trigger' => $responsive_menu_trigger)), 'setting');

  $responsive_menu_breakpoint = '1023';
  if (isset($settings['layout']['responsive_menu']['breakpoint']) && !empty($settings['layout']['responsive_menu']['breakpoint'])) {
		$responsive_menu_breakpoint = $settings['layout']['responsive_menu']['breakpoint'];
  }
  drupal_add_js(array('exchange' => array('responsive_menu_breakpoint' => $responsive_menu_breakpoint)), 'setting');

	
	if (!empty($variables['page']['sidebar_first'])) {
		// Unset no-sidebars class if exists
		$key = array_search('no-sidebars', $variables['classes_array']);
		if ($key) {
			unset($variables['classes_array'][$key]);
		}
		
		// Add sidebar class
		// $variables['classes_array'][] = 'sidebar-' . $settings['layout']['sidebar'];
		$variables['classes_array'][] = 'sidebar-right';
	}
  
  // Add button class
  $variables['classes_array'][] = $settings['style']['button'];

  if (!empty($variables['page']['featured'])) {
    $variables['classes_array'][] = 'featured';
  }

  if (!empty($variables['page']['triptych_first'])
    || !empty($variables['page']['triptych_middle'])
    || !empty($variables['page']['triptych_last'])) {
    $variables['classes_array'][] = 'triptych';
  }

  if (!empty($variables['page']['footer_firstcolumn'])
    || !empty($variables['page']['footer_secondcolumn'])
    || !empty($variables['page']['footer_thirdcolumn'])
    || !empty($variables['page']['footer_fourthcolumn'])) {
    $variables['classes_array'][] = 'footer-columns';
  }

	// Set path to the generated files directory
  global $theme_key;
  $theme = $theme_key;
  $path = variable_get('theme_' . $theme . '_files_directory');
  
  // Load stylesheet for fonts
  $filepath = $path . '/' . $theme . '.fonts.css';
  if (file_exists($filepath)) {
    drupal_add_css($filepath, array(
      'preprocess' => TRUE,
      'group' => CSS_THEME,
      'media' => 'screen',
      'every_page' => TRUE,
		));
  }
	
	// Add viewport tag
	$viewport = array(
		'#type' => 'html_tag',
		'#tag' => 'meta',
		'#attributes' => array(
			'name' =>  'viewport',
			'content' =>  'width=device-width, initial-scale=1.0'
		)
	);
	drupal_add_html_head($viewport, 'viewport');
}

/**
 * Override or insert variables into the page template for HTML output.
 */
function exchange_process_html(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_html_alter($variables);
  }
}

/**
 * Override or insert variables into the page template.
 */
function exchange_preprocess_page(&$variables) {
	// Load settings
	$settings['toggle_breadcrumb'] = theme_get_setting('toggle_breadcrumb', 'exchange');
	$settings['toggle_frontpage_title'] = theme_get_setting('toggle_frontpage_title', 'exchange');
	$settings['slider'] = theme_get_setting('slider', 'exchange');
	$settings['layout'] = theme_get_setting('layout', 'exchange');
	
	// Set helper $variables['hide_title'] variable
	$variables['hide_title'] = FALSE;
	if ($settings['toggle_frontpage_title'] == 0 && drupal_is_front_page()) {
		$variables['hide_title'] = TRUE;
	}

	// Create a footer span class depending on set amount footer columns
	$variables['footer_span'] = 4;
	$variables['footer_columns'] = 3;
	$variables['footer_column_indexes'] = array(
		1 => 'footer_firstcolumn',
		2 => 'footer_secondcolumn',
		3 => 'footer_thirdcolumn',
		4 => 'footer_fourthcolumn',
	);
	if (isset($settings['layout']['footer_columns'])) {
		$variables['footer_span'] = 12 / $settings['layout']['footer_columns'];
		$variables['footer_columns'] = $settings['layout']['footer_columns'];
	}

  // Create slider
  if (variable_get('exchange_theme_layerslider', FALSE)) {
		if ($settings['slider']['config']['status'] == 1 && drupal_is_front_page()) {
			// Add global bg
			if (isset($settings['slider']['config']['background']) && !empty($settings['slider']['config']['background'])) {
				$background = 'background-image:url(' . file_create_url($settings['slider']['config']['background']) . ');';
			} else {
				$background = '';
			}

			$height = "{$settings['slider']['config']['height']}px";
			$width = "{$settings['slider']['config']['width']}px";
			
			// If there are layers, add slider to the Featured region
			$variables['page']['slider']['slider_container'] = array(
				'#type' => 'markup',
				'#prefix' => "<div id='slider-container' style='{$background}max-width:{$width};'>",
				'#suffix' => '</div>',
			);
	    
			$variables['page']['slider']['slider_container']['slider'] = array(
				'#type' => 'markup',
				'#prefix' => "<div id='slider' style='width:{$width};height:{$height};'>",
				'#suffix' => '</div>',
			);

			if (isset($settings['slider']['layers'])) {
				foreach ($settings['slider']['layers'] as $lid => $layer) {
					if (!empty($layer)) {
						// Set layer properties
						$not_properties = array('weight', 'title', 'background', 'sublayers');
						$properties = array();
						foreach ($layer as $property => $value) {
							if (!in_array($property, $not_properties) && !empty($value)) {
								$properties[$property] = "{$property}: {$value};";
							}
						}
						
						$variables['page']['slider']['slider_container']['slider'][$lid] = array(
							'#type' => 'markup',
							'#prefix' => "<div class='ls-layer' id='layer-{$lid}' rel='" . implode('', $properties) . "'>",
							'#suffix' => '</div>',
						);
						
						// Get layer background path
						if (isset($layer['background']) && !empty($layer['background'])) {
							$path = file_create_url($layer['background']);
							$variables['page']['slider']['slider_container']['slider'][$lid]['bg'] = array(
								'#type' => 'markup',
								'#markup' => "<img src='{$path}' alt='layer{$lid}-background' class='ls-bg' />",
							);
						}
						
						// Sublayers
						if (isset($layer['sublayers'])) {
							$i = 2;
							foreach ($layer['sublayers'] as $sid => $sublayer) {
								// Set sublayer properties
								foreach ($sublayer['properties'] as $property => $value) {
									$properties[$property] = "{$property}: {$value};";
								}
								$style = "left:{$sublayer['x']}px;top:{$sublayer['y']}px;z-index:{$sublayer['weight']}";
	              
	              switch ($sublayer['type']) {
									case 'heading':
										$content = theme_html_tag(array('element' => array(
											'#tag' => $sublayer['heading']['level'],
											'#attributes' => array(
												'class' => array("ls-s{$i}"),
												'rel' => implode('', $properties),
												'style' => $style,
											),
											'#value' => $sublayer['heading']['heading'],
										)));
										break;
	                case 'image':
										if (isset($sublayer['image']['path'])) {
											$content = theme_image(array(
												'path' => file_create_url($sublayer['image']['path']),
												'attributes' => array(
													'class' => array("ls-s{$i}"),
													'rel' => implode('', $properties),
													'style' => $style,
	                        'alt' => "ls-s{$i}",
												)
											));
										}
	                  break;
	                case 'button':
										$content = "<div class='ls-s{$i}' rel='" . implode('', $properties) . "' style='{$style}'>";
										$content .= l(
											$sublayer['button']['text'], 
											$sublayer['button']['url'], 
											array('attributes' => array(
												'class' => array('button', $sublayer['button']['color']),
											))
										);
										$content .= '</div>';
	                  break;
	                case 'html':
										$content = "<div class='ls-s{$i}' rel='" . implode('', $properties) . "' style='{$style}'>";
	                  $content .= $sublayer['html']['content'];
										$content .= '</div>';
	                  break;
	                case 'video':
										if (!empty($sublayer['video']['id'])) {
											$content = "<div class='ls-s{$i}' rel='" . implode('', $properties) . "' style='{$style}'>";
											// Vimeo IDs are numerical while Youtube's are not
											if (is_numeric($sublayer['video']['id'])) {
												$content .= "<iframe 
													src='http://player.vimeo.com/video/{$sublayer['video']['id']}?badge=0&amp;color=c9ff23'
													width='{$sublayer['video']['width']}' 
													height='{$sublayer['video']['height']}' 
													frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen>
												</iframe>";
											} else {
												$content .= "<iframe 
													width='{$sublayer['video']['width']}' 
													height='{$sublayer['video']['height']}' 
													src='http://www.youtube.com/embed/{$sublayer['video']['id']}' 
													frameborder='0' allowfullscreen>
												</iframe>";
											}
											$content .= '</div>';
										}
	                  break;
	              }
								
								$variables['page']['slider']['slider_container']['slider'][$lid][$sid] = array(
									'#type' => 'markup',
									'#markup' => $content,
								);
								$i++;
							}
						}
					}
				}
			}
		}
	}
  
  // Create a menu variable that contains nested links. Default $main_menu
  // doesn't contain them.
  $main_menu_tree = menu_tree_all_data('main-menu');
  $variables['main_menu_expanded'] = menu_tree_output($main_menu_tree);
}

/**
 * Override or insert variables into the page template.
 */
function exchange_process_page(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_page_alter($variables);
  }
	
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
  // Since the title and the shortcut link are both block level elements,
  // positioning them next to each other is much simpler with a wrapper div.
  if (!empty($variables['title_suffix']['add_or_remove_shortcut']) && $variables['title']) {
    // Add a wrapper div using the title_prefix and title_suffix render elements.
    $variables['title_prefix']['shortcut_wrapper'] = array(
      '#markup' => '<div class="shortcut-wrapper clearfix">',
      '#weight' => 100,
    );
    $variables['title_suffix']['shortcut_wrapper'] = array(
      '#markup' => '</div>',
      '#weight' => -99,
    );
    // Make sure the shortcut link is the first item in title_suffix.
    $variables['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
  }
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function exchange_preprocess_maintenance_page(&$variables) {
  if (!$variables['db_is_active']) {
    unset($variables['site_name']);
  }

 	// Set body background
 	$settings['style'] = theme_get_setting('style', 'exchange');
	if ($settings['style']['background'] == 'custom') {
		$url = file_create_url($settings['style']['custom_bg']['path']);
		$repeat = $settings['style']['custom_bg']['repeat'];
		$pos = $settings['style']['custom_bg']['y_position'] . ' ' . $settings['style']['custom_bg']['x_position'];
		$variables['attributes_array']['style'] = "background:url({$url}) {$repeat} {$pos};";
	} else {
		$variables['classes_array'][] = $settings['style']['background'];
	}

}

/**
 * Override or insert variables into the maintenance page template.
 */
function exchange_process_maintenance_page(&$variables) {
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
}

/**
 * Override or insert variables into the user picture template.
 */
function exchange_preprocess_user_picture(&$variables) {
  $variables['user_picture'] = '';
  if (variable_get('user_pictures', 0)) {
    $account = $variables['account'];
    if (!empty($account->picture)) {
      if (is_numeric($account->picture)) {
        $account->picture = file_load($account->picture);
      }
      if (!empty($account->picture->uri)) {
        $filepath = $account->picture->uri;
      }
    }
    elseif (variable_get('user_picture_default', '')) {
      $filepath = variable_get('user_picture_default', '');
    }
    if (isset($filepath)) {
      $alt = t("@user's picture", array('@user' => format_username($account)));
      // If the image does not have a valid Drupal scheme (for eg. HTTP),
      // don't load image styles.
      if (module_exists('image') && file_valid_uri($filepath) && $style = variable_get('user_picture_style', '')) {
        $variables['user_picture'] = theme('image_style', array(
					'style_name' => $style, 
					'path' => $filepath, 
					'alt' => $alt, 
					'title' => $alt,
					'attributes' => array(
						'class' => array('img-circle'),
					),
				));
      }
      else {
        $variables['user_picture'] = theme('image', array(
					'path' => $filepath, 
					'alt' => $alt, 
					'title' => $alt,
					'attributes' => array(
						'class' => array('img-circle'),
					),
				));
      }
      if (!empty($account->uid) && user_access('access user profiles')) {
        $attributes = array('attributes' => array('title' => t('View user profile.')), 'html' => TRUE);
        $variables['user_picture'] = l($variables['user_picture'], "user/$account->uid", $attributes);
      }
    }
  }
}

/**
 * Override or insert variables into the node template.
 */
function exchange_preprocess_node(&$variables) {
	$node = $variables['elements']['#node'];

	// Add class to the node title so we can address it in the stylesheet
	$variables['title_attributes_array']['class'][] = 'node-title';
	
	// Unset read more button
	if (isset($variables['content']['links']['node']['#links']['node-readmore'])) {
		unset($variables['content']['links']['node']['#links']['node-readmore']);
	}
	
	// Rewrite $variables['submitted'] for blogs
	if ($variables['type'] == 'article') {
		$variables['author'] = l($node->name, 'user/' . $node->uid);
		$variables['submitted'] = format_date($node->created, 'custom', 'j F Y');

		if (isset($node->comment_count)) {
			$variables['comment_count'] = format_plural($node->comment_count, '1 comment', '@count comments');
		}
	}
	
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
  }
}

/**
 * Override or insert variables into the node template.
 */
function exchange_process_node(&$variables) {
	if ($variables['type'] == 'team_member') {
		// If there is social links, set $social_links TRUE which will be used
		// to determine if there is any in the node--team-member.tpl.php
		$variables['social_links'] = FALSE;
		foreach ($variables['content'] as $key => $item) {
			if (substr($key, 0, 12) == 'field_social') {
				$variables['social_links'] = TRUE;
			}
		}
	}
}

/**
 * Override or insert variables into the field template.
 */
function exchange_preprocess_field(&$variables, $hook) {
  if ($variables['element']['#field_name'] == 'field_testimonial_image') {
    $variables['classes_array'][] = 'img-circle';
  }
}

/**
 * Override or insert variables into the comment template.
 */
function exchange_preprocess_comment(&$variables) {
	$comment = $variables['elements']['#comment'];
	
  // Change date format
	$variables['created'] = format_date($comment->created, 'custom', 'j F Y');
	$variables['changed'] = format_date($comment->changed, 'custom', 'j F Y');
}

/**
 * Override or insert variables into the block template.
 */
function exchange_preprocess_block(&$variables) {
  // In the header region visually hide block titles.
  if ($variables['block']->region == 'header') {
    $variables['title_attributes_array']['class'][] = 'element-invisible';
  }
}

/**
 * Implements hook_preprocess().
 */
function exchange_preprocess_twitter_block_tweet(&$variables) {
	$variables['created'] = format_date(strtotime($variables['tweet']->created_at), 'custom', 'j F Y');
  $variables['text'] = twitter_block_linkify($variables['tweet']->text);
}

/**
 * Returns HTML for a sort icon.
 */
function exchange_tablesort_indicator($variables) {
  if ($variables['style'] == "asc") {
    return theme('image', array(
			'path' => drupal_get_path('theme', 'exchange') . '/images/arrow-asc.png', 
			'width' => 11, 
			'height' => 6, 
			'alt' => t('sort ascending'), 
			'title' => t('sort ascending'))
		);
  }
  else {
    return theme('image', array(
			'path' => drupal_get_path('theme', 'exchange') . '/images/arrow-desc.png', 
			'width' => 11, 
			'height' => 6, 
			'alt' => t('sort descending'), 
			'title' => t('sort descending'))
		);
  }
}
