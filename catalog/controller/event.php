<?php
/*
	$Project: Ka Theme Colors $
	$Author: karapuz team <support@ka-station.com> $
	$Version: 1.0.0.0 $
*/

namespace Opencart\Catalog\Controller\Extension\KaThemeColors;

use \Opencart\Extension\KaThemeColors\System\Colors;

class Event extends \Opencart\Extension\KaExtensions\System\Controller {

	/**
	 Event handler for catalog/view/common/header/after.
	 Inserts the color CSS block right before </head>, i.e. after stylesheet.css and the other page styles.
	*/
	public function header(string &$route, array &$args, string &$output) {

		$css = $this->cache->get(Colors::CACHE_KEY);

		if (empty($css) || !is_string($css)) {
			$this->load->model('setting/setting');
			$css = Colors::buildCss($this->model_setting_setting->getSetting(Colors::CODE));
			$this->cache->set(Colors::CACHE_KEY, $css);
		}

		$block = "<style id=\"ka-theme-colors\">\n" . $css . "</style>\n";

		$pos = stripos($output, '</head>');
		if ($pos !== false) {
			$output = substr_replace($output, $block, $pos, 0);
		}
	}
}
