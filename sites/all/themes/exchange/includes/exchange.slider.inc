<?php

// Uncollapsed fieldsets
$uncollapsed = array();
if (isset($_GET['uncollapsed'])) {
	$uncollapsed = explode(',', $_GET['uncollapsed']);
}

// Define options for parameter fields
$options['easing'] = array(
	'linear' => t('linear'), 'swing' => t('swing'),
	'easeInQuad' => t('easeInQuad'), 'easeOutQuad' => t('easeOutQuad'),
	'easeInOutQuad' => t('easeInOutQuad'), 'easeInCubic' => t('easeInCubic'),
	'easeOutCubic' => t('easeOutCubic'), 'easeInOutCubic' => t('easeInOutCubic'),
	'easeInQuart' => t('easeInQuart'), 'easeOutQuart' => t('easeOutQuart'),
	'easeInOutQuart' => t('easeInOutQuart'), 'easeInQuint' => t('easeInQuint'),
	'easeOutQuint' => t('easeOutQuint'), 'easeInOutQuint' => t('easeInOutQuint'),
	'easeInSine' => t('easeInSine'), 'easeOutSine' => t('easeOutSine'),
	'easeInOutSine' => t('easeInOutSine'), 'easeInExpo' => t('easeInExpo'),
	'easeOutExpo' => t('easeOutExpo'), 'easeInOutExpo' => t('easeInOutExpo'),
	'easeInCirc' => t('easeInCirc'), 'easeOutCirc' => t('easeOutCirc'),
	'easeInOutCirc' => t('easeInOutCirc'), 'easeInElastic' => t('easeInElastic'),
	'easeOutElastic' => t('easeOutElastic'), 'easeInOutElastic' => t('easeInOutElastic'),
	'easeInBack' => t('easeInBack'), 'easeOutBack' => t('easeOutBack'),
	'easeInOutBack' => t('easeInOutBack'), 'easeInBounce' => t('easeInBounce'),
	'easeOutBounce' => t('easeOutBounce'), 'easeInOutBounce' => t('easeInOutBounce'),
);

$options['direction'] = array(
	'top' => t('top'),
	'right' => t('right'),
	'bottom' => t('bottom'),
	'left' => t('left'),
);

for ($i = 0; $i <= 10; $i++) {
	$options['weight'][$i] = $i;
}

$form['slider'] = array(
	'#type' => 'fieldset',
	'#title' => t('LayerSlider 3'),
	'#collapsible' => TRUE,
	'#collapsed' => TRUE,
	'#group' => 'settings',
	'#tree' => TRUE,
);

$form['slider']['layers'] = array(
	'#type' => 'vertical_tabs',
	'#prefix' => '<div id="layers">',
	'#suffix' => '</div>',
);

if (isset($settings['slider']['layers'])) {
	foreach ($settings['slider']['layers'] as $lid => $layer) {
		$form['slider']['layers'][$lid] = array(
			'#type' => 'fieldset',
			'#title' => isset($layer['title']) ? $layer['title'] : t('Layer #@number', array('@number' => $lid + 1)),
			'#collapsible' => TRUE,
			'#group' => 'layers',
			'#attributes' => array(
				'class' => array('layer-fieldset'),
			),
		);
		
		$form['slider']['layers'][$lid]['weight'] = array(
			'#type' => 'weight',
			'#title' => t('Weight'),
			'#default_value' => $lid,
			'#attributes' => array(
				'class' => array('weight-select'),
			),
		);
		
		$tables['layer']['rows'][] = array(
			'title' => array('#title' => t('Title'), '#type' => 'textfield', '#default_value' => 'Layer #' . ($lid + 1)),
			'slidedirection' => array('#title' => t('Slide direction'), '#type' => 'select', '#options' => $options['direction'], '#default_value' => 'right'),
			'slidedelay' => array('#title' => t('Slide delay'), '#type' => 'textfield', '#default_value' => 4000),
			'durationin' => array('#title' => t('Duration in'), '#type' => 'textfield', '#default_value' => 1000),
			'durationout' => array('#title' => t('Duration out'), '#type' => 'textfield', '#default_value' => 1000),
			'easingin' => array(
        '#title' => t('Easing in'), 
        '#type' => 'select', 
        '#options' => $options['easing'], 
        '#default_value' => 'easeInOutQuint',
      ),
			'easingout' => array(
        '#title' => t('Easing out'), 
        '#type' => 'select', 
        '#options' => $options['easing'], 
        '#default_value' => 'easeInOutQuint',
      ),
			'delayin' => array('#title' => t('Delay in'), '#type' => 'textfield', '#default_value' => 0),
			'delayout' => array('#title' => t('Delay out'), '#type' => 'textfield', '#default_value' => 0),
		);
		
		// WYSIWYG area for sublayers
		$form['slider']['layers'][$lid]['area'] = array(
			'#type' => 'container',
			'#attributes' => array(
				'class' => array('area'),
				'style' => "position:relative;height:{$settings['slider']['config']['height']}px;width:{$settings['slider']['config']['width']}px;",
			),
      '#suffix' => t('Tip: You can move a sublayer with arrow keys. Select it by clicking on it and then holding shift while pressing arrow keys.'),
		);

		// Set global background
		if (isset($settings['slider']['config']['background']) && !empty($settings['slider']['config']['background'])) {
      // Get image info and set left and top margins
      $info = image_get_info($settings['slider']['config']['background']);
      $margin_left = - $info['width'] / 2;
      $margin_top = - $info['height'] / 2;
			$form['slider']['layers'][$lid]['area']['background']['global'] = array(
				'#markup' => theme_image(array(
					'path' => file_create_url($settings['slider']['config']['background']), 
					'attributes' => array(
						'class' => array('background'),
            'style' => "position:absolute;left:50%;top:50%;margin-top:{$margin_top}px;margin-left:{$margin_left}px;",
					),
				)),
			);
		}
		
		// Set layer specific background
		if (isset($layer['background']) && !empty($layer['background'])) {
      // Get image info and set left and top margins
      $info = image_get_info($layer['background']);
      $margin_left = - $info['width'] / 2;
      $margin_top = - $info['height'] / 2;
			$form['slider']['layers'][$lid]['area']['background']['layer'] = array(
				'#markup' => theme_image(array(
					'path' => file_create_url($layer['background']), 
					'attributes' => array(
						'class' => array('background'),
            'style' => "position:absolute;left:50%;top:50%;margin-top:{$margin_top}px;margin-left:{$margin_left}px;",
					),
				)),
			);
		}
		
		// Go through sublayers and add them to the slide area for WYSIWYG editing.
		// Also create rows for the parameter table.
		if (isset($layer['sublayers'])) {
      $form['slider']['layers'][$lid]['sublayers'] = array(
        '#type' => 'container',
        '#prefix' => '<h3>' . t('Sublayers') . '</h3>',
      );
      
			foreach ($layer['sublayers'] as $sid => $sublayer) {
				$id_class = "element-{$lid}-{$sid}";
				
				$collapsed = in_array("edit-slider-layers-{$lid}-sublayers-{$sid}", $uncollapsed) ? FALSE : TRUE;
				$form['slider']['layers'][$lid]['sublayers'][$sid] = array(
					'#type' => 'fieldset',
					'#title' => $sublayer['title'],
					'#collapsible' => TRUE,
					'#collapsed' => $collapsed,
					'#attributes' => array(
						'class' => array($id_class),
					),
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['title'] = array(
					'#type' => 'textfield',
					'#title' => t('Title'),
					'#default_value' => $sublayer['title'],
				);
				
				$form['slider']['layers'][$lid]['sublayers'][$sid]['x'] = array(
					'#type' => 'hidden',
					'#default_value' => $sublayer['x'],
					'#attributes' => array(
						'class' => array('x-pos', $id_class),
					),
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['y'] = array(
					'#type' => 'hidden',
					'#default_value' => $sublayer['y'],
					'#attributes' => array(
						'class' => array('y-pos', $id_class),
					),
				);
				
				$headers = implode('', array(
					'<th>' . t('Slide direction') . '</th>',
					'<th>' . t('Slide out direction') . '</th>',
					'<th>' . t('Duration in') . '</th>',
					'<th>' . t('Duration out') . '</th>',
					'<th>' . t('Easing in') . '</th>',
					'<th>' . t('Easing out') . '</th>',
					'<th>' . t('Delay in') . '</th>',
					'<th>' . t('Delay out') . '</th>',
				));
				$form['slider']['layers'][$lid]['sublayers'][$sid]['properties'] = array(
					'#type' => 'container',
					'#prefix' => "<table class='parameters'><tr>{$headers}</tr><tr>",
					'#suffix' => '</tr></table>',
				);
				
				$options['direction'] = array_merge(array('_none' => '- inherit -'), $options['direction']);
        $options['direction'] = array_merge($options['direction'], array('fade' => 'fade'));
				$form['slider']['layers'][$lid]['sublayers'][$sid]['properties']['slidedirection'] = array(
					'#type' => 'select', 
					'#options' => $options['direction'],
					'#default_value' => isset($sublayer['properties']['slidedirection']) ? $sublayer['properties']['slidedirection'] : '',
					'#prefix' => '<td>',
					'#suffix' => '</td>',
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['properties']['slideoutdirection'] = array(
					'#type' => 'select', 
					'#options' => $options['direction'],
					'#default_value' => isset($sublayer['properties']['slideoutdirection']) ? $sublayer['properties']['slideoutdirection'] : '',
					'#prefix' => '<td>',
					'#suffix' => '</td>',
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['properties']['durationin'] = array(
					'#type' => 'textfield',
					'#default_value' => isset($sublayer['properties']['durationin']) ? $sublayer['properties']['durationin'] : '',
					'#prefix' => '<td>',
					'#suffix' => '</td>',
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['properties']['durationout'] = array(
					'#type' => 'textfield',
					'#default_value' => isset($sublayer['properties']['durationout']) ? $sublayer['properties']['durationout'] : '',
					'#prefix' => '<td>',
					'#suffix' => '</td>',
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['properties']['easingin'] = array(
					'#type' => 'select', 
					'#options' => $options['easing'],
					'#default_value' => isset($sublayer['properties']['easingin']) ? $sublayer['properties']['easingin'] : '',
          '#empty_option' => t('- inherit -'),
					'#prefix' => '<td>',
					'#suffix' => '</td>',
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['properties']['easingout'] = array(
					'#type' => 'select', 
					'#options' => $options['easing'],
					'#default_value' => isset($sublayer['properties']['easingout']) ? $sublayer['properties']['easingout'] : '',
          '#empty_option' => t('- inherit -'),
					'#prefix' => '<td>',
					'#suffix' => '</td>',
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['properties']['delayin'] = array(
					'#type' => 'textfield',
					'#default_value' => isset($sublayer['properties']['delayin']) ? $sublayer['properties']['delayin'] : '',
					'#prefix' => '<td>',
					'#suffix' => '</td>',
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['properties']['delayout'] = array(
					'#type' => 'textfield',
					'#default_value' => isset($sublayer['properties']['delayout']) ? $sublayer['properties']['delayout'] : '',
					'#prefix' => '<td>',
					'#suffix' => '</td>',
				);
        unset($options['direction']['_none']);
        unset($options['direction']['fade']);
				
				$form['slider']['layers'][$lid]['sublayers'][$sid]['weight'] = array(
					'#title' => t('Weight'), 
					'#type' => 'select', 
					'#options' => $options['weight'],
					'#default_value' => isset($sublayer['weight']) ? $sublayer['weight'] : '',
				);
				
				$form['slider']['layers'][$lid]['sublayers'][$sid]['type'] = array(
					'#type' => 'select',
					'#title' => t('Type'),
          '#default_value' => isset($sublayer['type']) ? $sublayer['type'] : 'image',
					'#options' => array(
						'heading' => t('Heading'),
						'image' => t('Image'),
						'button' => t('Button'),
						'html' => t('HTML'),
						'video' => t('Video'),
					),
					'#attributes' => array(
						'class' => array('type'),
					),
				);
				
				// Heading type
				$form['slider']['layers'][$lid]['sublayers'][$sid]['heading'] = array(
					'#type' => 'container',
					'#states' => array(
						'visible' => array(
							"fieldset.{$id_class} select.type" => array('value' => 'heading'),
						),
					),
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['heading']['level'] = array(
					'#type' => 'select',
					'#title' => t('Level'),
					'#default_value' => isset($sublayer['heading']['level']) ? $sublayer['heading']['level'] : '',
					'#options' => array(
						'h1' => t('Heading 1'),
						'h2' => t('Heading 2'),
						'h3' => t('Heading 3'),
						'h4' => t('Heading 4'),
						'h5' => t('Heading 5'),
						'h6' => t('Heading 6'),
					),
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['heading']['heading'] = array(
					'#type' => 'textfield',
					'#title' => t('Heading'),
					'#default_value' => isset($sublayer['heading']['heading']) ? $sublayer['heading']['heading'] : '',
				);
				
				// Image type
				$form['slider']['layers'][$lid]['sublayers'][$sid]['image'] = array(
					'#type' => 'container',
					'#states' => array(
						'visible' => array(
							"fieldset.{$id_class} select.type" => array('value' => 'image'),
						),
					),
				);
				if (isset($sublayer['image']['path'])) {
					$url = file_create_url($sublayer['image']['path']);
					
					$form['slider']['layers'][$lid]['sublayers'][$sid]['image']['path'] = array(
						'#type' => 'hidden',
						'#default_value' => $sublayer['image']['path'],
					);
					$form['slider']['layers'][$lid]['sublayers'][$sid]['image']['preview'] = array(
						'#markup' => theme_image(array(
							'path' => $url, 
							'attributes' => array(
								'class' => array('sublayer-preview')
							)
						)),
					);
				}
				$form['slider']['layers'][$lid]['sublayers'][$sid]['image']['upload'] = array(
					'#type' => 'container',
					'#attributes' => array('class' => array('upload')),
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['image']['upload']['file'] = array(
					'#type' => 'file',
					'#name' => "files[sublayers-{$lid}-{$sid}]",
					'#title' => t('Upload'),
				);
				$form['slider']['layers'][$lid]['sublayers'][$sid]['image']['upload']['submit'] = array(
					'#type' => 'submit',
					'#value' => t('Upload'),
					'#name' => "layer-{$lid}-sublayer-{$sid}-bg-upload",
				);
        
				// Button type
				$form['slider']['layers'][$lid]['sublayers'][$sid]['button'] = array(
					'#type' => 'container',
					'#states' => array(
						'visible' => array(
							"fieldset.{$id_class} select.type" => array('value' => 'button'),
						),
					),
				);
        $form['slider']['layers'][$lid]['sublayers'][$sid]['button']['text'] = array(
          '#type' => 'textfield',
          '#title' => t('Button text'),
          '#default_value' => isset($sublayer['button']['text']) ? $sublayer['button']['text'] : '',
        );
        $form['slider']['layers'][$lid]['sublayers'][$sid]['button']['url'] = array(
          '#type' => 'textfield',
          '#title' => t('URL'),
          '#default_value' => isset($sublayer['button']['url']) ? $sublayer['button']['url'] : '',
        );
				$form['slider']['layers'][$lid]['sublayers'][$sid]['button']['color'] = array(
					'#type' => 'select',
					'#title' => t('Color'),
					'#default_value' => isset($sublayer['button']['color']) ? $sublayer['button']['color'] : '',
					'#options' => array(
						'white' => t('White'),
						'grey' => t('Grey'),
						'pink' => t('Pink'),
						'orange' => t('Orange'),
						'green' => t('Green'),
						'blue' => t('Blue'),
						'darkblue' => t('Dark Blue'),
						'purple' => t('Purple'),
						'teal' => t('Teal'),
						'black' => t('Black')
					)
				);
        
				// HTML type
				$form['slider']['layers'][$lid]['sublayers'][$sid]['html'] = array(
					'#type' => 'container',
					'#states' => array(
						'visible' => array(
							"fieldset.{$id_class} select.type" => array('value' => 'html'),
						),
					),
				);
        $form['slider']['layers'][$lid]['sublayers'][$sid]['html']['content'] = array(
          '#type' => 'textarea',
          '#title' => t('HTML'),
          '#default_value' => isset($sublayer['html']['content']) ? $sublayer['html']['content'] : '',
        );
        
				// Video type
				$form['slider']['layers'][$lid]['sublayers'][$sid]['video'] = array(
					'#type' => 'container',
					'#states' => array(
						'visible' => array(
							"fieldset.{$id_class} select.type" => array('value' => 'video'),
						),
					),
				);
        $form['slider']['layers'][$lid]['sublayers'][$sid]['video']['id'] = array(
          '#type' => 'textfield',
          '#title' => t('Video ID'),
          '#default_value' => isset($sublayer['video']['id']) ? $sublayer['video']['id'] : '',
					'#description' => t('Only Youtube and Vimeo videos are supported. Video ID is found in the URL.
						Youtube: http://www.youtube.com/watch?v=<strong>624HfkMty_8</strong> Vimeo: http://vimeo.com/<strong>22439234</strong>'),
        );
        $form['slider']['layers'][$lid]['sublayers'][$sid]['video']['width'] = array(
          '#type' => 'textfield',
          '#title' => t('Width'),
          '#default_value' => isset($sublayer['video']['width']) ? $sublayer['video']['width'] : '560',
          '#size' => 4,
        );
        $form['slider']['layers'][$lid]['sublayers'][$sid]['video']['height'] = array(
          '#type' => 'textfield',
          '#title' => t('Height'),
          '#default_value' => isset($sublayer['video']['height']) ? $sublayer['video']['height'] : '315',
          '#size' => 4,
        );

        // Add sublayer element to the WYSIWYG area
        $style = "position:absolute;left:{$sublayer['x']}px;top:{$sublayer['y']}px;z-index:{$sublayer['weight']}";
        $markup = "<div style={$style} id='{$id_class}' class='sublayer'>";
				
				if (isset($sublayer['type'])) {
					switch ($sublayer['type']) {
						case 'heading':
							$markup .= "<{$sublayer['heading']['level']}>{$sublayer['heading']['heading']}</{$sublayer['heading']['level']}>";
							break;
						case 'image':
							if (isset($sublayer['image']['path'])) {
								$info = image_get_info($sublayer['image']['path']);
								$margin_top = - $info['height'] / 2;
								$margin_left = - $info['width'] / 2;

								$path = file_create_url($sublayer['image']['path']);
								$markup .= "<img src='{$path}' />"; 
							}
							break;
						case 'button':
							$markup .= "<a class='button' href='{$sublayer['button']['url']}'>{$sublayer['button']['text']}</a>";
							break;
						case 'html':
							$markup .= $sublayer['html']['content'];
							break;
						case 'video':
							if (!empty($sublayer['video']['id'])) {
								// Vimeo IDs are numerical while Youtube's are not
								if (is_numeric($sublayer['video']['id'])) {
									$markup .= "<iframe 
										src='http://player.vimeo.com/video/{$sublayer['video']['id']}?badge=0&amp;color=c9ff23'
										width='{$sublayer['video']['width']}' 
										height='{$sublayer['video']['height']}' 
										frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen>
									</iframe>";
								} else {
									$markup .= "<iframe 
										width='{$sublayer['video']['width']}' 
										height='{$sublayer['video']['height']}' 
										src='http://www.youtube.com/embed/{$sublayer['video']['id']}?wmode=opaque' 
										frameborder='0' allowfullscreen>
									</iframe>";
								}
							}
							break;
					}
				}
        // Youtube-embed sun muut ei toimi jos on POST-parametreja
        // kts. http://stackoverflow.com/questions/1547884/refused-to-execute-a-javascript-script-source-code-of-script-found-within-reque
        $markup .= '<div class="sublayer-overlay"></div></div>';
        $form['slider']['layers'][$lid]['area'][$sid] = array(
          '#markup' => $markup,
        );
				
        $form['slider']['layers'][$lid]['sublayers'][$sid]['action_container'] = array(
          '#type' => 'container',
          '#prefix' => '<hr style="margin:0 0 20px;" />',
          '#attributes' => array(
            'class' => array('sublayer-action-container')
          ),
        );
        $form['slider']['layers'][$lid]['sublayers'][$sid]['action_container']['update'] = array(
          '#type' => 'submit',
          '#name' => "update-{$lid}-{$sid}",
          '#default_value' => t('Update'),
        );
				$form['slider']['layers'][$lid]['sublayers'][$sid]['action_container']['delete'] = array(
					'#type' => 'submit',
					'#name' => "delete-{$lid}-{$sid}",
					'#default_value' => t('Delete'),
					'#submit' => array('exchange_sublayer_delete'),
				);
			}
		}
		
		$form['slider']['layers'][$lid]['create_sublayer'] = array(
			'#type' => 'submit',
			'#name' => "create-sublayer-{$lid}}",
			'#default_value' => t('Create sublayer'),
			'#submit' => array('exchange_sublayer_create'),
		);
		
		// Create property tables
		foreach ($tables as $table_name => &$table) {
			$table_title = $table_name == 'layer' ? t('Layer properties') : t('Sublayer properties');
			
			$table['#prefix'] = '<h3>' . $table_title . '</h3><table class="parameters">';
			$table['#suffix'] = '</table>';
			
			$table['headers']['#weight'] = -1;
			$table['headers']['#prefix'] = '<tr>';
			$table['headers']['#suffix'] = '</tr>';
			
			foreach ($table['rows'] as $row_name => &$row) {
				$class = '';
				if ($table_name == 'sublayer') {
					$class = " class='element-{$lid}-{$row_name}'";
				}
				$row['#prefix'] = "<tr{$class}>";
				$row['#suffix'] = '</tr>';
				
				foreach ($row as $cell_name => &$cell) {
          if (is_array($cell)) {
            $cell['#prefix'] = "<td class='{$cell_name}'>";
            $cell['#suffix'] = '</td>';
            
            $table['headers'][$cell_name]['#markup'] = $cell['#title'];
            $table['headers'][$cell_name]['#prefix'] = '<th>';
            $table['headers'][$cell_name]['#suffix'] = '</th>';
            
            unset($tables[$table_name]['rows'][$row_name][$cell_name]['#title']);
            
            // Set saved values as defaults
            $defaults = $table_name == 'layer' ? $layer : $layer['sublayers'][$row_name];
            if (isset($defaults[$cell_name])) {
              $cell['#default_value'] = $defaults[$cell_name];
            }
          }
				}
			}
		}
		$form['slider']['layers'][$lid]['tables'] = $tables;
		
		if (!isset($layer['background']) || empty($layer['background'])) {
      $form['slider']['layers'][$lid]['background_upload'] = array(
        '#type' => 'container',
        '#attributes' => array('class' => array('upload')),
      );
      $form['slider']['layers'][$lid]['background_upload']['file'] = array(
        '#type' => 'file',
        '#name' => "files[backgrounds-{$lid}]",
        '#title' => t('Upload background'),
      );
      $form['slider']['layers'][$lid]['background_upload']['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Upload'),
				'#name' => "layer-{$lid}-bg-upload",
      );
		} else {
      $form['slider']['layers'][$lid]['background_upload']['remove'] = array(
        '#type' => 'submit',
        '#value' => t('Delete background'),
        '#submit' => array('exchange_background_delete'),
      );
			$form['slider']['layers'][$lid]['background'] = array(
				'#type' => 'hidden',
				'#value' => $layer['background'],
			);
    }
    
		$form['slider']['layers'][$lid]['delete'] = array(
			'#type' => 'submit',
			'#name' => "delete-{$lid}",
			'#default_value' => t('Delete layer'),
			'#submit' => array('exchange_layer_crud'),
		);
    
    unset($tables);
	}
}

$form['slider']['add'] = array(
	'#type' => 'submit',
	'#value' => t('Add layer'),
	'#name' => 'create',
	'#submit' => array('exchange_layer_crud'),
);

$collapsed = in_array('edit-slider-config', $uncollapsed) ? FALSE : TRUE;
$form['slider']['config'] = array(
	'#type' => 'fieldset',
	'#title' => t('Global settings'),
  '#collapsible' => TRUE,
  '#collapsed' => $collapsed,
);

$form['slider']['config']['status'] = array(
	'#type' => 'checkbox',
	'#title' => t('Use LayerSlider 3'),
	'#default_value' => $settings['slider']['config']['status'],
);

// Global background
if (!isset($settings['slider']['config']['background']) || empty($settings['slider']['config']['background'])) {
	$form['slider']['config']['background_upload'] = array(
		'#type' => 'container',
		'#attributes' => array('class' => array('upload')),
	);
	$form['slider']['config']['background_upload']['file'] = array(
		'#type' => 'file',
		'#name' => "files[backgrounds-global]",
		'#title' => t('Upload background'),
		'#description' => t('Apply background to all layers.'),
	);
	$form['slider']['config']['background_upload']['submit'] = array(
		'#type' => 'submit',
		'#value' => t('Upload'),
		'#name' => 'global-bg-upload',
	);
} else {
	$form['slider']['config']['background_upload']['remove'] = array(
		'#type' => 'submit',
		'#value' => t('Delete global background'),
		'#submit' => array('exchange_global_background_delete'),
	);
	$form['slider']['config']['background'] = array(
		'#type' => 'hidden',
		'#value' => $settings['slider']['config']['background'],
	);
}

/*
$form['slider']['config']['controller'] = array(
	'#type' => 'radios',
	'#title' => t('Controller'),
	'#default_value' => $settings['slider']['config']['controller'],
  '#options' => array(
    'none' => t('None'),
    'title' => t('Title'),
  ),
);
*/
$form['slider']['config']['autostart'] = array(
	'#type' => 'checkbox',
	'#title' => t('Autostart'),
	'#default_value' => $settings['slider']['config']['autostart'],
);
$form['slider']['config']['pauseonhover'] = array(
	'#type' => 'checkbox',
	'#title' => t('Pause on hover'),
	'#default_value' => $settings['slider']['config']['pauseonhover'],
);
$form['slider']['config']['autoplayvideos'] = array(
	'#type' => 'checkbox',
	'#title' => t('Automatically play videos'),
	'#default_value' => $settings['slider']['config']['autoplayvideos'],
);
$form['slider']['config']['width'] = array(
	'#type' => 'textfield',
	'#title' => t('Width'),
	'#field_suffix' => t('px'),
	'#size' => 10,
	'#required' => TRUE,
	'#default_value' => isset($settings['slider']['config']['width']) ? $settings['slider']['config']['width'] : 978,
);
$form['slider']['config']['height'] = array(
	'#type' => 'textfield',
	'#title' => t('Height'),
	'#field_suffix' => t('px'),
	'#size' => 10,
	'#required' => TRUE,
	'#default_value' => isset($settings['slider']['config']['height']) ? $settings['slider']['config']['height'] : 396,
);

// Used to store information about collapsed and uncollapsed fieldsets to improve
// usability.
$form['slider']['uncollapsed'] = array(
	'#type' => 'hidden',
	'#attributes' => array(
		'id' => 'slider-uncollapsed',
	),
);
