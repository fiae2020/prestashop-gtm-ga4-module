<?php
/**
 * Google Tag Manager Module for PrestaShop 8.x
 *
 * @author    Dzemal Imamovic
 * @copyright 2025 Dzemal Imamovic
 * @license   MIT
 * e-mail me for help : dzemal.imamovic@outlook.com
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class GtmModule extends Module
{
    public function __construct()
    {
        $this->name = 'gtmmodule';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.1';
        $this->author = 'Dzemal Imamovic';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.1.0',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Google Tag Manager with GA4');
        $this->description = $this->l('Integrates Google Tag Manager with automatic GA4 configuration when GA4 ID is provided');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this tracking module?');
    }

    /**
     * Install module
     *
     * @return bool
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        Configuration::updateValue('GTM_CONTAINER_ID', '');
        Configuration::updateValue('GA4_MEASUREMENT_ID', '');

        if (!$this->registerHook('displayHeader') || !$this->registerHook('displayAfterBodyOpeningTag')) {
            return false;
        }

        return true;
    }

    /**
     * Uninstall module
     *
     * @return bool
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        Configuration::deleteByName('GTM_CONTAINER_ID');
        Configuration::deleteByName('GA4_MEASUREMENT_ID');

        return true;
    }

    /**
     * Configuration form
     *
     * @return string
     */
    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $gtmId = (string) Tools::getValue('GTM_CONTAINER_ID');
            $ga4Id = (string) Tools::getValue('GA4_MEASUREMENT_ID');

            // Validate GTM Container ID
            if (empty($gtmId) || !$this->validateGtmId($gtmId)) {
                $output .= $this->displayError($this->l('Invalid GTM Container ID. Please enter a valid GTM Container ID (GTM-XXXXXXX).'));
            } else {
                Configuration::updateValue('GTM_CONTAINER_ID', $gtmId);

                // Validate GA4 ID if provided
                if (!empty($ga4Id)) {
                    if (!$this->validateGa4Id($ga4Id)) {
                        $output .= $this->displayError($this->l('Invalid GA4 Measurement ID. Please enter a valid GA4 ID (G-XXXXXXXXXX) or leave empty.'));
                    } else {
                        Configuration::updateValue('GA4_MEASUREMENT_ID', $ga4Id);
                        $output .= $this->displayConfirmation($this->l('Settings updated. GA4 tracking will be configured inside GTM.'));
                    }
                } else {
                    Configuration::updateValue('GA4_MEASUREMENT_ID', '');
                    $output .= $this->displayConfirmation($this->l('Settings updated. Only GTM container loaded.'));
                }
            }
        }

        return $output . $this->displayForm();
    }

    /**
     * Validate GTM Container ID format
     *
     * @param string $gtmId
     *
     * @return bool
     */
    private function validateGtmId($gtmId)
    {
        return preg_match('/^GTM-[A-Z0-9]+$/', $gtmId);
    }

    /**
     * Validate GA4 Measurement ID format
     *
     * @param string $ga4Id
     *
     * @return bool
     */
    private function validateGa4Id($ga4Id)
    {
        return preg_match('/^G-[A-Z0-9]+$/', $ga4Id);
    }

    /**
     * Display configuration form
     *
     * @return string
     */
    public function displayForm()
    {
        // Get default language
        $defaultLang = (int) Configuration::get('PS_LANG_DEFAULT');

        // Info message
        $infoMessage = $this->l('This module loads GTM container and automatically configures GA4 inside GTM when GA4 ID is provided. This is the recommended approach for proper analytics implementation.');

        // Init Fields form array
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Google Tag Manager Configuration'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('GTM Container ID'),
                    'name' => 'GTM_CONTAINER_ID',
                    'size' => 20,
                    'required' => true,
                    'desc' => $this->l('Enter your GTM Container ID (e.g., GTM-XXXXXXX) - Required'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('GA4 Measurement ID'),
                    'name' => 'GA4_MEASUREMENT_ID',
                    'size' => 20,
                    'required' => false,
                    'desc' => $this->l('Enter your GA4 Measurement ID (e.g., G-XXXXXXXXXX) - Optional. Will be configured inside GTM automatically.'),
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list'),
            ],
        ];

        // Load current values
        $helper->fields_value['GTM_CONTAINER_ID'] = Configuration::get('GTM_CONTAINER_ID');
        $helper->fields_value['GA4_MEASUREMENT_ID'] = Configuration::get('GA4_MEASUREMENT_ID');

        $html = $this->display(__FILE__, 'views/templates/admin/config_info.tpl');

        return $helper->generateForm($fieldsForm) . $html;
    }

    /**
     * Hook display header
     *
     * @return string
     */
    public function hookDisplayHeader()
    {
        $gtmId = Configuration::get('GTM_CONTAINER_ID');
        $ga4Id = Configuration::get('GA4_MEASUREMENT_ID');

        if (empty($gtmId)) {
            return '';
        }

        $this->context->smarty->assign([
            'gtm_container_id' => $gtmId,
            'ga4_measurement_id' => $ga4Id,
            'has_ga4' => !empty($ga4Id),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/gtm_header.tpl');
    }

    /**
     * Hook display after body opening tag
     *
     * @return string
     */
    public function hookDisplayAfterBodyOpeningTag()
    {
        $gtmId = Configuration::get('GTM_CONTAINER_ID');
        if (empty($gtmId)) {
            return '';
        }

        $this->context->smarty->assign([
            'gtm_container_id' => $gtmId,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/gtm_body.tpl');
    }
}
