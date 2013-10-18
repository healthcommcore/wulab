<?php
/**
 * Color class will provide a few coloring effects and utilities
 * for the Color module. For example, we might want to overlay
 * white border with 50% opacity to top of the header region.
 * While we could simply have almost similar effect with
 * transparent white PNG image, we want to be perfect when it 
 * comes the design and use the real effect.
 */
class Color {
	/**
	 * Overlay blending
	 *
	 * This function mimics Photoshop overlay blending mode. The exact equation for overlay
	 * is not known, however Kevin Jensen has a pretty accurate equation available on his website.
	 * Please see http://www.venture-ware.com/kevin/coding/lets-learn-math-photoshop-blend-modes/
	 *
	 * @param string $bottom Color which will be overlayed with $top formatted as HEX color string, i.e. for red '#FF0000'
	 * @param string $top Color which overlays $bottom formatted as HEX color string, i.e. for white '#FFFFFF'
	 * @param float $opacity Optional opacity to be applied to the overlayed color in relation to $bottom
	 *
	 * @return string Color resulted in overlaying formatted as HEX color string
	 */
	public static function overlay($bottom, $top, $opacity = NULL) {
		// Convert $top and $bottom to RGB array
		$top = self::hex_to_rgb($top);
		$bottom = self::hex_to_rgb($bottom);

		// Overlay $bottom with $top
		$overlay = array();
		foreach ($bottom as $i => $a) {
			$b = $top[$i];
			if ($a < 128) {
				$overlay[$i] = (int) (2 * $b * $a / 255);
			} else {
				$overlay[$i] = (int) (255 * (1 - 2 * (1 - $b / 255) * (1 - $a / 255)));
			}
		}
	 
		// Apply opacity to $overlay in relation to $bottom
		if (isset($opacity)) {
			foreach ($overlay as $i => $b) {
				$a = $bottom[$i];
				$overlay[$i] = (int) ((1 - $opacity) * $a + $opacity * $b);
			}
		}

		return self::rgb_to_hex($overlay);
	}

	/**
	 * Multiple blending
	 *
	 * @param string $bottom Color which will be multiplied with $top formatted as HEX color string, i.e. for red '#FF0000'
	 * @param string $top Color which multiplies $bottom formatted as HEX color string, i.e. for white '#FFFFFF'
	 * @param float $opacity Optional opacity to be applied to the multiplied color in relation to $bottom
	 *
	 * @return string Color resulted in overlaying formatted as HEX color string
	 */
	public static function multiply($bottom, $top, $opacity) {
		// Convert $top and $bottom to RGB array
		$top = self::hex_to_rgb($top);
		$bottom = self::hex_to_rgb($bottom);

		// Multiply $bottom with $top
		$multiply = array();
		foreach ($bottom as $i => $a) {
			$b = $top[$i];
			$multiply[$i] = (int) $b * $a / 255;
		}

		// Apply opacity to $multiply in relation to $bottom
		if (isset($opacity)) {
			foreach ($multiply as $i => $b) {
				$a = $bottom[$i];
				$multiply[$i] = (int) ((1 - $opacity) * $a + $opacity * $b);
			}
		}

		return self::rgb_to_hex($multiply);
	}

	/**
	 * Calculates average of two colors
	 *
	 * @param array $colors Two HEX string colors in an array which average to calculate
	 *
	 * @return string Average color
	 */
	public static function average($colors) {
		// Convert colors to RGB arrays
		$colors[0] = self::hex_to_rgb($colors[0]);
		$colors[1] = self::hex_to_rgb($colors[1]);

		// Calculate average
		$result = array();
		foreach ($colors[0] as $i => $a) {
			$b = $colors[1][$i];
			$result[$i] = (int) (($a + $b) / 2);
		}

		return self::rgb_to_hex($result);
	}

	/**
	 * Compares two colors and returns lighter/darker depending on $mode
	 *
	 * @param array $colors Two HEX string colors to compare in an array
	 * @param string $mode Which one of the two colors to return. Can be 'lighter' or 'darker'
	 *
	 * @return string Color
	 */
	public static function compare($colors, $mode = 'darker') {
		// Convert colors to RGB arrays
		$colors[0] = self::hex_to_rgb($colors[0]);
		$colors[1] = self::hex_to_rgb($colors[1]);

		// Calculate average of all channels.
		$averages = array();
		$averages[0] = ($colors[0][0] + $colors[0][1] + $colors[0][2]) / 3;
		$averages[1] = ($colors[1][0] + $colors[1][1] + $colors[1][2]) / 3;

		// Get the key. If $mode is 'darker', get the lower value and if 'lighter', get the higher value
		$key = $mode == 'darker' ? array_search(min($averages), $averages) : array_search(max($averages), $averages);

		return self::rgb_to_hex($colors[$key]);
	}

	/**
	 * Converts HEX color string to RGB array
	 *
	 * @param string $hex HEX color string, for example red '#FF0000'
	 *
	 * @return array RGB array
	 */
	public static function hex_to_rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);

		return $rgb;
	}

	/**
	 * Converts RGB array to HEX string color
	 *
	 * @param array $rgb RGB color array, for example red array(255, 0 0)
	 *
	 * @return array HEX color string
	 */
	public static function rgb_to_hex($rgb) {
		$hex = "#";
		$hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

		return $hex;
	}
}