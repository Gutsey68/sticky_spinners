<?php

	if(!defined('_PS_VERSION_')) {
		exit;
	}

	require_once (_PS_MODULE_DIR_.'offers_page2/classes/Offers.php');

	class Offers_page2 extends Module {

		public function __construct() {

			$this->name = 'offers_page2';
			$this->tab = 'front_office_features';
			$this->version = '1.2.1';
			$this->author = 'Gauthier Seyzeriat--Meyer';
			$this->need_instance = 0;
			$this->bootstrap = true;

			parent::__construct();
			
			$this->displayName = $this->l('Module de petites annonces de vinyles');
			$this->description = $this->l('Permet aux utilisateurs de publier des annonces pour acheter, vendre ou échanger des vinyles.');
		}

		public function install () {
			if (!parent::install() || 
				!$this->createTable() ||
				!$this->addAdminMenu() ||
				!$this->registerHook('displayTop') ) {
				return false;
			}
			return true;
		}

		public function addAdminMenu() {

			$parent_tab = new Tab();
			$parent_tab->class_name = "AdminOffers";
			$parent_tab->module = $this->name;
			$parent_tab->id_parent = 0;
			$parent_tab->active = 1;

			foreach (Language::getlanguages(true) as $lang) {
				$parent_tab->name[$lang['id_lang']] = $this->l('Gérer les Annonces');
			}

			$parent_tab->save(); // save utilise le crud de presta sauf que soit il ajoute soit il met à jour 

			$tab = new Tab();
			$tab->class_name = "AdminOffersList";
			$tab->module = $this->name;
			$tab->id_parent = $parent_tab->id;
			$tab->active = 1;

			foreach (Language::getlanguages(true) as $lang) {
				$tab->name[$lang['id_lang']] = $this->l('Annonces');
			}

			$tab->save();

			return true;
		}

		private function createTable() {
			$sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "offers` (
				`id_offer` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_customer` int(11) NOT NULL,
				`title` VARCHAR(255) NOT NULL,
				`description` TEXT NOT NULL,
				`image` VARCHAR(255) NULL,
				`date_add` DATETIME NOT NULL,
				`valid` tinyint(1) NOT NULL,
				PRIMARY KEY (`id_offer`)
			) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

			$result = DB::getInstance()->execute($sql);
			
			if ($result) {
				return true;
			}
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
        
		public function getContent()
		{
			$output = null;
		
			if (Tools::isSubmit('submit'.$this->name)) {
				$someSetting = (bool)Tools::getValue('ENABLE_MODERATION');
		
				if (!Validate::isBool($someSetting)) {
					$output .= $this->displayError($this->l('Paramètres invalides'));
				} else {
					Configuration::updateValue('OFFERS_PAGE_ENABLE_MODERATION', $someSetting);
					$output .= $this->displayConfirmation($this->l('Paramètres mis à jour.'));
				}
			}
		
			return $output.$this->displayForm();
		}
		
		public function displayForm()
		{
			$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		
			$fields_form[0]['form'] = [
				'legend' => [
					'title' => $this->l('Settings'),
				],
				'input' => [
					[
						'type' => 'switch',
						'label' => $this->l('Activer la modération des annonces'),
						'name' => 'ENABLE_MODERATION',
						'is_bool' => true,
						'desc' => $this->l(' Lorsque la modération est activée, toutes les nouvelles annonces seront initialement marquées comme non valides et devront être approuvées manuellement. Lorsqu\'elle est désactivée, les nouvelles annonces seront automatiquement considérées comme valides et immédiatement visibles sur le site.'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Activé')
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Désactivé')
							]
						],
					],
				],
				'submit' => [
					'title' => $this->l('Save'),
					'class' => 'btn btn-default pull-right'
				]
			];
		
			$helper = new HelperForm();

			$helper->module = $this;
			$helper->token = Tools::getAdminTokenLite('AdminModules');
			$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
			$helper->title = $this->displayName;
			$helper->submit_action = 'submit'.$this-> name;
		
			$helper->fields_value['ENABLE_MODERATION'] = Configuration::get('OFFERS_PAGE_ENABLE_MODERATION');
		
			return $helper->generateForm($fields_form);
		}
		
	}
