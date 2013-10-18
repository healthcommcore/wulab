<?php
?>
<header id="top">
		<div class="row">
		<div class="container">
			<div id="branding" class="<?php echo !$page['header'] ? 'span12' : 'span7'; ?>">
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
			
			<?php if ($page['header']): ?>
				<div class="<?php echo !$site_name && !$site_slogan && !$logo ? 'span12' : 'span5'; ?> clearfix">
					<?php print render($page['header']); ?>
				</div>
			<?php endif; ?>
		</div>
		</div>
	</header>
<nav id="main-menu">
		<a href="#" class="open-menu"><?php print t('Menu'); ?></a>
		<div class="row">
		<div class="container">
			<div class="span12">
				<?php if (render($page['mainmenu'])): ?>
					<?php print render($page['mainmenu']); ?>
				<?php elseif ($main_menu_expanded): ?>
					<?php print render($main_menu_expanded); ?>
				<?php endif; ?>
			</div>
		</div>
		</div>
	</nav>


<div id="page" class="container">
	<?php if (render($page['topbar_left']) || render($page['topbar_right']) || $secondary_menu): ?>
		<div id="topbar">
			<div class="row">
				<div class="span6">
					<?php print render($page['topbar_left']); ?>
				</div>
				<div class="span6">
					<?php if (render($page['topbar_right'])): ?>
						<?php print render($page['topbar_right']); ?>
					<?php elseif ($secondary_menu): ?>
						<nav id="secondary-menu">
							<?php print theme('links__system_secondary_menu', array('links' => $secondary_menu)); ?>
						</nav>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	
	<div id="main">
		<?php if ($page['featured']): ?>
			<div class="row">
					<div id="featured" class="span12">
						<?php print render($page['featured']); ?>
					</div> <!-- /#featured -->
			</div>
		<?php endif; ?>
		
<div class="row <?php echo ($page['home_news'] || $page['home_buttons']) ? 'home_area' : ''; ?>">
			<?php if ($page['home_news']): ?>
				<div id="home_news" class="span3">
					<?php print render($page['home_news']); ?>
				</div>
			<?php endif; ?>
			<?php if ($page['home_buttons']): ?>
				<div id="home_buttons" class="span9">
					<?php print render($page['home_buttons']); ?>
				</div>
			<?php endif; ?>
			<?php if(!drupal_is_front_page()) : ?>
			<?php if ($page['sidebar_first']): ?>
				<div class="span3 visible-desktop">
					<aside id="sidebar">
							<?php print render($page['sidebar_first']); ?>
					</aside>
				</div>
			<?php endif; ?>
			<section id="main-content" class="<?php echo ($page['sidebar_first']) ? 'span8' : 'span12'; ?>">
				<?php if (($title && !$hide_title) || render($tabs) || render($breadcrumb)) : ?>
					<header class="content clearfix">
						<?php if ($breadcrumb): ?>
							<?php print $breadcrumb; ?>
						<?php endif; ?>
						
            <div class="row">
              <div class="<?php print $page['sidebar_first'] ? 'span8' : 'span12'; ?>">
                <?php print render($title_prefix); ?>
                <?php if ($title && !$hide_title): ?>
                  <h1 id="page-title">
                    <?php print $title; ?>
                  </h1>
                <?php endif; ?>
                <?php print render($title_suffix); ?>
                
                <?php if (render($tabs)) : ?>
                  <div id="tabs" class="clearfix">
                    <?php print render($tabs); ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
					</header>
				<?php endif; ?>
				
				<?php if ($messages): ?>
					<div id="messages">
						<?php print $messages; ?>
					</div> <!-- /#messages -->
				<?php endif; ?>
				
				<?php if ($page['highlighted']): ?>
					<div id="highlighted">
						<?php print render($page['highlighted']); ?>
					</div>
				<?php endif; ?>
				
				<?php if (isset($page['slider']) && !empty($page['slider'])): ?>
					<?php print render($page['slider']); ?>
				<?php endif; ?>
			
				<?php print render($page['help']); ?>
				<?php if ($action_links): ?>
					<ul class="action-links">
						<?php print render($action_links); ?>
					</ul>
				<?php endif; ?>
				<?php print render($page['content']); ?>
			</section>
			<?php endif; ?>
			
		</div>
		
		<div class="row">
			<?php if ($page['triptych_first'] || $page['triptych_middle'] || $page['triptych_last']): ?>
				<div id="triptych-wrapper">
					<div class="span4"><?php print render($page['triptych_first']); ?></div>
					<div class="span4"><?php print render($page['triptych_middle']); ?></div>
					<div class="span4"><?php print render($page['triptych_last']); ?></div>
				</div> <!-- /#triptych-wrapper -->
			<?php endif; ?>
		</div>
	</div>
</div>
	
    <footer>
			<div class="container">
      <?php if ($page['footer_firstcolumn'] || $page['footer_secondcolumn'] || $page['footer_thirdcolumn'] || $page['footer_fourthcolumn']): ?>
        <div id="footer-main" class="row">
					<div class="span6 footer-column">
            <?php print render($page['footer_firstcolumn']); ?>
          </div>
          <div class="span2 footer-column">
            <?php print render($page['footer_secondcolumn']); ?>
          </div>
          <div class="span4 footer-column">
            <?php print render($page['footer_thirdcolumn']); ?>
          </div>
        	
        </div>
      <?php endif; ?>

      <?php if ($page['footer'] || $page['footer_stripe_right']): ?>
        <div id="footer-stripe">
          <div class="row">
            <div class="span6">
              <?php print render($page['footer']); ?>
            </div>
            <div class="span6">
              <?php print render($page['footer_stripe_right']); ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
			</div>
    </footer>
