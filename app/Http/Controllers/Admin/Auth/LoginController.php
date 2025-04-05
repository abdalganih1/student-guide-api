<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // استيراد واجهة التوثيق
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * عرض نموذج تسجيل دخول المدير.
     */
    public function showLoginForm(): View
    {
        // إذا كان المدير مسجل دخوله بالفعل، وجهه للوحة التحكم
        if (Auth::guard('admin')->check()) {
             return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login'); // تأكد من وجود هذا الـ View
    }

    /**
     * معالجة طلب تسجيل دخول المدير.
     */
    public function login(Request $request): RedirectResponse
    {
        // التحقق من صحة المدخلات
        $request->validate([
            'login' => 'required|string', // حقل موحد لـ username أو email
            'password' => 'required|string',
        ]);

        // تحديد نوع المدخل (username أو email)
        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // محاولة تسجيل الدخول باستخدام الـ guard 'admin'
        $credentials = [
            $loginType => $request->input('login'),
            'password' => $request->input('password'),
        ];

        // محاولة المصادقة
        // استخدام Auth::guard('admin')->attempt()
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            // نجاح المصادقة
            $request->session()->regenerate(); // تجديد الجلسة لمنع session fixation
            return redirect()->intended(route('admin.dashboard')); // توجيه للوحة التحكم أو للوجهة المقصودة سابقاً
        }

        // فشل المصادقة
        // إرجاع خطأ التحقق مع رسالة مخصصة
        throw ValidationException::withMessages([
            'login' => [trans('auth.failed')], // استخدام رسالة الخطأ القياسية من ملفات اللغة
        ]);
    }

    /**
     * تسجيل خروج المدير.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout(); // تسجيل الخروج من الـ guard 'admin'

        $request->session()->invalidate(); // إبطال الجلسة الحالية
        $request->session()->regenerateToken(); // تجديد CSRF token

        return redirect()->route('admin.login'); // توجيه لصفحة تسجيل الدخول
    }
}