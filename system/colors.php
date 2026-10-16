<?php
/*
	$Project: Ka Theme Colors $
	$Author: karapuz team <support@ka-station.com> $
	$Version: 1.1.0.0 $

	Color definitions and CSS generator shared by the admin page and the storefront.
*/

namespace Opencart\Extension\KaThemeColors\System;

class Colors {

	const CODE      = 'ka_theme_colors';
	const CACHE_KEY = 'ka_theme_colors.css';

	/**
	 Returns color groups. Each color: code => default value (OC4 default theme colors).
	 An empty default means "automatic" (calculated from another color).

	 @return array group => [color_code => default]
	*/
	public static function getGroups() {
		return array(
			'primary' => array(
				'primary'       => '#229ac8',
				'primary_hover' => '',
			),
			'top' => array(
				'top_bg'   => '#f8f9fa',
				'top_text' => '#6c757d',
			),
			'header' => array(
				'header_bg' => '#ffffff',
			),
			'menu' => array(
				'menu_bg'   => '#229ac8',
				'menu_text' => '#ffffff',
			),
			'cart' => array(
				'cart_bg'   => '#212529',
				'cart_text' => '#ffffff',
			),
			'footer' => array(
				'footer_bg'   => '#303030',
				'footer_text' => '#e2e2e2',
				'footer_link' => '#cccccc',
			),
			'price' => array(
				'price' => '#444444',
			),
		);
	}

	/**
	 Returns a flat list of colors: setting key => default.

	 @return array
	*/
	public static function getDefaults() {
		$result = array();
		foreach (static::getGroups() as $colors) {
			foreach ($colors as $k => $v) {
				$result[static::CODE . '_' . $k] = $v;
			}
		}
		return $result;
	}

	/**
	 Normalizes a color to #rrggbb.

	 $color — '#abc', 'abc', '#aabbcc'.

	 @return string normalized color or empty string when the value is not a valid color.
	*/
	public static function normalize($color) {
		$color = strtolower(trim((string)$color));
		if (!preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/', $color, $m)) {
			return '';
		}
		$hex = $m[1];
		if (strlen($hex) == 3) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		return '#' . $hex;
	}

	public static function toRgb($color) {
		$hex = substr(static::normalize($color), 1);
		return array(hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
	}

	/**
	 Mixes a color with another color.

	 $weight — part of $with in the result, 0..1.
	*/
	public static function mix($color, $with, $weight) {
		$a = static::toRgb($color);
		$b = static::toRgb($with);
		$res = '#';
		for ($i = 0; $i < 3; $i++) {
			$res .= sprintf('%02x', round($a[$i] * (1 - $weight) + $b[$i] * $weight));
		}
		return $res;
	}

	/**
	 Builds the CSS block for the storefront.

	 $settings — setting key => color (missing or invalid values are replaced with defaults).

	 @return string CSS
	*/
	public static function buildCss($settings) {

		$c = array();
		foreach (static::getDefaults() as $key => $default) {
			$short = substr($key, strlen(static::CODE) + 1);
			$value = isset($settings[$key]) ? static::normalize($settings[$key]) : '';
			$c[$short] = $value ? $value : $default;
		}

		if (empty($c['primary_hover'])) {
			$c['primary_hover'] = static::mix($c['primary'], '#000000', 0.2);
		}

		$p      = $c['primary'];
		$p_rgb  = implode(',', static::toRgb($p));
		$hover  = $c['primary_hover'];
		$active = static::mix($hover, '#000000', 0.05);

		$vars = array(
			'--ka-primary'      => $p,
			'--ka-primary-rgb'  => $p_rgb,
			'--ka-primary-hover'=> $hover,
			'--ka-top-bg'       => $c['top_bg'],
			'--ka-top-text'     => $c['top_text'],
			'--ka-header-bg'    => $c['header_bg'],
			'--ka-menu-bg'      => $c['menu_bg'],
			'--ka-menu-text'    => $c['menu_text'],
			'--ka-cart-bg'      => $c['cart_bg'],
			'--ka-cart-text'    => $c['cart_text'],
			'--ka-footer-bg'    => $c['footer_bg'],
			'--ka-footer-text'  => $c['footer_text'],
			'--ka-footer-link'  => $c['footer_link'],
			'--ka-price'        => $c['price'],
			// bootstrap variables, alerts/badges/links follow the primary color
			'--bs-primary'                => 'var(--ka-primary)',
			'--bs-primary-rgb'            => 'var(--ka-primary-rgb)',
			'--bs-link-color'             => 'var(--ka-primary)',
			'--bs-link-color-rgb'         => 'var(--ka-primary-rgb)',
			'--bs-link-hover-color'       => 'var(--ka-primary-hover)',
			'--bs-link-hover-color-rgb'   => implode(',', static::toRgb($hover)),
			'--bs-primary-bg-subtle'      => static::mix($p, '#ffffff', 0.8),
			'--bs-primary-border-subtle'  => static::mix($p, '#ffffff', 0.6),
			'--bs-primary-text-emphasis'  => static::mix($p, '#000000', 0.6),
		);

		$css = ":root{";
		foreach ($vars as $k => $v) {
			$css .= $k . ':' . $v . ';';
		}
		$css .= "}\n";

		$css .= ".btn-primary{--bs-btn-bg:var(--ka-primary);--bs-btn-border-color:var(--ka-primary);"
			. "--bs-btn-hover-bg:var(--ka-primary-hover);--bs-btn-hover-border-color:var(--ka-primary-hover);"
			. "--bs-btn-active-bg:$active;--bs-btn-active-border-color:$active;"
			. "--bs-btn-disabled-bg:var(--ka-primary);--bs-btn-disabled-border-color:var(--ka-primary);"
			. "--bs-btn-focus-shadow-rgb:var(--ka-primary-rgb);}\n";
		$css .= ".btn-outline-primary{--bs-btn-color:var(--ka-primary);--bs-btn-border-color:var(--ka-primary);"
			. "--bs-btn-hover-bg:var(--ka-primary);--bs-btn-hover-border-color:var(--ka-primary);"
			. "--bs-btn-active-bg:var(--ka-primary);--bs-btn-active-border-color:var(--ka-primary);}\n";
		$css .= ".form-control:focus,.form-select:focus{border-color:var(--bs-primary-border-subtle);box-shadow:0 0 0 .25rem rgba(var(--ka-primary-rgb),.25)}\n";
		$css .= ".form-check-input:checked{background-color:var(--ka-primary);border-color:var(--ka-primary)}\n";
		$css .= ".page-link{--bs-pagination-color:var(--ka-primary);--bs-pagination-hover-color:var(--ka-primary-hover);--bs-pagination-active-bg:var(--ka-primary);--bs-pagination-active-border-color:var(--ka-primary)}\n";
		$css .= ".nav-tabs .nav-link{color:var(--ka-primary)}.nav-tabs .nav-link.active{color:var(--bs-body-color)}\n";

		$css .= "#top{background-color:var(--ka-top-bg)}\n";
		$css .= "#top .list-inline-item>a,#top .list-inline-item .dropdown>a,#top .list-inline-item .dropdown>span,#top .list-inline-item a span{color:var(--ka-top-text)}\n";

		$css .= "header{background-color:var(--ka-header-bg)}\n";
		if ($c['header_bg'] != '#ffffff') {
			// remove the white gap between the top bar and the colored header
			$css .= "#top{margin-bottom:0;border-bottom-color:transparent}header{padding-top:20px;padding-bottom:5px;margin-bottom:0}\n";
		}

		$css .= "#menu{background-color:var(--ka-menu-bg)!important;background-image:none;border-color:var(--ka-menu-bg)}\n";
		$css .= "#menu .navbar-nav>li>a,#category,#menu .navbar-toggler i{color:var(--ka-menu-text)}\n";
		$css .= "#menu .navbar-toggler i{border-color:var(--ka-menu-text)}\n";

		$css .= "#cart>.btn,#cart>.dropdown>.btn,#cart .btn-dark{--bs-btn-bg:var(--ka-cart-bg);--bs-btn-border-color:var(--ka-cart-bg);"
			. "--bs-btn-hover-bg:var(--ka-cart-bg);--bs-btn-hover-border-color:var(--ka-cart-bg);--bs-btn-active-bg:var(--ka-cart-bg);"
			. "--bs-btn-color:var(--ka-cart-text);--bs-btn-hover-color:var(--ka-cart-text);--bs-btn-active-color:var(--ka-cart-text)}\n";
		$css .= "#cart>.btn:hover,#cart .btn-dark:hover{filter:brightness(1.1)}\n";

		$css .= "footer{background-color:var(--ka-footer-bg);color:var(--ka-footer-text)}\n";
		$css .= "footer a{color:var(--ka-footer-link)}footer a:hover{color:var(--ka-footer-text);text-decoration:underline}footer h5{color:var(--ka-footer-text)}\n";

		$css .= ".price,.price-new{color:var(--ka-price)}\n";

		return $css;
	}
}
