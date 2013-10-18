<?php
$info = array(
  // Available colors and color labels used in theme.
  'fields' => array(
    'base' => t('Base'),
    'background' => t('Background'),
    'headertop' => t('Header Top'),
    'headerbottom' => t('Header Bottom'),
    'headerbordertop' => t('Header Border Top'),
    'headerborderbottom' => t('Header Border Bottom'),
    'sitename' => t('Site Name'),
    'navtop' => t('Navigation Top'),
    'navbottom' => t('Navigation Bottom'),
    'navbordertop' => t('Navigation Border Top'),
    'navborderbottom' => t('Navigation Border Bottom'),
    'navborderleft' => t('Navigation Border Left'),
    'navborderright' => t('Navigation Border Right'),
    'navcolor' => t('Navigation Text Color'),
    'slider' => t('Slider'),
    'footer' => t('Footer'),
    'footercolor' => t('Footer Text Color'),
    'link' => t('Link'),
    'text' => t('Text'),
  ),
  // Pre-defined color schemes.
  'schemes' => array(
    'default' => array(
      'title' => t('Dark Grey (default)'),
      'colors' => array(
        'base' => '#e34d25',
        'background' => '#e2e4e6',
        'headertop' => '#414648',
        'headerbottom' => '#333739',
        'headerbordertop' => '#545b5d',
        'headerborderbottom' => '#232627',
        'sitename' => '#f7f7f7',
        'navtop' => '#3f4345',
        'navbottom' => '#373b3d',
        'navbordertop' => '#4e5356',
        'navborderbottom' => '#26292a',
        'navborderleft' => '#4c5154',
        'navborderright' => '#292c2d',
        'navcolor' => '#d4d4d4',
        'slider' => '#4d3c4c',
        'footer' => '#3b3f41',
        'footercolor' => '#f5f5f5',
        'link' => '#e34d26',
        'text' => '#525252',
      ),
    ),
    'yellow' => array(
      'title' => t('Yellow'),
      'colors' => array(
        'base' => '#eb384d',
        'background' => '#f1edea',
        'headertop' => '#fbcd47',
        'headerbottom' => '#f9c038',
        'headerbordertop' => '#fcdc5c',
        'headerborderbottom' => '#a52c3c',
        'sitename' => '#f7f7f7',
        'navtop' => '#ed4057',
        'navbottom' => '#eb384d',
        'navbordertop' => '#f1506c',
        'navborderbottom' => '#a42735',
        'navborderleft' => '#f14e6a',
        'navborderright' => '#a52a39',
        'navcolor' => '#ffffff',
        'slider' => '#fac53e',
        'footer' => '#f9c038',
        'footercolor' => '#ffffff',
        'link' => '#eb384d',
        'text' => '#525252',
      ),
    ),
    'blue' => array(
      'title' => t('Blue'),
      'colors' => array(
        'base' => '#0d6ea9',
        'background' => '#e9ebf0',
        'headertop' => '#ffffff',
        'headerbottom' => '#ececec',
        'headerbordertop' => '#ffffff',
        'headerborderbottom' => '#04547b',
        'sitename' => '#143768',
        'navtop' => '#0679b1',
        'navbottom' => '#1d4f94',
        'navbordertop' => '#0797c4',
        'navborderbottom' => '#143767',
        'navborderleft' => '#1682bd',
        'navborderright' => '#0b4671',
        'navcolor' => '#ffffff',
        'slider' => '#ffffff',
        'footer' => '#ececec',
        'footercolor' => '#525252',
        'link' => '#0d6ea9',
        'text' => '#525252',
      ),
    ),
    'green' => array(
      'title' => t('Green'),
      'colors' => array(
        'base' => '#58b50e',
        'background' => '#e9f0eb',
        'headertop' => '#ffffff',
        'headerbottom' => '#ececec',
        'headerbordertop' => '#ffffff',
        'headerborderbottom' => '#4d8604',
        'sitename' => '#146814',
        'navtop' => '#6fc007',
        'navbottom' => '#21951c',
        'navbordertop' => '#8acf08',
        'navborderbottom' => '#176813',
        'navborderleft' => '#5dc316',
        'navborderright' => '#32760b',
        'navcolor' => '#ffffff',
        'slider' => '#ffffff',
        'footer' => '#ececec',
        'footercolor' => '#525252',
        'link' => '#58b50e',
        'text' => '#525252',
      ),
    ),
  ),

  // Images to copy over.
  'copy' => array(
		'logo.png',
  ),
	
  // Gradient definitions.
  'gradients' => array(
		array(
			'dimension' => array(0, 0, 1, 96),
			'direction' => 'vertical',
			'colors' => array('headertop', 'headerbottom'),
		),
		array(
			'dimension' => array(1, 0, 1, 34),
			'direction' => 'vertical',
			'colors' => array('navtop', 'navbottom'),
		),
	),

  // CSS files (excluding @import) to rewrite with new color scheme.
  'css' => array(
    'style.css',
  ),

  // Coordinates of all the theme slices (x, y, width, height)
  // with their filename as used in the stylesheet.
  'slices' => array(
		'images/header-bg.png' => array(0, 0, 1, 96),
		'images/main-menu.png' => array(1, 0, 1, 34),
		'images/accordion.png' => array(0, 100, 19, 19),
		'images/accordion-collapsed.png' => array(19, 100, 19, 19),
	),
	
  // Color areas to fill (x, y, width, height).
	'fill' => array(
    'base' => array(0, 100, 38, 19),
	),
	
	'blend_target' => '#ffffff',

  // Preview files.
	'preview_js' => 'color/preview.js',
  'preview_css' => 'color/preview.css',
  'preview_html' => 'color/preview.html',

  // Base file for image generation.
  'base_image' => 'color/base.png',
);
