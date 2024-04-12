<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Offers extends ObjectModel
{
    public $id_offer;
    public $id_customer;
    public $title; 
    public $description; 
    public $image;
    public $date_add; 
    public $valid;
    public $active;
    
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

    public static function getOffersByCustomer($id_customer) {

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('offers', 'o');
        $sql->where('o.id_customer = '.(int)$id_customer);
        $sql->orderBy('o.date_add DESC');
    
        $results = Db::getInstance()->executeS($sql);
    
        return $results ? $results : [];
    }
    
    public static function getValidOffers()
    {
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