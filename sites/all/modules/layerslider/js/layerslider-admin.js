/**
 * @file
 * Some basic behaviors and utility functions for LayerSlider.
 */
(function ($) {
	Drupal.behaviors.exchange = {
		attach: function (context, settings) {
      // Layer reordering
			$('#layer-wrapper .vertical-tabs-list').sortable({
        handle : '.handle',
        axis : 'y',
				create : function(event, ui) {
					// Insert handle div
					$('#layer-wrapper .vertical-tabs-list li a').each(function(index) {
						$(this).prepend('<div class="handle">&nbsp;</div>');
					});
					
					// Set data-layer_index attribute to each tab since Drupal doesn't provide a way
					// to identify different tabs by default.
					$('#layer-wrapper .vertical-tabs-list li').attr('data-layer_index', function(i, val) {
            return $('#layer-wrapper fieldset.vertical-tabs-pane').eq(i).data('layer_index');
					});
				},
				
        // Update the weight select list
				update : function(event, ui) {
					$('#layer-wrapper .vertical-tabs-list li').each(function(index) {
            // Remove classes indicating order
            $(this).removeClass('first last');

            // Add order indicating classes (first and last)
            if (index == 0) {
              $(this).addClass('first');
            }
            if (index == $('#layer-wrapper .vertical-tabs-list li').size()) {
              $(this).addClass('last');
            }

            var layer_index = $(this).data('layer_index');
						$('input[name="layers[' + layer_index + '][weight]"]').val(index);
					});
				}
			});

      // Sublayer reordering (z-index)
      // See http://stackoverflow.com/questions/1735372/jquery-sortable-list-scroll-bar-jumps-up-when-sorting
      $('div.sublayers').sortable({
        handle : '.handle',
        axis : 'y',
        forcePlaceHolderSize: true,
        create : function(event, ui) {
          // Insert handle div
          $('fieldset.sublayer', this).each(function(index) {
            $(this).prepend('<div class="handle">&nbsp;</div>');
          });
        },
        update : function(event, ui) {
          $('fieldset.sublayer', this).each(function(index) {
            var layer_index = $(this).data('layer_index');
            var sublayer_index = $(this).data('sublayer_index');

            // Update hidden field
            $('input[name="layers[' + layer_index + '][sublayers][' + sublayer_index + '][position][z]"]').val(index);

            // Update z-index in preview
            $('#edit-layers-' + layer_index + '-preview-area-' + sublayer_index).css('z-index', index);
          });
        }
      });

			// Sublayer dragging on the WYSIWYG area
			$('.preview-area .sublayer').draggable({
				containment: 'parent',
				scroll: false,
        // Update x-pos and y-pos fields when dragging stops
				stop: function(event, ui) {
					var id = $(this).attr('id');
					var layer_index = $(this).data('layer_index');
					var sublayer_index = $(this).data('sublayer_index');
					var pos = $(this).position();
          $('input[name="layers[' + layer_index + '][sublayers][' + sublayer_index + '][position][x]"]').val(pos.left);
          $('input[name="layers[' + layer_index + '][sublayers][' + sublayer_index + '][position][y]"]').val(pos.top);
				},
			});
      
      // Mark sublayer active (moveable with arrow keys) when clicked on
      $('.preview-area .sublayer').click(function() {
        $('.sublayer.active').removeClass('active');
        $(this).addClass('active');
      });

      // Delete sublayer with DEL key
      $(window).keydown(function(e) {
        if ($('.preview-area .sublayer.active')[0] && e.keyCode == 46) {
          e.preventDefault();
          var active = $('.preview-area .sublayer.active');
          var layer_index = active.data('layer_index');
          var sublayer_index = active.data('sublayer_index');

          // Trigger delete button
          $('input[name="layer-' + layer_index + '-sublayer-' + sublayer_index + '-remove"]').mousedown();
        }
      });

      // Open sublayer fieldset with double click on the preview area
      $('.preview-area .sublayer').dblclick(function() {
        var lid = $(this).data('layer_index');
        var sid = $(this).data('sublayer_index');

        // Close other fieldsets
        $('#edit-layers-' + lid + '-sublayers fieldset:not(.collapsed):not(#edit-layers-' + lid + '-sublayers-' + sid + ') a.fieldset-title:first').click();

        // Open the fieldset
        $('fieldset#edit-layers-' + lid + '-sublayers-' + sid + '.collapsed a.fieldset-title').click();
      });
      
      // Move sublayer with arrow keys
      $(window).keydown(function(e) {
        if (e.shiftKey == true && $('.preview-area .sublayer.active')[0]) {
          if (e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40) {
            var box = $('.preview-area .sublayer.active');
            var oldLeft = parseInt(box.css('left'));
            var oldTop = parseInt(box.css('top'));
            var movement = 10; // pixels to move by one press
            var layer_index = box.data('layer_index');
            var sublayer_index = box.data('sublayer_index');
            var area_width = box.parent('.preview-area').css('width');
            var area_height = box.parent('.preview-area').css('height');
            
            // Calculate max values for both vertical and horizontal position
            if (e.keyCode == 37 || e.keyCode == 39) {
              var maxValue = area_width - box.width();
              
              // Subtract / Add
              var newValue = e.keyCode == 37 ? oldLeft - movement : oldLeft + movement;
              
              // Restrict so that the sublayer will stay in the container
              var restrictedValue = newValue < 0 ? 0 : newValue > maxValue ? maxValue : newValue;
              
              // Reposition
              box.css('left', restrictedValue);
              
              // Update value to the hidden x-position field
              $('input[name="layers[' + layer_index + '][sublayers][' + sublayer_index + '][position][x]"]').val(restrictedValue);
            } else {
              var maxValue = area_height - box.height();
              
              // Subtract / Add
              var newValue = e.keyCode == 38 ? oldTop - movement : oldTop + movement;
              
              // Restrict so that the sublayer will stay in the container
              var restrictedValue = newValue < 0 ? 0 : newValue > maxValue ? maxValue : newValue;
              
              // Reposition
              box.css('top', restrictedValue);
              
              // Update value to the hidden y-position field
              $('input[name="layers[' + layer_index + '][sublayers][' + sublayer_index + '][position][y]"]').val(restrictedValue);
            }
          }
        }
      });
		}
	};
})(jQuery);