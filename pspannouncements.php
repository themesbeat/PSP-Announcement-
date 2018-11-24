<?php
/**
 * 2017-2018 PrestaPatron
 *
 * PrestaPatron Announcement Block
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    PrestaPatron
 *  @copyright 2017-2018 PrestaPatron
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
  exit;
}

class Pspannouncements extends Module {
	public function __construct() {
		$this->name = 'pspannouncements';
	    $this->tab = 'front_office_features';
	    $this->version = '1.0.0';
	    $this->author = 'Presta Patron';
	    $this->need_instance = 0;
	    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_); 
	    $this->bootstrap = true;
	 
	    parent::__construct();
	 
	    $this->displayName = $this->l('PSP Announcement');
	    $this->description = $this->l('Displays a announcement banner at the top of your store.');
	}


	public function install(){
		if (Shop::isFeatureActive()) {
		    Shop::setContext(Shop::CONTEXT_ALL);
		}

		$announcementsettings = $this->getModuleannouncementSettings();

		foreach ($announcementsettings as $name => $value) {
            Configuration::updateValue($name, $value);
        }		
	

		return parent::install() && $this->registerHook('displayAfterBodyOpeningTag') && $this->registerHook('displayBanner') && $this->registerHook('header');
	}


	public function uninstall() {
		$announcementsettings = $this->getModuleannouncementSettings();

        foreach (array_keys($announcementsettings) as $name) {
            Configuration::deleteByName($name);
        }
		
		return parent::uninstall();
	}

	protected function getModuleannouncementSettings() {
        $announcementsettings = array(
        	'ENABLE_ANNOUNCEMENT' => 0,
        	'ANNOUNCEMENT_TYPE' => 1,
            'ANNOUNCEMENT_START_DATE' => '2018/11/15',
            'ANNOUNCEMENT_END_DATE' => '2028/1/1',
            'ANNOUNCEMENT_CODE' => 'CYBERMONDAY',
            'ANNOUNCEMENT_TEXT' => 'Exclusive offer - Get 10% off on all our products',
            'ANNOUNCEMENT_LINK' => 'https://www.prestashop.com',
            'ANNOUNCEMENT_LINK_TEXT' => 'Click Here',

            'BANNER_BG_COLOR' => '#cf476b',
            'TEXT_COLOR' => '#ffffff',
            'BUTTON_BG_COLOR' => '#1f294c',
            'BUTTON_TEXT_COLOR' => '#ffffff',
        );
        return $announcementsettings;
    }


    public function getContent() {
    	 $output = null;

    	 if (Tools::isSubmit('submit'.$this->name))
	    {	

	    	$enable_announcement = Tools::getValue('ENABLE_ANNOUNCEMENT');
	    	$announcement_type = Tools::getValue('ANNOUNCEMENT_TYPE');
	        $announcement_start_date = Tools::getValue('ANNOUNCEMENT_START_DATE');
	        $announcement_end_date = Tools::getValue('ANNOUNCEMENT_END_DATE');
	        $announcement_code = Tools::getValue('ANNOUNCEMENT_CODE');
	        $announcement_text = Tools::getValue('ANNOUNCEMENT_TEXT');
	        $announcement_link = Tools::getValue('ANNOUNCEMENT_LINK');
	        $announcement_link_text = Tools::getValue('ANNOUNCEMENT_LINK_TEXT');

	        $banner_bg_color = Tools::getValue('BANNER_BG_COLOR');
	        $text_color = Tools::getValue('TEXT_COLOR');
	        $button_bg_color = Tools::getValue('BUTTON_BG_COLOR');
	        $button_text_color = Tools::getValue('BUTTON_TEXT_COLOR');
	       	
	        if (!$announcement_type && empty($announcement_text))
	         
	            $output .= $this->displayError($this->l('Announcement Text is Required'));
	        else
	        {
	        	Configuration::updateValue('ANNOUNCEMENT_TYPE', $announcement_type);
	        	Configuration::updateValue('ENABLE_ANNOUNCEMENT', $enable_announcement);
	            Configuration::updateValue('ANNOUNCEMENT_START_DATE', $announcement_start_date);
	            Configuration::updateValue('ANNOUNCEMENT_END_DATE', $announcement_end_date);	            
	            Configuration::updateValue('ANNOUNCEMENT_CODE', $announcement_code);
	            Configuration::updateValue('ANNOUNCEMENT_TEXT', $announcement_text);
	            Configuration::updateValue('ANNOUNCEMENT_LINK', $announcement_link);
	            Configuration::updateValue('ANNOUNCEMENT_LINK_TEXT', $announcement_link_text);

	             Configuration::updateValue('BANNER_BG_COLOR', $banner_bg_color);
	             Configuration::updateValue('TEXT_COLOR', $text_color);
	             Configuration::updateValue('BUTTON_BG_COLOR', $button_bg_color);
	             Configuration::updateValue('BUTTON_TEXT_COLOR', $button_text_color);
	            

	            $output .= $this->displayConfirmation($this->l('Announcement Settings Updated'));
	        }
	    }
	    return $output.$this->renderForm();
    }

    public function renderForm() {
    	// Get default language
	    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
	    $options = array(
				  array(
				    'id_option' => 1,  
				    'name' => 'Simple' 
				  ),
				  array(
				    'id_option' => 2,
				    'name' => 'Countdown'
				  ),
				  array(
				    'id_option' => 3,
				    'name' => 'Countdown with Coupon'
				  ),
				);
	     
	    // Init Fields form array
	    $fields_form[0]['form'] = array(
	        'legend' => array(
	        'title' => $this->l('Announcement Settings'),
	        ),
	        'input' => array(
	        	array(
                    'type' => 'switch',
                    'label' => $this->l('Add Announcement:'),
                    'desc' => $this->l('Enable to add Announcement bar'),
                    'name' => 'ENABLE_ANNOUNCEMENT',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
				  'type' => 'select',
				  'label' => $this->l('Announcement Type:'),  
				  'desc' => $this->l('Choose an announcement type'), 
				  'name' => 'ANNOUNCEMENT_TYPE', 
				  'required' => true, 
				  'options' => array(
				    'query' => $options,  
				    'id' => 'id_option',  
				    'name' => 'name'                       
				  )				  
				),				
	        	array(
	                'type' => 'date',
	                'label' => $this->l('Start Date:'),
	                'name' => 'ANNOUNCEMENT_START_DATE',
                    'desc' => $this->l('Select Announcement start date'),

	                'size' => 10,							
	            ),

	            array(
	                'type' => 'date',
	                'label' => $this->l('End Date:'),
	                'name' => 'ANNOUNCEMENT_END_DATE',
                    'desc' => $this->l('Select Announcement end date'),

	                'size' => 10,
	            ),

	            array(
	                'type' => 'text',
	                'label' => $this->l('Coupon Code:'),
                    'desc' => $this->l('Enter the Coupon code here'),
	                'name' => 'ANNOUNCEMENT_CODE',
	                'size' => 20,	
	            ),


	            array(
	                'type' => 'text',
	                'label' => $this->l('Announcement Text'),
	                'desc' => $this->l('Enter the Announcement Text here'),
	                'name' => 'ANNOUNCEMENT_TEXT',
	                'size' => 20,
	                'required' => true,	                
	            ),
	            array(
	                'type' => 'text',
	                'label' => $this->l('Announcement Link'),
	                'desc' => $this->l('Enter the link of Announcement, if any'),
	                'name' => 'ANNOUNCEMENT_LINK',
	                'size' => 20,	                
	            ),

	            array(
	                'type' => 'text',
	                'label' => $this->l('Announcement Link Text'),
	                'desc' => $this->l('Enter the text for  link of Announcement, if any'),
	                'name' => 'ANNOUNCEMENT_LINK_TEXT',
	                'size' => 20,	                
	            ),

	        ),
	        'submit' => array(
	            'title' => $this->l('Save'),
	            'class' => 'btn btn-default pull-right'
	        )
	    );

	    $fields_form[1]['form'] = array(
	        'legend' => array(
	        'title' => $this->l('Design Settings'),
	        ),
	        'input' => array(
	        	
	            array(
	                'type' => 'color',
	                'label' => $this->l('Banner Background'),
	                'name' => 'BANNER_BG_COLOR',
	                'size' => 20,	                
	            ),

	            array(
	                'type' => 'color',
	                'label' => $this->l('Banner Text Color'),
	                'name' => 'TEXT_COLOR',
	                'size' => 20,	                
	            ),

	            array(
	                'type' => 'color',
	                'label' => $this->l('Button Background Color'),
	                'name' => 'BUTTON_BG_COLOR',
	                'size' => 20,	                
	            ),
	            array(
	                'type' => 'color',
	                'label' => $this->l('Button Background Color'),
	                'name' => 'BUTTON_TEXT_COLOR',
	                'size' => 20,	                
	            ),

	        ),
	        'submit' => array(
	            'title' => $this->l('Save'),
	            'class' => 'btn btn-default pull-right'
	        )
	    );
	     
	    $helper = new HelperForm();
	     
	    // Module, token and currentIndex
	    $helper->module = $this;
	    $helper->name_controller = $this->name;
	    $helper->token = Tools::getAdminTokenLite('AdminModules');
	    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
	     
	    // Language
	    $helper->default_form_language = $default_lang;
	    $helper->allow_employee_form_lang = $default_lang;
	     
	    // Title and toolbar
	    $helper->title = $this->displayName;
	    $helper->show_toolbar = true;        // false -> remove toolbar
	    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
	    $helper->submit_action = 'submit'.$this->name;
	    $helper->toolbar_btn = array(
	        'save' =>
	        array(
	            'desc' => $this->l('Save'),
	            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
	            '&token='.Tools::getAdminTokenLite('AdminModules'),
	        ),
	        'back' => array(
	            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
	            'desc' => $this->l('Back to list')
	        )
	    );
	     
	    // Load current value
	    $helper->fields_value['ENABLE_ANNOUNCEMENT'] = Configuration::get('ENABLE_ANNOUNCEMENT');
	    $helper->fields_value['ANNOUNCEMENT_TYPE'] = Configuration::get('ANNOUNCEMENT_TYPE');
	    $helper->fields_value['ANNOUNCEMENT_CODE'] = Configuration::get('ANNOUNCEMENT_CODE');
	    $helper->fields_value['ANNOUNCEMENT_START_DATE'] = Configuration::get('ANNOUNCEMENT_START_DATE');
	     $helper->fields_value['ANNOUNCEMENT_END_DATE'] = Configuration::get('ANNOUNCEMENT_END_DATE');
	    $helper->fields_value['ANNOUNCEMENT_LINK'] = Configuration::get('ANNOUNCEMENT_LINK');
	    $helper->fields_value['ANNOUNCEMENT_TEXT'] = Configuration::get('ANNOUNCEMENT_TEXT');
	    $helper->fields_value['ANNOUNCEMENT_LINK_TEXT'] = Configuration::get('ANNOUNCEMENT_LINK_TEXT');

	    $helper->fields_value['BANNER_BG_COLOR'] = Configuration::get('BANNER_BG_COLOR');
	    $helper->fields_value['TEXT_COLOR'] = Configuration::get('TEXT_COLOR');
	    $helper->fields_value['BUTTON_BG_COLOR'] = Configuration::get('BUTTON_BG_COLOR');
	    $helper->fields_value['BUTTON_TEXT_COLOR'] = Configuration::get('BUTTON_TEXT_COLOR');
	   	     
	    return $helper->generateForm($fields_form);
    }


    public function hookHeader($params) {
    	$this->context->controller->addCSS($this->_path.'views/css/pspannouncement.css');
	
		$this->context->controller->addJS($this->_path.'views/js/pspannouncement.js');

		$this->context->controller->addJS($this->_path.'views/js/countdown.js');
    }


    public function hookDisplayBanner($params) {
    	$this->smarty->assign(array(

    		'enable_announcement' => Configuration::get('ENABLE_ANNOUNCEMENT'),
    		'announcement_type' => Configuration::get('ANNOUNCEMENT_TYPE'),	

			'announcement_code' => Configuration::get('ANNOUNCEMENT_CODE'),		
			'announcement_start_date' => Configuration::get('ANNOUNCEMENT_START_DATE'),	
			'announcement_end_date' => Configuration::get('ANNOUNCEMENT_END_DATE'),	
			'announcement_link' => Configuration::get('ANNOUNCEMENT_LINK'),
			'announcement_text' => Configuration::get('ANNOUNCEMENT_TEXT'),
			'announcement_link_text' => Configuration::get('ANNOUNCEMENT_LINK_TEXT'),


			'banner_bg_color' => Configuration::get('BANNER_BG_COLOR'),	
			'text_color' => Configuration::get('TEXT_COLOR'),
			'button_bg_color' => Configuration::get('BUTTON_BG_COLOR'),
			'button_text_color' => Configuration::get('BUTTON_TEXT_COLOR'),

			
		));
    	
		return $this->display(__FILE__, '/views/templates/hook/pspannouncment-front.tpl');
    }

    public function hookDisplayNav1($params){
	  return $this->hookDisplayBanner($params);
	}


	public function hookDisplayAfterBodyOpeningTag($params) {
		return $this->hookDisplayBanner($params);
	}

	protected function getAnnouncementSettings()
    {
        $settings = $this->getModuleSettings();
        $get_settings = array();
        foreach (array_keys($settings) as $name) {
            $data = Configuration::get($name);
            $get_settings[$name] = array('value' => $data, 'type' => $this->getStringValueType($data));
        }

        return $get_settings;
    }

    protected function getStringValueType($string)
    {
        if (Validate::isInt($string)) {
            return 'int';
        } elseif (Validate::isFloat($string)) {
            return 'float';
        } elseif (Validate::isBool($string)) {
            return 'bool';
        } else {
            return 'string';
        }
    }


}
