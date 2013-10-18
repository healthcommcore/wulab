// Handle the color changes and update the preview window.
(function ($) {
  /**
   * To understand what overlay, average, multiply and compare do,
   * please see exchange.utilities.inc
   */
  var overlay = function(bottom, top, opacity) {
    opacity = typeof opacity !== 'undefined' ? opacity : 1;

    var top = hex_to_rgb(top);
    var bottom = hex_to_rgb(bottom);

    var overlay = new Array();
    for (var i = 0; i < 3; i++) {
      var a = bottom[i];
      var b = top[i];

      if (a < 128) {
        overlay[i] = Math.round(2 * b * a / 255);
      } else {
        overlay[i] = Math.round(255 * (1 - 2 * (1 - b / 255) * (1 - a / 255)));
      }
    }

    for (var i = 0; i < 3; i++) {
      var a = bottom[i];
      var b = overlay[i];

      overlay[i] = Math.round((1 - opacity) * a + opacity * b);
    }

    return rgb_to_hex(overlay);
  }

  var multiply = function(bottom, top, opacity) {
    opacity = typeof opacity !== 'undefined' ? opacity : 1;

    var top = hex_to_rgb(top);
    var bottom = hex_to_rgb(bottom);

    var multiply = new Array();
    for (var i = 0; i < 3; i++) {
      var a = bottom[i];
      var b = top[i];

      multiply[i] = Math.round(a * b / 255);
    }

    for (var i = 0; i < 3; i++) {
      var a = bottom[i];
      var b = multiply[i];

      multiply[i] = Math.round((1 - opacity) * a + opacity * b);
    }

    return rgb_to_hex(multiply);
  }

  var average = function(color1, color2) {
    var color1 = hex_to_rgb(color1);
    var color2 = hex_to_rgb(color2);

    var result = new Array();
    for (var i = 0; i < 3; i++) {
      var a = color1[i];
      var b = color2[i];

      result[i] = Math.round((a + b) / 2);
    }

    return rgb_to_hex(result);
  }

  var compare = function(color1, color2) {
    var color1 = hex_to_rgb(color1);
    var color2 = hex_to_rgb(color2);

    var average1 = (color1[0] + color1[1] + color1[2]) / 3;
    var average2 = (color2[0] + color2[1] + color2[2]) / 3;

    if (average1 > average2) {
      return rgb_to_hex(color2);
    }

    return rgb_to_hex(color1);
  }

  var hex_to_rgb = function(hexStr){
    var hex = parseInt(hexStr.substring(1), 16);
    var r = (hex & 0xff0000) >> 16;
    var g = (hex & 0x00ff00) >> 8;
    var b = hex & 0x0000ff;
    return [r, g, b];
  }

  var rgb_to_hex = function(rgbArr) {
    return "#" + ((1 << 24) + (rgbArr[0] << 16) + (rgbArr[1] << 8) + rgbArr[2]).toString(16).slice(1);
  }

  Drupal.color = {
    logoChanged: false,
    callback: function(context, settings, form, farb, height, width) {
      // Background
      $('#preview', form).css('backgroundColor', $('#palette input[name="palette[background]"]', form).val());

      // Site name
      $('#preview-logo', form).css('color', $('#palette input[name="palette[sitename]"]', form).val());
 
      // Text
      $('#preview-main h2, #preview-main #preview-content', form).css('color', $('#palette input[name="palette[text]"]', form).val());
 
      // Links
      $('#preview-content a', form).css('color', $('#palette input[name="palette[link]"]', form).val());
 
      // Menu item link color
      $('#preview-main-menu a', form).css('color', $('#palette input[name="palette[navcolor]"]', form).val());
			
			// Footer
			$('#preview-footer', form).css('backgroundColor', $('#palette input[name="palette[footer]"]', form).val());
			$('#preview-footer', form).css('color', $('#palette input[name="palette[footercolor]"]', form).val());

      // CSS3 Gradients.
      var headertop = $('#palette input[name="palette[headertop]"]', form).val();
      var headerbottom = $('#palette input[name="palette[headerbottom]"]', form).val();
      var navtop = $('#palette input[name="palette[navtop]"]', form).val();
      var navbottom = $('#palette input[name="palette[navbottom]"]', form).val();
 
      $('#preview #preview-header', form).attr('style', "background-color: " + headertop + "; background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(" + headertop + "), to(" + headerbottom + ")); background-image: -moz-linear-gradient(-90deg, " + headertop + ", " + headerbottom + ");");
			$('#preview #preview-main-menu', form).attr('style', "background-color: " + navtop + "; background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(" + navtop + "), to(" + navbottom + ")); background-image: -moz-linear-gradient(-90deg, " + navtop + ", " + navbottom + ");");

      // Calculate borders and other special effects
      var headerbordertop = overlay(headertop, '#ffffff', 0.3);
      $('#preview #preview-header', form).css('border-top-color', headerbordertop);

      var darker = compare(navtop, headerbottom);
      var headerborderbottom = multiply(darker, '#000000', 0.3)
      $('#preview #preview-header', form).css('border-bottom-color', headerborderbottom);

      var navbordertop = overlay(navtop, '#ffffff', 0.25);
      var navborderbottom = multiply(navbottom, '#000000', 0.3);
      $('#preview #preview-main-menu', form).css('border-top-color', navbordertop);
      $('#preview #preview-main-menu', form).css('border-bottom-color', navborderbottom);

      var navaverage = average(navtop, navbottom);
      var navborderright = multiply(navaverage, '#000000', 0.3);
      var navborderleft = overlay(navaverage, '#ffffff', 0.3);
      $('#preview-main-menu li', form).css('border-right-color', navborderright);
      $('#preview-main-menu li', form).css('border-left-color', navborderleft);

    }
  };
})(jQuery);