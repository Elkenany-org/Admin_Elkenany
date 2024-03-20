<?php
const Now = 'now';
const Scheduling = 'scheduling';
const Daily = 'daily';
const Custom = 'custom';
return [
    'name' => 'Notification',
    'type'=>[
        Now         =>'الأن',
        Scheduling  =>'مجدول',
        Daily       =>'يومي',
        Custom      =>'مخصص',
        ],

    'source'=>[
        'Modules\\Guide\\Entities\\Company'=>'شركات',
        'Modules\\Guide\\Entities\\Company_product'=>'منتجات',
        'Modules\\News\\Entities\\News'=>'اخبار',
        'Modules\\Magazines\\Entities\\Magazine'=>'مجلات',
        'Modules\\Shows\\Entities\\Show'=>'معارض',
    ],

    'source_mobile'=>[
        'Modules\\Guide\\Entities\\Company'=>'companies',
        'Modules\\Guide\\Entities\\Company_product'=>'new_product',
        'Modules\\News\\Entities\\News'=>'news',
        'Modules\\Magazines\\Entities\\Magazine'=>'magazines',
        'Modules\\Shows\\Entities\\Show'=>'showes',
    ],

//`news` الاخبار
//
//`showes` المعارض
//
//`stores` السوق
//
//`magazines` الدلائل والمجلات
//
//`companies` شركة في الدليل
//
//`local_stock_sub` البورصة لو local
//
//`fodder_stock_sub` البورصة لو fodder
//
//`new_product` دا لو اتضاف منتج جديد
];
