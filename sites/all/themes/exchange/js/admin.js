(function ($) {
	Drupal.behaviors.exchange = {
		attach: function (context, settings) {
      // Layer order can be controlled by dragging layers. We will use
      // jQuery UI's sortable for this purpose.
			$('#layers .vertical-tabs-list').sortable({
				create : function(event, ui) {
					// Insert handle div
					$('#layers .vertical-tabs-list li a').each(function(index) {
						$(this).prepend('<div class="handle">&nbsp;</div>');
					});
					
					// Hide weight fields
					$('#layers .weight-select').parent().hide();
					
					// Set index attribute to each tab since Drupal doesn't provide a way
					// to identify different tabs by default.
					$('#layers .vertical-tabs-list li').attr('id', function(i, val) {
						var index = $(this).index();
						return index;
					});
				},
				
        // Update the weight select list
				update : function(event, ui) {
					$('#layers .vertical-tabs-list li').each(function(index) {
						var id = $(this).attr('id');
						var pos = $(this).index();
						$('#layers .layer-fieldset:eq(' + id + ') .weight-select').val(pos);
					});
				}
			});
			
			// Sublayer dragging on the WYSIWYG area
			$('.sublayer').draggable({
				containment: 'parent',
				scroll: false,
        // Update x-pos and y-pos fields when dragging stops
				stop: function(event, ui) {
					var id = $(this).attr('id');
					var pos = $(this).position();
          $('input.x-pos.' + id).val(pos.left);
          $('input.y-pos.' + id).val(pos.top);
				},
			});
      
      $('.area .sublayer').click(function() {
        $('.sublayer.active').removeClass('active');
        $(this).addClass('active');
      });
      
      $(window).keydown(function(e) {
        if (e.shiftKey == true && $('.area .sublayer.active')[0]) {
          if (e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40) {
            var box = $('.area .sublayer.active');
            var oldLeft = parseInt(box.css('left'));
            var oldTop = parseInt(box.css('top'));
            var movement = 10; // pixels to move by one press
            
            // Calculate max values for both vertical and horizontal position
            if (e.keyCode == 37 || e.keyCode == 39) {
              var maxValue = 978 - box.width();
              
              // Subtract / Add
              var newValue = e.keyCode == 37 ? oldLeft - movement : oldLeft + movement;
              
              // Restrict so that the sublayer will stay in the container
              var restrictedValue = newValue < 0 ? 0 : newValue > maxValue ? maxValue : newValue;
              
              // Reposition
              box.css('left', restrictedValue);
              
              // Update value to the hidden x-position field
              $('input.x-pos.' + box.attr('id')).val(restrictedValue);
            } else {
              var maxValue = 396 - box.height();
              
              // Subtract / Add
              var newValue = e.keyCode == 38 ? oldTop - movement : oldTop + movement;
              
              // Restrict so that the sublayer will stay in the container
              var restrictedValue = newValue < 0 ? 0 : newValue > maxValue ? maxValue : newValue;
              
              // Reposition
              box.css('top', restrictedValue);
              
              // Update value to the hidden y-position field
              $('input.y-pos.' + box.attr('id')).val(restrictedValue);
            }
          }
        }
      });

			// Change slider area background
			var color = $('#color_scheme_form #edit-palette-slider').val();
			$('#edit-slider .area').css('backgroundColor', color);
			
			// Save data about collapsed and uncollapsed fieldsets
			var uncollapsed = new Array();
			$('#edit-slider fieldset.collapsible:not(.collapsed)').each(function() {
				var id = $(this).attr('id');
				uncollapsed.push(id);
			});
			$('input#slider-uncollapsed').val(uncollapsed.join());
			
			$('#edit-slider fieldset.collapsible').bind('collapsed', function(arg) {
				var uncollapsed = $('input#slider-uncollapsed').val().split(',');
				var id = $(this).attr('id');
				
				// Collaps
				if (arg.value == true) {
					for (var key in uncollapsed) {
						if (uncollapsed[key] == id) {
							uncollapsed.splice(key, 1);
						}
					}
					
				} 
				// Uncollaps
				else {
					uncollapsed.push(id);
				}
				
				$('input#slider-uncollapsed').val(uncollapsed.join());
			});
		}
	};
})(jQuery);