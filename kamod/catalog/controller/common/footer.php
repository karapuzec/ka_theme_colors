<?php
/*
	$Project: Ka Theme Colors $
	$Author: karapuz team <support@ka-station.com> $
	$Version: 1.1.0.0 $

	Adds the custom JavaScript of the module to the footer template data. The code is printed before </body>
	(after jQuery, Bootstrap and the page scripts) by the kapatch file kamod/catalog/view/template/common/footer.twig.xml.
*/

namespace Opencart\Catalog\Controller\Extension\KaThemeColors\Common;

use \Opencart\Extension\KaThemeColors\System\CustomCode;

class Footer extends \Opencart\Catalog\Controller\Common\Footer {

	use \Opencart\Extension\KaExtensions\System\TraitController;

	public function index(): string {

		$this->disableRender();
		parent::index();
		$this->enableRender();

		$data     = $this->getRenderData();
		$template = $this->getRenderTemplate();

		$data['ka_theme_custom_js'] = CustomCode::prepare($this->config->get(CustomCode::JS), 'js');

		return $this->load->view($template, $data);
	}
}
