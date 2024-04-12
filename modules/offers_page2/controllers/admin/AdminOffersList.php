<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (_PS_MODULE_DIR_.'offers_page2/classes/Offers.php');

class AdminOffersListController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'offers';
        $this->className = 'Offers';
        $this->identifier = 'id_offer';
        $this->name = 'offers_page2'; 
        $this->bootstrap = true;

		$this->context = Context::getContext();


		parent::__construct();

		}

        public function renderList() {
            $this->fields_list = array(
                'id_offer' => array(
                    'title' => $this->l('ID'),
                    'align' => 'center',
                    'class' => 'fixed-width-xs'
                ),
                'image' => array(
                    'title' => $this->l('Image'),
                    'align' => 'center',
                    'callback' => 'displayImage',
                    'orderby' => false,
                    'search' => false,
                    'class' => 'fixed-width-xs'
                ),
                
                'title' => array(
                    'title' => $this->l('Titre'),
                ),
                'description' => array(
                    'title' => $this->l('Description'),
                    'callback' => 'getDescriptionSnippet'
                ),
                'valid' => array(
                    'title' => $this->l('Validé'),
                    'valid' => 'status',
                    'type' => 'bool',
                    'align' => 'center',
                    'class' => 'fixed-width-sm'
                ),
                'date_add' => array(
                    'title' => $this->l('Date d\'ajout'),
                    'type' => 'date',
                    'align' => 'right'
                ),
            );
        
            $this->addRowAction('edit');
            $this->addRowAction('delete');
            $this->addRowAction('details');
        
            return parent::renderList();
        }
        
        public function getDescriptionSnippet($description, $row)
        {
            return Tools::substr($description, 0, 75) . '...';
        }
        

        public function renderForm() {

            
            $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Annonce'),
                    'icon' => 'icon-briefcase'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Titre'),
                        'name' => 'title',
                        'required' => true
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Description'),
                        'name' => 'description',
                        'required' => true
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Validé'),
                        'name' => 'valid',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Activé')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Désactivé')
                            )
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Sauvegarder'),
                ),
            );
        
            return parent::renderForm();
        }
        
        public function displayImage($image, $data)
        {
            if (isset($image) && $image != '') {
                return '<img src="../modules/' . $this->module->name . '/views/img/' . $image . '" style="width:100px;height:auto;">';
            }
            return '-';
        }


}
