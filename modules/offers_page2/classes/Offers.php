<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Représente les annonces spécifiques à chaque client dans la boutique.
 * Chaque offre est liée à un client et contient des informations telles que le titre, la description, une image, et des attributs d'état comme validité et activité.
 */

class Offers extends ObjectModel {
    
    public $id_offer;
    public $id_customer;
    public $title; 
    public $description; 
    public $image;
    public $date_add; 
    public $valid;
    public $active;
    
    /**
     * Définition des propriétés de l'offre avec validation et type spécifié pour l'utilisation avec ObjectModel.
     * Ceci inclut les types de données, les validations, et les contraintes comme la taille des champs et l'obligation.
     */
    public static $definition = array(

        'table' => 'offers',
        'primary' => 'id_offer',
        'fields' => array(          
            'id_customer' => array ('type'     => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'description' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'valid' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'active' => array ('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    /**
     * Récupère toutes les annonces valides.
     * Inclut le prénom du client lié à chaque offre dans les résultats.
     *
     * @return array Liste des offres valides avec des informations supplémentaires sur les clients associés.
     */
    public static function getValidOffers() {

        $context = Context::getContext();
    
        $offers = Db::getInstance()->executeS('
            SELECT o.*, c.firstname AS customer_firstname
            FROM '._DB_PREFIX_.'offers o
            LEFT JOIN '._DB_PREFIX_.'customer c ON c.id_customer = o.id_customer
            WHERE o.valid = 1
            ORDER BY date_add DESC');
    
        return $offers;
    }
    
}