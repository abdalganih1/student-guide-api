<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl"> {{-- إضافة دعم العربية RTL --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل دخول المدير</title>
    <!-- Bootstrap CSS (CDN) -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3flBFAEvoPGcrtMQCDXcKRSIGRjw" crossorigin="anonymous">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 400px; margin: 100px auto; padding: 30px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .login-container h2 { margin-bottom: 20px; text-align: center; }
        .form-floating label { right: 0; left: auto; } /* تعديل لموضع label في RTL */
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>لوحة تحكم دليل الطالب</h2>
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf {{-- هام: حماية CSRF --}}

                {{-- عرض أخطاء التحقق العامة (مثل خطأ كلمة المرور) --}}
                @if($errors->has('login'))
                    <div class="alert alert-danger p-2 small">
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('login') is-invalid @enderror" id="login" name="login" placeholder="اسم المستخدم أو البريد الإلكتروني" value="{{ old('login') }}" required autofocus>
                    <label for="login">اسم المستخدم أو البريد الإلكتروني</label>
                    {{-- لا نعرض خطأ محدد هنا لنفس السبب الأمني --}}
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('login') is-invalid @enderror" id="password" name="password" placeholder="كلمة المرور" required>
                    <label for="password">كلمة المرور</label>
                 </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        تذكرني
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
                </div>

                 {{-- يمكنك إضافة رابط "نسيت كلمة المرور؟" هنا إذا طبقت الميزة --}}
                {{--
                @if (Route::has('admin.password.request'))
                    <div class="text-center mt-3">
                        <a class="btn btn-link" href="{{ route('admin.password.request') }}">
                            نسيت كلمة المرور؟
                        </a>
                    </div>
                @endif
                --}}
            </form>
        </div>
    </div>
    <!-- Bootstrap JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>