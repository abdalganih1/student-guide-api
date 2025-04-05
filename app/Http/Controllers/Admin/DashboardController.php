<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
// استيراد الـ Models لحساب الإحصائيات
use App\Models\Specialization;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\GraduationProject;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية.
     */
    public function index(): View
    {
        // جلب بعض الإحصائيات لعرضها (اختياري)
        $stats = [
            'specializations' => Specialization::count(),
            'courses' => Course::count(),
            'faculty' => Faculty::count(),
            'projects' => GraduationProject::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}