<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces; ?>>

<head profile="<?php print $grddl_profile; ?>">
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
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
<!-- Popup window for external links -->
<script>
(function($){
	var extLinks = $('.popup');
	var width = $(window).width() / 1.5;
	var height = $(window).height();
	var xPos = window.screenX + (window.outerWidth - width) / 2;
	var yPos = window.screenY + (window.outerHeight - height) / 2;;
	var options = "resizable=yes,menubar=yes,toolbar=yes,status=yes,location=yes," + 
		"height=" + height + ",width=" + width + 
		",top=" + yPos + ",left=" + xPos;
	for(var i = 0; i < extLinks.length; i++){
		extLinks[i].onclick = function(){
			window.open(this.href, this.value, options);
			return false;
		}
	}
})(jQuery);
</script>
</body>
</html>
