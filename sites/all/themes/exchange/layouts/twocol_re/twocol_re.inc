<?php
// Plugin definition
$plugin = array(
  'title' => t('Two column responsive'),
  'category' => t('Columns: 2'),
  'icon' => 'twocol_re.png',
  'theme' => 'twocol_re',
  'css' => 'twocol_re.css',
  'regions' => array(
    'left' => t('Left side'),
    'right' => t('Right side')
  ),
);
