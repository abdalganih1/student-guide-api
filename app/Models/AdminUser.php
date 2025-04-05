<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// مهم: استورد Authenticatable بدلًا من Model العادي لتمكين التوثيق
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // لتفعيل الإشعارات (اختياري)
use Laravel\Sanctum\HasApiTokens; // إذا كنت ستستخدم Sanctum لتوثيق API لوحة التحكم أيضًا

class AdminUser extends Authenticatable // يرث من Authenticatable
{
    // استخدم Traits المطلوبة
    use HasFactory, Notifiable;
    // use HasApiTokens; // أضفه إذا لزم الأمر

    // تحديد اسم الجدول صراحةً
    protected $table = 'admin_users';

    // تحديد الـ guard الخاص بهذا المودل (مفيد عند استخدام multiple guards)
    protected $guard = 'admin'; // يجب أن يتطابق هذا الاسم مع ما ستعرفه في config/auth.php

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password', // سيتم عمل hash لكلمة المرور تلقائيًا بواسطة Laravel إذا استخدمت create أو update
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * إخفاء كلمة المرور و remember_token عند تحويل المودل إلى مصفوفة أو JSON
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * تحديد نوع البيانات للحقول (اختياري لكنه مفيد)
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // إذا كان لديك هذا العمود
        'password' => 'hashed', // يضمن أن كلمة المرور يتم عمل hash لها تلقائيًا عند التعيين
    ];
}