<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Offer extends ObjectModel
{
    /** Définissez ici les propriétés de votre annonce. */
    public $id_offer;       // L'identifiant de l'annonce
    public $title;          // Le titre de l'annonce
    public $description;    // La description de l'annonce
    public $image;          // Le chemin de l'image de l'annonce
    public $date_add;       // La date de création de l'annonce
    public $date_upd;       // La date de dernière mise à jour de l'annonce

    /** La définition de la table et des champs pour ObjectModel */
    public static $definition = array(
        'table' => 'offers',         // Le nom de la table (sans le préfixe)
        'primary' => 'id_offer',     // La clé primaire
        'multilang' => false,        // Activer si votre entité doit gérer les multilangues
        'fields' => array(           // Définir ici les champs
            // Champs standards
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'description' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
        ),
    );

    /** 
     * Vous pouvez ajouter des méthodes personnalisées ici si nécessaire, 
     * par exemple pour des requêtes spécifiques à la base de données.
     */
}
