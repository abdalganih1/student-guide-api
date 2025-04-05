<?php

return [
    // ... الإعدادات الافتراضية ...

    'defaults' => [
        // 'guard' => 'web', // اترك الافتراضي للويب العادي (إذا كان لديك)
        'guard' => env('AUTH_GUARD', 'web'), // أو اجعله قابلاً للتغيير من .env
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [ // الـ guard الافتراضي (لمستخدمي الموقع العاديين إن وجدوا)
            'driver' => 'session',
            'provider' => 'users',
        ],

        // --- إضافة الـ Guard الخاص بالمدير ---
        'admin' => [
            'driver' => 'session', // نستخدم session-based auth للوحة التحكم
            'provider' => 'admins', // يشير إلى الـ provider أدناه
        ],
        // --- نهاية الإضافة ---

        // 'sanctum' => [ // (يبقى كما هو للـ API إذا كنت تستخدمه)
        //     'driver' => 'sanctum',
        //     'provider' => null,
        // ],
    ],

    'providers' => [
        'users' => [ // الـ provider الافتراضي
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // --- إضافة الـ Provider الخاص بالمدير ---
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\AdminUser::class, // يستخدم مودل AdminUser
        ],
        // --- نهاية الإضافة ---

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    'passwords' => [
        // --- قد تحتاج لتعريف مدخل خاص بكلمات مرور المديرين إذا أردت ميزة استعادة كلمة المرور لهم ---
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        // --- مثال لمدخل خاص بالمديرين ---
         'admins' => [
             'provider' => 'admins', // يستخدم provider المديرين
             'table' => 'password_reset_tokens', // يمكن استخدام نفس الجدول أو جدول منفصل
             'expire' => 60,
             'throttle' => 60,
         ],
        // --- نهاية المثال ---
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
