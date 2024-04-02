<?php

	if(!defined('_PS_VERSION_')) {
		exit;
	}

	class Restock_alert extends Module {

		public function __construct() {

			$this->name = 'gauthier';
			$this->tab = 'front_office_features';
			$this->version = '1.0.0';
			$this->author = 'Gauthier';
			$this->need_instance = 0;
			$this->bootstrap = true;

			parent::__construct();
			
			$this->displayName = $this->l('Module d\'alerte de réassort');
			$this->description = $this->l('Permet aux clients d\'être informés lorsque des vinyles épuisés sont de nouveau disponibles');
		}

		public function install () {
			if (!parent::install() || 
				!$this->registerHook('displayProductActions') || 
				!$this->registerHook('actionFrontControllerSetMedia') ||
				!$this->createTable()) {
				return false;
			}
			return true;
		}

		public function uninstall() {

			Configuration::deleteByName('DEVIS_TITRE');
			Configuration::deleteByName('DEVIS_DESCRIPTION');
			Configuration::deleteByName('DEVIS_IMG');

			return parent::uninstall();
			
		}

		public function hookActionFrontControllerSetMedia() {
			
            // a changer ->
			// inclure fichier CSS 
			$this->context->controller->registerStylesheet(
				'module-gauthier-style' , // identifiant
				'modules/'.$this->name.'/views/assets/css/front.css' // chemin
			);


		}

		public function hookDisplayProductActions() {
			
            // a changer ->
			$id_product = Tools::getValue('id_product');
			$params = array (
				'id_product' => $id_product
			);
			

			$email = Configuration::get('ALERT_EMAIL');

			$this->context->smarty->assign(array(
				'email_restock'       => $email,
			));

			return $this->display(__FILE__, 'personnal-information.tpl');
		}
        
		public function getContent() {

			$output = null;

			if(Tools::isSubmit('submit'.$this-> name)) {

				$email = Tools::getValue('ALERT_EMAIL'); // récup l'e-mail

                if (!Validate::isEmail($email)) {
                    $output .= $this->displayError($this->l('L\'adresse e-mail n\'est pas valide.'));
                } else {
                    Configuration::UpdateValue('ALERT_EMAIL' , $email);
                    $output .= $this->displayConfirmation($this->l('Votre inscription a bien été prise en compte. Nous vous enverrons un e-mail dès que cet article sera de nouveau en stock !'));
                }
            }

            return $output . $this->displayForm();
		}

		public function displayForm() {

			$fieldsForm[0]['form'] = [
				'legend' => [
					'title' => $this->l('Alertes réassort'),
				],
				
				'input' => [ 
					[
                        'type'     => 'text',
                        'desc'     => $this->l('Entrez votre adresse e-mail pour être alerté lorsque le produit est de nouveau en stock.'),
                        'label'    => $this->l('Adresse e-mail'),
                        'name'     => 'ALERT_EMAIL',
                        'required' => true
                    ],
				],
				'submit' => [
					'title' => $this->l('M\'inscrire'),
					'class' => 'btn btn-default'
				],
			];

			$helper = new HelperForm();

			$helper->module = $this; 
			$helper->token = Tools::getAdminTokenLite('AdminModules');
			$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
			$helper->title = $this->displayName;
			$helper->submit_action = 'submit'.$this-> name;

			$helper->fields_value['ALERT_EMAIL'] = '';

			return $helper->generateForm($fieldsForm);
		}
	}
