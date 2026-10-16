<?php
/*
	$Project: Ka Theme Colors $
	$Author: karapuz team <support@ka-station.com> $
	$Version: 1.1.0.0 $

	Custom CSS and JavaScript entered on the settings page and printed on the storefront.
*/

namespace Opencart\Extension\KaThemeColors\System;

class CustomCode {

	const CSS = 'ka_theme_colors_custom_css';
	const JS  = 'ka_theme_colors_custom_js';

	/**
	 Returns the setting keys of the custom code fields: key => type (css or js).

	 @return array
	*/
	public static function getFields() {
		return array(
			static::CSS => 'css',
			static::JS  => 'js',
		);
	}

	/**
	 Converts a saved value to the code for the page.
	 OpenCart stores POST data after htmlspecialchars(), so the value is decoded back to the original text.
	 A closing </style> or </script> tag inside the code would end the block early, so its slash is escaped
	 (<\/style> is still valid inside CSS and JavaScript strings).

	 $value — saved setting value.
	 $type — css or js.

	 @return string Code ready for output, empty string when there is no code.
	*/
	public static function prepare($value, $type) {

		if (!is_string($value)) {
			return '';
		}

		$code = trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
		if ($code === '') {
			return '';
		}

		$tag  = ($type == 'js') ? 'script' : 'style';
		$code = preg_replace('#</(' . $tag . ')#i', '<\\/$1', $code);

		return $code;
	}
}
