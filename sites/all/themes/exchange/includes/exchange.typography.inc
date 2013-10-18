<?php
  $form['typography'] = array(
    '#type' => 'fieldset',
    '#description' => t('See <a href="@link" target="_blank">Google Web Fonts</a>.', array('@link' => 'http://www.google.com/webfonts')),
    '#title' => t('Fonts'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#group' => 'settings',
    '#tree' => TRUE,
    '#weight' => -3,
  );
  
  // Set typography fieldsets for elements
  $form['typography']['elements'] = array(
    '#type' => 'container',
  );
  
  $elements = array(
    'body' => t('General'), 
    'id_site-name' => t('Site name'),
    'id_site-slogan' => t('Site slogan'),
    'h4' => t('H4'),
    'h5' => t('H5'), 
    'h6' => t('H6'),
  );

  foreach ($elements as $element => $label) {
    // Wrap properties into a fieldset
    $form['typography']['elements'][$element] = array(
      '#type' => 'fieldset',
      '#title' => $label,
      '#attributes' => array(
        'class' => array('container-inline'),
        // We will use ID for Drupal #states functionality
        'id' => array("fieldset-{$element}"),
      ),
    );
    
    // User can set font size in either 'em' or 'px' unit.
    // Therefore, we will need two textfields. Only one will be visible
    // at a time.
    $units = array('px', 'em');
    
		// Go through units and define form fields for them
		foreach ($units as $unit) {
			$form['typography']['elements'][$element]['size'][$unit] = array(
				'#type' => 'textfield',
				'#default_value' => $settings['typography']['elements'][$element]['size'][$unit],
				'#attributes' => array('class' => array($unit)),
				'#states' => array(
					'visible' => array(
						"#fieldset-{$element} select.unit" => array('value' => $unit),
					),
				),
				'#size' => 4,
			);
		}

		$form['typography']['elements'][$element]['unit'] = array(
			'#type' => 'select',
			'#options' => array(
				'px' => t('px'),
				'em' => t('em'),
			),
			'#default_value' => $settings['typography']['elements'][$element]['unit'],
			'#attributes' => array('class' => array('unit')),
		);
		
		// Define basic web-safe font stacks
		$font_stacks = array(
			'Sans-serif' => array(
				'Arial, "Helvetica Neue", Helvetica, sans-serif' => 'Arial',
				'"Arial Black", "Arial Bold", Gadget, sans-serif' => 'Arial Black',
				'Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif' => 'Calibri',
				'Geneva, Tahoma, Verdana, sans-serif' => 'Geneva',
				'Impact, Haettenschweiler, "Franklin Gothic Bold", Charcoal, "Helvetica Inserat", "Bitstream Vera Sans Bold", "Arial Black", sans serif' => 'Impact',
				'"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif' => 'Lucida Grande',
				'Tahoma, Verdana, Segoe, sans-serif' => 'Tahoma',
				'"Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif' => 'Trebuchet MS',
				'Verdana, Geneva, sans-serif' => 'Verdana',
			),
			'Serif' => array(
				'Cambria, Georgia, serif' => 'Cambria',
				'Georgia, Times, "Times New Roman", serif' => 'Georgia',
				'Palatino, "Palatino Linotype", "Palatino LT STD", "Book Antiqua", Georgia, serif' => 'Palatino',
				'TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif' => 'Times New Roman',
			),
		);
		
		// Form field for selecting font
		$form['typography']['elements'][$element]['font'] = array(
			'#type' => 'select',
			'#options' => $font_stacks,
			'#default_value' => $settings['typography']['elements'][$element]['font'],
			'#attributes' => array('class' => array('font')),
		);
		
		// Add Google Web Fonts to font options
    $web_fonts = exchange_load_gwf();

    // Create an array of fonts suitable for select lists
    $fonts = array();
    foreach ($web_fonts->items as $web_font) {
      // Google doesn't provide direct information on whether the font
      // is serif, sans-serif, monospace etc so we will use 'sans-serif'
      // as a fallback font.
      $font_stack = "'{$web_font->family}', sans-serif";
      $fonts[$font_stack] = $web_font->family;
    }

    // Add Web Fonts to font options
    $form['typography']['elements'][$element]['font']['#options']['Google Web Fonts'] = $fonts;
		
		// Form fields for applying bold and italic styles
		$form['typography']['elements'][$element]['bold'] = array(
			'#type' => 'checkbox',
			'#title' => t('<strong>Bold</strong>'),
			'#default_value' => $settings['typography']['elements'][$element]['bold'],
		);
	}
