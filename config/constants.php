<?php

const  ADDRESS_TYPE_OUTLET = 'outlet';
const  ADDRESS_TYPE_FACTORY = 'factory';
const  ADDRESS_TYPE_FARM = 'farm';
const  ADDRESS_TYPE_LAB = 'lab';
const  ADDRESS_TYPE_OTHER = 'ozther';

const LOGIN_ALERT = 'برجاء تسجيل الدخول لأستخدام جميع الخدمات بشكل كامل';
const PREMIUM_ALERT = 'برجاء الترقيه الي الباقه المدفوعه لأستخدام جميع الخدمات بشكل كامل';

return [
    'address_type'=>[
        ADDRESS_TYPE_OUTLET=>'المنافذ',
        ADDRESS_TYPE_FACTORY=>'المصانع',
        ADDRESS_TYPE_FARM=>'المزارع',
        ADDRESS_TYPE_LAB=>'المعامل',
        ADDRESS_TYPE_OTHER=>'مقرات أخري',
    ],

    //ads system
    'type_place'=>[
        'guide'=>[
            'name'=>'الدليل',
            'type'=>'guide',
        ],
        'localstock'=>[
            'name'=>'البورصة اليومية',
            'type'=>'localstock',
        ],
        'fodderstock'=>[
            'name'=>'بورصة الاعلاف',
            'type'=>'fodderstock',
        ],
        'store'=>[
            'name'=>'السوق',
            'type'=>'store',
        ],
        'news'=>[
            'name'=>'الاخبار',
            'type'=>'news',
        ],
        'shows'=>[
            'name'=>'المعارض',
            'type'=>'shows',
        ],
        'magazines'=>[
            'name'=>'الدلائل والمجلات',
            'type'=>'magazines',
        ],
        'ships'=>[
            'name'=>'السفن',
            'type'=>'ships',
        ],
        'home'=>[
            'name'=>'الرئيسية',
            'type'=>'home',
        ],
    ],

    'type_ads'=>[
        'banner',
        'logo',
        'sort',
        'popup',
        'notification'
    ]

];
