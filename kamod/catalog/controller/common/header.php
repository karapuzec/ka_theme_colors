<?php
/*
	$Project: Ka Theme Colors $
	$Author: karapuz team <support@ka-station.com> $
	$Version: 1.0.0.0 $

	Adds the color CSS of the module to the header template data. The CSS is printed before </head>
	by the kapatch file kamod/catalog/view/template/common/header.twig.xml.
*/

namespace Opencart\Catalog\Controller\Extension\KaThemeColors\Common;

use \Opencart\Extension\KaThemeColors\System\Colors;

class Header extends \Opencart\Catalog\Controller\Common\Header {

	use \Opencart\Extension\KaExtensions\System\TraitController;

	public function index(): string {

		$this->disableRender();
		parent::index();
		$this->enableRender();

		$data     = $this->getRenderData();
		$template = $this->getRenderTemplate();

		$data['ka_theme_colors_style'] = $this->getColorsCss();

		return $this->load->view($template, $data);
	}


	/**
	 Returns the color CSS from the OpenCart cache. Regenerates it from the module settings when the cache is empty.

	 @return string CSS code.
	*/
	protected function getColorsCss() {

		$css = $this->cache->get(Colors::CACHE_KEY);

		if (empty($css) || !is_string($css)) {
			$this->load->model('setting/setting');
			$css = Colors::buildCss($this->model_setting_setting->getSetting(Colors::CODE));
			$this->cache->set(Colors::CACHE_KEY, $css);
		}

		return $css;
	}
}
