<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (_PS_MODULE_DIR_.'offers_page2/classes/Offers.php');

class AdminOffersListController extends ModuleAdminController {

    /**
     * Constructeur pour le contrôleur AdminOffersListController.
     * Configure les propriétés nécessaires pour gérer la liste des offres dans le back-office, notamment la table, la classe, l'identifiant, et autres propriétés du contrôleur.
     */
    public function __construct() {

        $this->table = 'offers';
        $this->className = 'Offers';
        $this->identifier = 'id_offer';
        $this->name = 'offers_page2'; 
        $this->bootstrap = true;

		$this->context = Context::getContext();

		parent::__construct();

    }

    /**
     * Prépare et rend la liste des offres dans le back-office.
     * Configure les colonnes à afficher, les actions disponibles pour chaque offre (éditer, supprimer), et d'autres paramètres d'affichage de la liste.
     *
     * @return string Le HTML de la liste généré par le parent.
     */
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
    
        return parent::renderList();
    }
    
    /**
     * Fonction de rappel pour tronquer et afficher un extrait de la description d'une offre dans la liste.
     * Affiche les 120 premiers caractères de la description suivis de points de suspension.
     *
     * @param string $description Description complète de l'offre.
     * @param array $row Les données complètes de l'offre.
     * @return string Un extrait de la description.
     */
    public function getDescriptionSnippet($description, $row) {
        return Tools::substr($description, 0, 120) . '...';
    }

    /**
     * Prépare et rend le formulaire de création ou d'édition d'une offre.
     * Configure les champs du formulaire, y compris les champs pour le titre, la description, et la validation de l'offre.
     *
     * @return string Le HTML du formulaire généré par le parent.
     */
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
                            'label' => $this->l('Oui')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Non')
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
    
    /**
     * Fonction de rappel pour afficher une image dans la liste des offres.
     * Affiche une image à partir du dossier du module si elle existe, autrement retourne un tiret.
     *
     * @param string $image Nom du fichier de l'image.
     * @param array $data Les données complètes de l'offre associée à l'image.
     * @return string HTML pour l'image ou un tiret si aucune image n'est disponible.
     */
    public function displayImage($image, $data) {

        if (isset($image) && $image != '') {
            return '<img src="../modules/' . $this->module->name . '/views/img/' . $image . '" style="width:100px;height:auto;">';
        }
        return '-';
    }
}
