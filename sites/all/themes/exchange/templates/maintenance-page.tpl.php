<?php
?>
<!DOCTYPE html>
<html>
<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <!--[if lt IE 9]>
    <script src="<?php print $base_path . $directory; ?>/js/html5.js"></script>
  <![endif]-->
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <div id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
  </div>
  <div id="page" class="container">
    <header id="top">
      <div class="row">
        <div id="branding" class="span12">
          <?php if ($logo): ?>
            <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
              <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
            </a>
          <?php endif; ?>

          <?php if ($site_name || $site_slogan): ?>
            <div id="name-and-slogan"<?php if ($hide_site_name && $hide_site_slogan) { print ' class="element-invisible"'; } ?>>
              <?php if ($site_name): ?>
                <?php if ($title): ?>
                  <div id="site-name"<?php if ($hide_site_name) { print ' class="element-invisible"'; } ?>>
                      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
                  </div>
                <?php else: /* Use h1 when the content title is empty */ ?>
                  <h1 id="site-name"<?php if ($hide_site_name) { print ' class="element-invisible"'; } ?>>
                    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
                  </h1>
                <?php endif; ?>
              <?php endif; ?>

              <?php if ($site_slogan): ?>
                <div id="site-slogan"<?php if ($hide_site_slogan) { print ' class="element-invisible"'; } ?>>
                  <?php print $site_slogan; ?>
                </div>
              <?php endif; ?>

            </div> <!-- /#name-and-slogan -->
          <?php endif; ?>
        </div> <!-- /#branding -->
      </div>
    </header>

    <div id="main">
      <div class="row">
        <section id="main-content" class="span12">
          <?php if ($title) : ?>
            <header class="content clearfix">
              <div class="row">
                <div class="span12">
                  <h1 id="page-title">
                    <?php print $title; ?>
                  </h1>
                </div>
              </div>
            </header>
          <?php endif; ?>
          
          <?php if ($messages): ?>
            <div id="messages">
              <?php print $messages; ?>
            </div> <!-- /#messages -->
          <?php endif; ?>
          
          <?php print $content; ?>
        </section>
      </div>
    </div>
  </div>
</body>
</html>