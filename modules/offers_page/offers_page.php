<?php

	if(!defined('_PS_VERSION_')) {
		exit;
	}

	class Offers_page extends Module {

		public function __construct() {

			$this->name = 'offers_page';
			$this->tab = 'front_office_features';
			$this->version = '1.0.0';
			$this->author = 'Gauthier Seyzeriat--Meyer';
			$this->need_instance = 0;
			$this->bootstrap = true;

			parent::__construct();
			
			$this->displayName = $this->l('Module de petites annonces de vinyles');
			$this->description = $this->l('Permet aux utilisateurs de publier des annonces pour acheter, vendre ou échanger des vinyles.');
		}

		public function install () {
			if (!parent::install() || 
				!$this->registerHook('displayTop') ) {
				return false;
			}
			return true;
		}

		public function uninstall() {

			Configuration::deleteByName('ALERT_EMAIL');

			return parent::uninstall();
			
		}

		public function hookDisplayTop() {
			

			$email = Configuration::get('ALERT_EMAIL');

			$this->context->smarty->assign(array(
				'email_restock'       => $email,
			));

			return $this->display(__FILE__, 'header.tpl');
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
