<?php
/*
* Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/

class ModelShoputilsDesign extends Model {
    private $template;

    public function init($module_id, $advanced_settings = null) {
        $this->load->language('shoputils/design');

        $this->template = new Template();
        $this->template->data['module_id'] = $module_id;
        $this->template->data['tab_design'] = $this->language->get('tab_design');

        $this->template->data['entry_layout'] = $this->language->get('entry_layout');
        $this->template->data['entry_position'] = $this->language->get('entry_position');
        $this->template->data['entry_status'] = $this->language->get('entry_status');
        $this->template->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->template->data['entry_settings'] = $this->language->get('entry_settings');

        $this->template->data['text_content_top'] = $this->language->get('text_content_top');
        $this->template->data['text_content_bottom'] = $this->language->get('text_content_bottom');
        $this->template->data['text_column_left'] = $this->language->get('text_column_left');
        $this->template->data['text_column_right'] = $this->language->get('text_column_right');
        $this->template->data['text_enabled'] = $this->language->get('text_enabled');
        $this->template->data['text_disabled'] = $this->language->get('text_disabled');
        $this->template->data['text_default_parameters'] = $this->language->get('text_default_parameters');
        $this->template->data['text_default_parameters_help'] = $this->language->get('text_default_parameters_help');

        $this->template->data['button_add_module'] = $this->language->get('button_add_module');
        $this->template->data['button_remove'] = $this->language->get('button_remove');
        $this->template->data['token'] = $this->session->data['token'];
        $this->template->data['advanced_settings'] = $advanced_settings;

        $this->load->model('design/layout');
        $this->template->data['layouts'] = $this->model_design_layout->getLayouts();


        if (isset($this->request->post[$module_id . '_module'])) {
            $this->template->data['modules'] = $this->request->post[$module_id . '_module'];
        } else {
            $this->template->data['modules'] = $this->config->get($module_id . '_module');
        }

        if (!isset($this->template->data['modules']) || !is_array($this->template->data['modules'])) {
            $this->template->data['modules'] = array();
        }

        $this->template->data['module_row'] = count($this->template->data['modules']);

    }

    public function normalizePost($module_id) {
        if (isset($this->request->post[$module_id . '_module'])) {
            $result = array();
            foreach ($this->request->post[$module_id . '_module'] as $settings) {
                $result[] = $settings;
            }
            $this->request->post[$module_id . '_module'] = $result;
        }
    }

    public function getDesignTab() {
        return $this->template->fetch('shoputils/design_tab.tpl');
    }

    public function getDesignValues() {
        return $this->template->fetch('shoputils/design_values.tpl');
    }

    public function getDesignScript() {
        return $this->template->fetch('shoputils/design_script.tpl');
    }
}
?>