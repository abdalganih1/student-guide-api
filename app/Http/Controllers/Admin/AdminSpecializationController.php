<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use App\Http\Requests\Admin\StoreSpecializationRequest; // استيراد Request الإضافة
use App\Http\Requests\Admin\UpdateSpecializationRequest; // استيراد Request التعديل
use Illuminate\Http\Request; // لا نحتاجها مباشرة هنا بسبب Form Requests
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminSpecializationController extends Controller
{
    /**
     * عرض قائمة بالاختصاصات.
     */
    public function index(): View
    {
        $specializations = Specialization::orderBy('name_ar')->paginate(15); // جلب وعرض 15 لكل صفحة
        return view('admin.specializations.index', compact('specializations'));
    }

    /**
     * عرض نموذج إضافة اختصاص جديد.
     */
    public function create(): View
    {
        return view('admin.specializations.create');
    }

    /**
     * تخزين اختصاص جديد في قاعدة البيانات.
     */
    public function store(StoreSpecializationRequest $request): RedirectResponse
    {
        // التحقق من الصحة تم بواسطة StoreSpecializationRequest
        // جلب البيانات التي تم التحقق منها
        $validatedData = $request->validated();

        // إنشاء السجل
        Specialization::create($validatedData);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('admin.specializations.index')
                         ->with('success', 'تمت إضافة الاختصاص بنجاح.');
    }

    /**
     * عرض تفاصيل اختصاص محدد (اختياري في لوحة التحكم).
     * public function show(Specialization $specialization): View
     * {
     *     // return view('admin.specializations.show', compact('specialization'));
     * }
     */

    /**
     * عرض نموذج تعديل اختصاص قائم.
     */
    public function edit(Specialization $specialization): View
    {
        // المودل تم جلبه تلقائياً بواسطة Route Model Binding
        return view('admin.specializations.edit', compact('specialization'));
    }

    /**
     * تحديث بيانات اختصاص قائم في قاعدة البيانات.
     */
    public function update(UpdateSpecializationRequest $request, Specialization $specialization): RedirectResponse
    {
        // التحقق من الصحة تم بواسطة UpdateSpecializationRequest
        $validatedData = $request->validated();

        // تحديث السجل
        $specialization->update($validatedData);

        return redirect()->route('admin.specializations.index')
                         ->with('success', 'تم تعديل الاختصاص بنجاح.');
    }

    /**
     * حذف اختصاص من قاعدة البيانات.
     */
    public function destroy(Specialization $specialization): RedirectResponse
    {
        try {
            // يمكنك إضافة تحقق هنا للتأكد من عدم وجود مقررات مرتبطة بهذا الاختصاص قبل الحذف
            if ($specialization->courses()->exists()) {
                return redirect()->route('admin.specializations.index')
                                 ->with('error', 'لا يمكن حذف الاختصاص لوجود مقررات مرتبطة به.');
            }

            $specialization->delete();
            return redirect()->route('admin.specializations.index')
                             ->with('success', 'تم حذف الاختصاص بنجاح.');

        } catch (\Exception $e) {
            // معالجة أي أخطاء أخرى قد تحدث (مثل مشاكل قاعدة البيانات)
             return redirect()->route('admin.specializations.index')
                             ->with('error', 'حدث خطأ أثناء محاولة حذف الاختصاص.');
        }
    }
}