<?php

	if(!defined('_PS_VERSION_')) {
		exit;
	}

	class Avis_produits extends Module {

		public function __construct() {

			$this-> name = 'avis_produits'; // nom technique, doit avoir le même nom que mon dossier, mon fichier et ma classe.
			$this-> tab = 'front_office_features';
			$this-> version = '1.0.0';
			$this-> author = 'Gauthier Seyzeriat--Meyer';
			$this-> need_instance = 0;
			$this-> bootstrap = true;

			parent::__construct(); // charge la function des traductions des chaines de caractère
			
			$this-> displayName = $this-> l('Avis produits'); // Nom public. La méthode l permet de rendre la chaine de texte traduisible.
			$this-> description = $this-> l('Permet aux client d\'évaluer des produits');
		}

		public function install () {
			if (!parent::install() || !$this->registerHook('displayRightColumnProduct') || !$this->registerHook('actionFrontControllerSetMedia')) {
				return false;
			}
			return true;
		}

		// methode qui s'execute à la désinstallation
		public function uninstall() {

			Configuration::deleteByName('AVIS_TITRE');
			Configuration::deleteByName('AVIS_DESCRIPTION');
			Configuration::deleteByName('AVIS_CANNOTE');
			Configuration::deleteByName('AVIS_ISCONNECTED');

			return parent::uninstall();
			
		}

		public function hookActionFrontControllerSetMedia() {
			
			// inclure fichier CSS 
			$this->context->controller->registerStylesheet(
				'module-avis-produit-style' , // identifiant
				'modules/'.$this->name.'/views/assets/css/front.css' // chemin
			);

			// inclure un fichier JS -> registerJavascript

		}

		public function hookDisplayRightColumnproduct() {

			$titre = Configuration::get('AVIS_TITRE');
			$description = Configuration::get('AVIS_DESCRIPTION');
			$canNote = Configuration::get('AVIS_CANNOTE');
			$isConnected = Configuration::get('AVIS_ISCONNECTED');

			$this->context->smarty->assign(array(
				'titreavis' => $titre,
				'descriptionavis' => $description,
				'canNoteAvis' => $canNote,
				'isConnectedAvis' => $isConnected
			));

			return $this->display(__FILE__, 'layout-both-columns.tpl');
		}

		// ajout de page de configuration
		public function getContent() {

			$output = null;

			// function isSubmit permet de tester si un formulaire est envoyé avec un paramètre, le name du boutton.
			if(Tools::isSubmit('submit' .$this-> name)) {

				$titre = Tools::getValue('AVIS_TITRE'); // getValue permet de récupérer des infos envoyées en get ou post 

				// Tools::dieObject($titre); pareil que le var_dump avec un die; ensuite. Pour continuer l'éxecution de la page, Tools::dieObject($var, false);

				$description = Tools::getValue('AVIS_DESCRIPTION');
				$isConnected = Tools::getValue('AVIS_ISCONNECTED');
				$canNote = Tools::getValue('AVIS_CANNOTE');

				// toutes les données d'un formulaire de configuration de module sont stockées dans la table configuration

				if (!$titre) {
					$output = $this-> displayError ($this->l('the title is required'));
				} else {
					Configuration::UpdateValue('AVIS_TITRE' , $titre);

					$output .= $this-> displayConfirmation($this-> l('Updated title'));
				}

				if(Validate::isInt(Tools::getValue('AVIS_ISCONNECTED'))) {
					Configuration::UpdateValue('AVIS_ISCONNECTED' , $isConnected);
					$output .= $this-> displayConfirmation($this-> l('Updated connecting settings'));
				}

				if(Validate::isInt(Tools::getValue('AVIS_CANNOTE'))) {
					Configuration::UpdateValue('AVIS_CANNOTE' , $canNote);
					$output .= $this-> displayConfirmation($this-> l('Updated notting settings'));
				}

				if (validate::isCleanHtml($description)) {

					Configuration::UpdateValue('AVIS_DESCRIPTION' , $description, true);

					$output .= $this-> displayConfirmation($this-> l('The description field is updated'));
				} else {
					$output .= $this-> displayError($this-> l('The description field isn\'t valid'));
				}

				/** 
				 *  la function updateValue permet d'insérer de nouvelles données ou de mettre à jour des données existantes. Le 1er paramètre est le name de la valeur stockées
				 *   et le 2eme sa valeur. Si le name existe pas dans la table alors il l'ajoute, sinon il le met à jour.
				 *  3eme paramètre facultatif pour accepter les balises html, de base sur false
				 */
				 
			}
		
			return $output . $this-> displayForm();
		}

		public function displayForm() {

			$fieldsForm[0]['form'] = [
				'legend' => [
					'title' => $this->l('Settings'),
				],
				
				'input' => [ 
					[
						'type'     => 'text', // type de champ (texte, checkbox, )
						'label'    => $this-> l('Titre'),
						'name'     => 'AVIS_TITRE',
						'required' => true // rend obligatoire un champ
					],
					[
						'type'     => 'radio', // type de champ (texte, checkbox, )
						'label'    => $this-> l('Connected'),
						'name'     => 'AVIS_ISCONNECTED',
						'values'   => array(
							array(
							  'id'    => 'active_on',                 
							  'value' => 1,                                 
							  'label' => $this->l('Yes')         
							),
							array(
							  'id'    => 'active_off',
							  'value' => 0,
							  'label' => $this->l('No')
							)
							),
					],
					[
						'type'     => 'radio', // type de champ (texte, checkbox, )
						'label'    => $this-> l('Note'),
						'name'     => 'AVIS_CANNOTE',
						'values'   => array(
							array(
							  'id'    => 'active_on',                 
							  'value' => 1,                                 
							  'label' => $this->l('Yes')         
							),
							array(
							  'id'    => 'active_off',
							  'value' => 0,
							  'label' => $this->l('No')
							)
							),
					],
					[
						'type'         => 'textarea',
						'label'        => $this-> l('Description'),
						'name'         => 'AVIS_DESCRIPTION',
						'autoload_rte' => true, // ajoute wysiwyg
					],
				],
				'submit' => [ // boutton de mon formulaire.
					'title' => $this-> l('Save'),
					'class' => 'btn btn-default pull-right'
				]
			];

			$helper = new HelperForm(); // helperform permet de générer la structure HTLM du formulaire.

			// hydratation
			$helper-> module = $this; // instance du module qui utilisera le formulaire. 
			$helper-> token = Tools::getAdminTokenLite('AdminModules'); // besoin d'un token pour executer le traitement du formulaire.
			$helper-> currentIndex = AdminController::$currentIndex. '&configure=' .$this-> name; // permet de générer le lien action du form.
			$helper-> title = $this-> displayName;
			$helper-> submit_action = 'submit' .$this-> name; // génère l'attribut name de mon boutton pour tester si un formulaire est envoyé.

			// On prérempli les champs avec les données déjà existantes dans la table de configuration
			$helper-> fields_value['AVIS_TITRE'] = Configuration::get('AVIS_TITRE');
			$helper-> fields_value['AVIS_DESCRIPTION'] = Configuration::get('AVIS_DESCRIPTION');
			$helper-> fields_value['AVIS_CANNOTE'] = Configuration::get('AVIS_CANNOTE');
			$helper-> fields_value['AVIS_ISCONNECTED'] = Configuration::get('AVIS_ISCONNECTED');

			return $helper-> generateForm($fieldsForm); // generateForm va générer la structure html de mon formulaire 
		}
	}
	

             
