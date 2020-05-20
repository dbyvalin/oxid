<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

/**
 * Metadata version
 */
$sMetadataVersion = '3.0';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'maxpay',
    'title'        => 'MaxPay',
    'description'  => array(
        'de' => 'Modul für die Zahlung mit MaxPay.',
        'en' => 'Module for MaxPay payment.',
    ),
    'thumbnail'    => 'logo.png',
    'version'      => '1.0.0',
    'author'       => 'Dinarys GmbH',
    'url'          => 'https://dinarys.com',
    'email'        => 'admin@maxpay.com',
    'extend'       => array(
        \OxidEsales\Eshop\Core\ViewConfig::class                              => \Maxpay\MaxpayModule\Core\ViewConfig::class,
        \OxidEsales\Eshop\Application\Controller\OrderController::class       => \Maxpay\MaxpayModule\Controller\OrderController::class,
        \OxidEsales\Eshop\Application\Controller\PaymentController::class     => \Maxpay\MaxpayModule\Controller\PaymentController::class,
        \OxidEsales\Eshop\Application\Controller\WrappingController::class    => \Maxpay\MaxpayModule\Controller\WrappingController::class,
        \OxidEsales\Eshop\Application\Controller\Admin\OrderList::class       => \Maxpay\MaxpayModule\Controller\Admin\OrderList::class,
        \OxidEsales\Eshop\Application\Controller\Admin\DeliverySetMain::class => \Maxpay\MaxpayModule\Controller\Admin\DeliverySetMain::class,
        \OxidEsales\Eshop\Application\Model\Address::class                    => \Maxpay\MaxpayModule\Model\Address::class,
        \OxidEsales\Eshop\Application\Model\User::class                       => \Maxpay\MaxpayModule\Model\User::class,
        \OxidEsales\Eshop\Application\Model\Order::class                      => \Maxpay\MaxpayModule\Model\Order::class,
        \OxidEsales\Eshop\Application\Model\Basket::class                     => \Maxpay\MaxpayModule\Model\Basket::class,
        \OxidEsales\Eshop\Application\Model\Article::class                    => \Maxpay\MaxpayModule\Model\Article::class,
        \OxidEsales\Eshop\Application\Model\PaymentGateway::class             => \Maxpay\MaxpayModule\Model\PaymentGateway::class,
    ),
    'controllers' => array(
        'maxpaystandarddispatcher'        => \Maxpay\MaxpayModule\Controller\StandardDispatcher::class,
        'maxpayorder_maxpay'              => \Maxpay\MaxpayModule\Controller\Admin\OrderController::class
    ),
    'events'       => array(
        'onActivate'   => '\Maxpay\MaxpayModule\Core\Events::onActivate',
        'onDeactivate' => '\Maxpay\MaxpayModule\Core\Events::onDeactivate'
    ),
    'templates' => array(
    ),
    'blocks' => array(
        array('template' => 'deliveryset_main.tpl',               'block'=>'admin_deliveryset_main_form',           'file'=>'/views/blocks/deliveryset_main.tpl'),
        array('template' => 'page/checkout/payment.tpl',          'block'=>'select_payment',                        'file'=>'/views/blocks/page/checkout/maxpaypaymentselector.tpl'),
        array('template' => 'order_list.tpl',                     'block'=>'admin_order_list_filter',               'file'=>'/views/blocks/maxpayorder_list_filter_actions.tpl'),
        array('template' => 'order_list.tpl',                     'block'=>'admin_order_list_sorting',              'file'=>'/views/blocks/maxpayorder_list_sorting_actions.tpl'),
        array('template' => 'order_list.tpl',                     'block'=>'admin_order_list_item',                 'file'=>'/views/blocks/maxpayorder_list_items_actions.tpl'),
        array('template' => 'order_list.tpl',                     'block'=>'admin_order_list_colgroup',             'file'=>'/views/blocks/maxpayorder_list_colgroup_actions.tpl'),
     ),
    'settings' => array(
        array('group' => 'maxpay_settings',         'name' => 'sMaxpayPublickKey',              'type' => 'str',      'value' => ''),
        array('group' => 'maxpay_settings',         'name' => 'sMaxpayPrivateKey',              'type' => 'str',      'value' => ''),

        array('group' => 'maxpay_development', 'name' => 'blMaxpayLoggerEnabled',           'type' => 'bool',     'value' => 'true'),
        array('group' => 'maxpay_development', 'name' => 'blMaxpaySandboxMode',             'type' => 'bool',     'value' => 'false'),
        array('group' => 'maxpay_development', 'name' => 'sMaxpayTestPublicKey',            'type' => 'str',      'value' => ''),
        array('group' => 'maxpay_development', 'name' => 'sMaxpayTestPrivateKey',           'type' => 'str',      'value' => ''),
    )
);
