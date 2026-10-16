<?php
/*
	$Project: Ka Theme Colors $
	$Author: karapuz team <support@ka-station.com> $
	$Version: 1.0.0.0 $

	Settings page of the module. It is opened from the 'Ka Extensions' page.
*/

namespace Opencart\Admin\Controller\Extension\KaThemeColors;

use \Opencart\Extension\KaThemeColors\System\Colors;

class Extension extends \Opencart\Extension\KaExtensions\System\ControllerSettings {

	protected $ext_code = 'ka_theme_colors';

	/**
	 Fields for ControllerSettings: one field per color, default values are the OC4 default theme colors.
	*/
	protected function getFields() {
		$fields = array();
		foreach (Colors::getGroups() as $group => $colors) {
			foreach ($colors as $color => $default) {
				$code = Colors::CODE . '_' . $color;
				$fields[$code] = array(
					'code'          => $code,
					'type'          => 'color',
					'group'         => $group,
					'default_value' => $default,
				);
			}
		}
		return $fields;
	}


	public function index() {
		$groups = array();
		foreach (Colors::getGroups() as $group => $colors) {
			$groups[$group] = array_map(function($c) { return Colors::CODE . '_' . $c; }, array_keys($colors));
		}
		$this->data['color_groups'] = $groups;

		// the color picker cannot be empty, show the calculated color for 'auto' fields
		$primary = Colors::normalize($this->config->get('ka_theme_colors_primary')) ?: '#229ac8';
		$this->data['auto_values'] = array(
			'ka_theme_colors_primary_hover' => Colors::mix($primary, '#000000', 0.2),
		);

		$this->document->addScript('../extension/ka_theme_colors/admin/view/javascript/ka_theme_colors.js');

		parent::index();
	}


	/**
	 Replaces the standard Ka settings template with the module template (it extends the standard one).
	*/
	protected function showPage($template = '', $data = array()) {
		if ($template == 'extension/ka_extensions/common/pages/settings') {
			$template = 'extension/ka_theme_colors/extension';
		}
		parent::showPage($template, $data);
	}


	protected function validate() {

		if (!parent::validate()) {
			return false;
		}

		foreach ($this->fields as $code => $f) {
			$value = isset($this->request->post[$code]) ? trim($this->request->post[$code]) : '';

			if ($value === '' && $f['default_value'] === '') {
				continue; // automatic value
			}

			if (Colors::normalize($value) === '') {
				$this->errors[$code] = sprintf($this->language->get('error_color'), $this->language->get('txt_title_' . $code));
			}
		}

		return empty($this->errors);
	}


	public function save() {

		if (!empty($this->request->post) && is_array($this->request->post)) {
			foreach ($this->fields as $code => $f) {
				if (isset($this->request->post[$code])) {
					$normalized = Colors::normalize($this->request->post[$code]);
					if ($normalized !== '') {
						$this->request->post[$code] = $normalized;
					}
				}
			}
		}

		$result = parent::save();

		if ($result) {
			$this->rebuildCache();
		}

		return $result;
	}


	/**
	 Generates the CSS block from the saved settings and stores it in the OpenCart cache.
	 The storefront reads the block from the cache and regenerates it only when the cache is empty.
	*/
	protected function rebuildCache() {
		$this->cache->delete(Colors::CACHE_KEY);
		$settings = $this->model_setting_setting->getSetting(Colors::CODE);
		$this->cache->set(Colors::CACHE_KEY, Colors::buildCss($settings));
	}


	public function install($params) {

		if (!parent::install($params)) {
			return false;
		}

		$this->rebuildCache();

		return true;
	}


	public function uninstall($params) {

		if (!parent::uninstall($params)) {
			return false;
		}

		$this->cache->delete(Colors::CACHE_KEY);

		return true;
	}
}
