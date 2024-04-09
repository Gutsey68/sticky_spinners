<?php

	if(!defined('_PS_VERSION_')) {
		exit;
	}

    class Offers extends ObjectModel {

        public $id_offers;
        public $id_customer;
        public $id_product;
        public $message;
        public $status;
        public $active;
        public $date_add;

        public static $definition = array (

            'table' => 'offers',
            'primary' => 'id_offers',
            'fields' => array (
                'id_offers' => array ('type'     => self::TYPE_INT, 
                                        'validate' => 'isUnsignedId'),
                'id_product' => array ('type'      => self::TYPE_INT, 
                                        'validate' => 'isUnsignedId',
                                        'required' => true),
                'message' => array ('type'         => self::TYPE_HTML, 
                                        'validate' => 'isCleanHtml'),
                'status' => array ('type'          => self::TYPE_BOOL, 
                                        'validate' => 'isBool'),
                'active' => array ('type'          => self::TYPE_BOOL, 
                                        'validate' => 'isBool'),
                'date_add' => array ('type'        => self::TYPE_DATE, 
                                        'validate' => 'isDate'),
            )
        );

    }

