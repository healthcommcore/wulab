/**
 * Responsive navigation
 */
jQuery.fn.responsiveNav = function() {
	$ = jQuery;

	jQuery(this).removeClass('responsive select-menu');

	// Return if responsive menu is set to none
	if (Drupal.settings['exchange']['responsive_menu_type'] == '') {
		return;
	}

	var activate = false;

	// Automatic responsive navigation
	if (Drupal.settings['exchange']['responsive_menu_trigger'] == 'auto') {
		// Check if the first menu item is on the same line as the last one.
		// If not, use responsive menu.
		var first_pos = jQuery('ul.menu:first-child li.first', this).position();
		var last_pos = jQuery('ul.menu:first-child li.last', this).position();

		if (first_pos.top != last_pos.top) {
			activate = true;
		}
	}
	// Breakpoint responsive navigation
	else if (Drupal.settings['exchange']['responsive_menu_trigger'] == 'breakpoint') {
		if (Drupal.settings['exchange']['responsive_menu_breakpoint'] >= jQuery(window).width()) {
			activate = true;
		}
	}

	// Create select list menu
	if (Drupal.settings['exchange']['responsive_menu_type'] == 'select' && $('#main-menu-select').length == 0) {
		// Create select list
		$("<select id='main-menu-select' />").appendTo(this);

	  // Populate dropdown with menu items
	  var selected = false;
	  $('a:not(.open-menu)', this).each(function() {
	    var el = $(this);
	    var option =$("<option />", {
				"value"   :  el.attr("href"),
				"text"    :  el.text()
	    });

	    // Select active item
	    if (el.hasClass('active') && !selected) {
				option.attr('selected', 'selected');

				selected = true;
	    }

	    option.appendTo('select#main-menu-select');
		});

		// On change redirect to the item URL
	  $('select#main-menu-select').change(function() {
			if ($('option:selected', this).val() != '') {
				window.location = $('option:selected', this).val();
			}
	  });
	}

	if (activate) {
		if (Drupal.settings['exchange']['responsive_menu_type'] == 'select') {
			$('#main-menu').addClass('select-menu');
		} else {
			jQuery(this).addClass('responsive');

			// Show submenus
			jQuery('li.expanded ul', this).show();
		}
	} else {
		if (Drupal.settings['exchange']['responsive_menu_type'] == 'select') {
			$('#main-menu').removeClass('select-menu');
		} else {
			// Hide submenus
			jQuery('li.expanded ul', this).hide();

			// Close the menu
			jQuery('ul.opened').removeClass('opened');
		}
	}
}

jQuery(document).ready(function($) {
	/**
	 * LayerSlider 3
	 */
	autostart = true;
	if (Drupal.settings['exchange']['autostart'] == 0) {
		var autostart = false;
	}
	
	pauseonhover = false;
	if (Drupal.settings['exchange']['pauseonhover'] == 1) {
		var pauseonhover = true;
	}
	
	autoplayvideos = false;
	if (Drupal.settings['exchange']['autoplayvideos'] == 1) {
		var autoplayvideos = true;
	}
	
	$('#slider').layerSlider({
			autoStart               : autostart,
			responsive              : true,
			firstLayer              : 1,
			twoWaySlideshow         : false,
			keybNav                 : true,
			touchNav                : true,
			imgPreload              : true,
			navPrevNext             : true,
			navStartStop            : false,
			navButtons              : false,
			pauseOnHover            : pauseonhover,
      skinsPath               : Drupal.settings['layerslider']['skinsPath'],
      skin                    : 'exchange',
			globalBGColor           : 'transparent',
			animateFirstLayer       : true,
			autoPlayVideos          : autoplayvideos,
			autoPauseSlideshow      : 'auto',
			youtubePreview          : 'maxresdefault.jpg',
			slideDirection          : 'right',
			slideDelay              : 4000,
			durationIn              : 1000,
			durationOut             : 1000,
			easingIn                : 'easeInOutQuint',
			easingOut               : 'easeInOutQuint',
			delayIn                 : 0,
			delayOut                : 0,
			showCircleTimer					: false,
			showBarTimer					  : true,
	});
  
	// Show slider controller on hover
	$('#slider-container').hover(
		function() {
			$('.ls-nav-prev, .ls-nav-next', this).fadeIn(180);
		}, 
		function() {
			$('.ls-nav-prev, .ls-nav-next', this).fadeOut(180);
		}
	);
  
// Hide sidebar and resize main content depending on
// browser size and if browser window is resized
	var sidebar = $('#sidebar');
	//var insideHeader = $('#inside_header');
	var mainContent = $('#main-content');

	mainContentReflow();
	$(window).resize(function(){
		mainContentReflow();
	});

	function mainContentReflow(){
		if(sidebar.parent().css('display') == 'none'){
			//insideHeader.removeClass('span9').addClass('span12');
			mainContent.removeClass('span8').addClass('span12');
		}
		else{
			//insideHeader.removeClass('span12').addClass('span9');
			mainContent.removeClass('span12').addClass('span8');
		}
	}

	/**
	 * Dropdown
	$('#main-menu li.expanded').hover(
		function () {
			var width = $(window).width();
			if (!$('#main-menu').hasClass('responsive')) {
				$(this).addClass("hover");
				$('ul:first', this).slideDown('fast');
			}
		},
		function () {
			var width = $(window).width();
			if (!$('#main-menu').hasClass('responsive')) {
				$(this).removeClass("hover");
				$('ul:first',this).slideUp('fast');
			}
		}
	);
	 */

	/**
	 * Indicator for dropdown menus
	$('#main-menu li.expanded').each(function() {
		$(this).children('a').append(' &raquo;');
	});
	 */

	/**
	 * Responsive nav init
	 */
	$('#main-menu').responsiveNav();

	/**
	 * Responsive navigation toggling
	 */
	$('#main-menu .open-menu').click(function(e) {
		e.preventDefault();
		var menu = $(this).parent();

		$('ul.menu:first-child', menu).slideToggle(400, function() {
			if ($(this).is(':visible')) {
				$(this).addClass('opened');
			} else {
				$(this).removeClass('opened');
			}
			$(this).removeAttr('style');
		});
	});

	/**
	 * Portfolio thumbnails, hover effect
	 */
	$('.portfolio .work-item').hover(
    function () {
      $('.work-entry', this).fadeTo(320, 1);
    },
    function () {
      $('.work-entry', this).fadeTo(320, 0);
    }
	);
});

;(function($) {
	/**
	 * Re-init responsive nav on window resize
	 */
	$(window).resize(function(){
		$('#main-menu').responsiveNav();
	});


  $(window).bind('load', function() {
    /**
     * Accordion active class
     */
    $('.accordion-group').each(function() {
      if ($('.accordion-body', this).height() != 0) {
        $('.accordion-toggle', this).addClass('active');
      }
    });
    
    /**
     * Sticky footer
    stickyFooter();
     
    $(window)
      .scroll(stickyFooter)
      .resize(stickyFooter);
     
    function stickyFooter() {
      var docHeight = $(document.body).height() - $("#sticky-footer-push").height();
      if(docHeight < $(window).height()){
        var diff = $(window).height() - docHeight;
        if (!$("#sticky-footer-push").length > 0) {
          $("#main-content").append('<div id="sticky-footer-push"></div>');
        }
        $("#sticky-footer-push").height(diff);
      }
    }
     */

		/**
		* Full height sidebar
		*/
		if($(window).width() >= 1024) {
			sidebarHeight();
			function sidebarHeight() {
				if ($('#main-content').height() > $('#sidebar').height()) {
					var diff = $('#main-content').height() - $('#sidebar').height();
					$("#sidebar").append('<div id="sidebar-push"></div>');
					$("#sidebar-push").height(diff);
				}
			}
		}
  });
})(jQuery);
