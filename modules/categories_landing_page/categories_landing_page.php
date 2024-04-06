<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Categories_landing_page extends Module {

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

    public function install() {
        if (
            !parent::install() ||
            !$this->registerHook('displayHome')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall() {

        Configuration::deleteByName('ALERT_EMAIL');

        return parent::uninstall();
    }

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
    
    
    

    public function getContent() {
        
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
    
            $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
        }
        return $this->_html.$this->displayForm();
    }
    

    protected function displayForm() {

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->submit_action = 'submit' . $this->name;
        $helper->default_form_language = $default_lang;

        $categories = Category::getCategories($default_lang, true, true);
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
                'desc' => $this->l('Choose a category to display.'),
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
