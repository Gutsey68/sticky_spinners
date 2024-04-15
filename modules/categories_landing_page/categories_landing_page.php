<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Categories_landing_page extends Module {

    /**
     * Constructeur du module Categories_landing_page.
     * Initialise les propriétés du module, telles que le nom, la catégorie, la version, l'auteur, ainsi que les paramètres pour l'utilisation de Bootstrap et l'intégration au front office.
     */
    public function __construct() {

        $this->name = 'categories_landing_page';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Gauthier Seyzeriat--Meyer';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Module d\'affichage des catégories sur la page d\'acceuil');
        $this->description = $this->l('Affiche une sélection de catégories sur la page d’accueil.');
    }

    /**
     * Installe le module en enregistrant les hooks nécessaires et en effectuant les configurations initiales.
     * Échoue et retourne false si l'installation du parent ou l'enregistrement du hook échoue.
     *
     * @return bool Retourne true si l'installation est réussie, sinon false.
     */
    public function install() {
        if (
            !parent::install() ||
            !$this->registerHook('displayHome')
        ) {
            return false;
        }
        return true;
    }

    /**
     * Désinstalle le module en supprimant les configurations spécifiques au module.
     * Supprime notamment la configuration ALERT_EMAIL avant de procéder à la désinstallation parente.
     *
     * @return bool Retourne true si la désinstallation est réussie, sinon false.
     */
    public function uninstall() {

        Configuration::deleteByName('ALERT_EMAIL');

        return parent::uninstall();
    }

    /**
     * Hook qui s'exécute sur la page d'accueil pour afficher les catégories sélectionnées.
     * Récupère jusqu'à trois catégories configurées pour l'affichage et les assigne au template Smarty pour l'affichage.
     *
     * @param array $params Paramètres fournis par le hook, utilisés pour adapter le comportement du module.
     * @return string Le contenu HTML généré par le template Smarty.
     */
    public function hookDisplayHome($params)
    {
        $context = Context::getContext();
        $link = $context->link;
        $id_lang = $context->language->id;
    
        $categoriesToShow = [];
    
        for ($i = 1; $i <= 3; $i++) {
            $categoryId = Configuration::get('HOME_CATEGORY_' . $i);
            if ($categoryId) {
                $category = new Category((int)$categoryId, $id_lang);
                if (Validate::isLoadedObject($category) && $category->active) {
                    
                    $coverImageId = $category->id_image;
                    
                    if ($coverImageId) {
                        $imagePath = $context->link->getCatImageLink($category->link_rewrite, $category->id_image, 'medium_default');
                    } else {
                        $imagePath = '';
                    }
    
                    $categoriesToShow[] = [
                        'name' => $category->name,
                        'description' => $category->description,
                        'link' => $link->getCategoryLink($category),
                        'image' => $imagePath,
                    ];
                }
            }
        }
    
        $context->smarty->assign([
            'categories' => $categoriesToShow,
        ]);
    
        return $this->display(__FILE__, 'views/templates/hook/index.tpl');
    }
    
    /**
     * Gère la soumission du formulaire de configuration du module dans l'interface d'administration.
     * Met à jour les valeurs pour jusqu'à trois catégories choisies pour être affichées sur la page d'accueil.
     * Sauvegarde les configurations et affiche un message de confirmation après la mise à jour.
     *
     * @return string HTML du formulaire de configuration complété par les messages de confirmation.
     */
    public function getContent() {
        
        $output = null;

        if (((bool)Tools::isSubmit('submit'.$this->name)) == true) {
           
            for ($i = 1; $i <= 3; $i++) {
                $configName = 'HOME_CATEGORY_' . $i;
                $categoryValue = (string)Tools::getValue($configName);
                if (!empty($categoryValue)) {
                    Configuration::updateValue($configName, $categoryValue);
                } else {
                    Configuration::updateValue($configName, '');
                }
            }
    
            $output .= $this->displayConfirmation($this->l('Modifications enregistrées'));
        }
        return $output.$this-> displayForm();
    }
    
    /**
     * Génère et affiche le formulaire de configuration pour le module dans l'interface d'administration.
     * Construit dynamiquement les options de sélection des catégories basées sur les catégories disponibles.
     *
     * @return string Le HTML du formulaire généré pour la configuration des catégories.
     */
    protected function displayForm() {

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->submit_action = 'submit' . $this->name;

        $categories = Category::getCategories(false, true, true);
        $options_categories = [];
        foreach ($categories as $category) {
            foreach ($category as $key => $value) {
                if (!is_array($value)) {
                    continue;
                }
                foreach ($value as $subcategory) {
                    $options_categories[] = ['id_option' => $subcategory['id_category'], 'name' => $subcategory['name']];
                }
            }
        }

        $fields_form = [];
        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];

        for ($i = 1; $i <= 3; $i++) {
            $fields_form[0]['form']['input'][] = [
                'type' => 'select',
                'label' => $this->l('Category ') . $i,
                'desc' => $this->l('Choisissez une catégorie à afficher.'),
                'name' => 'HOME_CATEGORY_' . $i,
                'required' => false,
                'options' => [
                    'query' => $options_categories,
                    'id' => 'id_option',
                    'name' => 'name'
                ],
            ];
        }

        $helper->fields_value = [];
        for ($i = 1; $i <= 3; $i++) {
            $helper->fields_value['HOME_CATEGORY_' . $i] = Configuration::get('HOME_CATEGORY_' . $i);
        }

        return $helper->generateForm($fields_form);
    }
}
