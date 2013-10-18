<?php
$form['style'] = array(
	'#type' => 'fieldset',
	'#title' => t('Style'),
	'#collapsible' => TRUE,
	'#collapsed' => TRUE,
	'#group' => 'settings',
	'#tree' => TRUE,
);

$patterns = array(
	'none' => t('None'),
	'concrete' => t('Concrete (default)'),
	'pattern1' => t('Pattern 1'),
	'pattern2' => t('Pattern 2'),
	'pattern3' => t('Pattern 3'),
	'pattern4' => t('Pattern 4'),
	'pattern5' => t('Pattern 5'),
	'custom' => t('Custom'),
);

foreach ($patterns as $key => $label) {
	$bg_options[$key] = "<div class='pattern' id='{$key}'></div>";
}

$form['style']['background'] = array(
	'#type' => 'radios',
	'#title' => t('Background image'),
	'#options' => $bg_options,
	'#default_value' => $settings['style']['background'],
);

$form['style']['custom_bg'] = array(
	'#type' => 'container',
	'#states' => array(
		'visible' => array(
			':input[name="style[background]"]' => array('value' => 'custom'),
		),
	),
);

if (isset($settings['style']['custom_bg']['path']) && !empty($settings['style']['custom_bg']['path'])) {
	$url = file_create_url($settings['style']['custom_bg']['path']);
	$form['style']['custom_bg']['preview'] = array(
		'#markup' => "<img src='{$url}' style='max-width:200px;max-height:200px' />",
	);
	$form['style']['custom_bg']['path'] = array(
		'#type' => 'hidden',
		'#value' => $settings['style']['custom_bg']['path'],
	);
}

$form['style']['custom_bg']['upload'] = array(
	'#type' => 'container',
	'#attributes' => array('class' => array('upload')),
);
$form['style']['custom_bg']['upload']['file'] = array(
	'#type' => 'file',
	'#name' => "files[custom-background]",
	'#title' => t('Upload background'),
);
$form['style']['custom_bg']['upload']['submit'] = array(
	'#type' => 'submit',
	'#value' => t('Upload'),
	'#name' => "custom-background-upload",
);
$form['style']['custom_bg']['repeat'] = array(
	'#type' => 'radios',
	'#title' => t('Repeat'),
	'#default_value' => $settings['style']['custom_bg']['repeat'],
	'#options' => array(
		'no-repeat' => t('No'),
		'repeat' => t('Repeat'),
		'repeat-x' => t('Repeat horizontally'),
		'repeat-y' => t('Repeat vertically'),
	),
);
$form['style']['custom_bg']['repeat'] = array(
	'#type' => 'radios',
	'#title' => t('Repeat'),
	'#default_value' => $settings['style']['custom_bg']['repeat'],
	'#options' => array(
		'no-repeat' => t('No'),
		'repeat' => t('Repeat'),
		'repeat-x' => t('Repeat horizontally'),
		'repeat-y' => t('Repeat vertically'),
	),
);
$form['style']['custom_bg']['x_position'] = array(
	'#type' => 'radios',
	'#title' => t('Horizontal position'),
	'#default_value' => $settings['style']['custom_bg']['x_position'],
	'#options' => array(
		'left' => t('Left'),
		'center' => t('Center'),
		'right' => t('Right'),
	),
);
$form['style']['custom_bg']['y_position'] = array(
	'#type' => 'radios',
	'#title' => t('Vertical position'),
	'#default_value' => $settings['style']['custom_bg']['y_position'],
	'#options' => array(
		'top' => t('Top'),
		'center' => t('Center'),
		'bottom' => t('Bottom'),
	),
);

$buttons = array(
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
);

foreach ($buttons as $key => $label) {
  $button_options[$key] = "<a class='buttonxe {$key}'>{$label}</a>";
}

$form['style']['button'] = array(
  '#type' => 'radios',
	'#title' => t('Buttons'),
	'#description' => '<p>' . t('Select button style which will be used for submit buttons in forms and links with button class.') . '</p>', 
  '#options' => $button_options,
  '#default_value' => $settings['style']['button'],
);