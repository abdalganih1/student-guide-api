@extends('admin.layouts.app')

@section('title', 'تعديل الاختصاص: ' . $specialization->name_ar)

@section('content')
<div class="container-fluid">
     <h1 class="h3 mb-4 text-gray-800">تعديل الاختصاص: {{ $specialization->name_ar }}</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- لاحظ تغيير المسار و إضافة @method('PUT') --}}
            <form action="{{ route('admin.specializations.update', $specialization) }}" method="POST">
                @csrf
                @method('PUT') {{-- تحديد طريقة الإرسال للتعديل --}}

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_ar" class="form-label">الاسم (بالعربية) <span class="text-danger">*</span></label>
                        {{-- عرض القيمة الحالية من المودل أو القيمة القديمة إذا فشل التحقق --}}
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $specialization->name_ar) }}" required>
                        @error('name_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">الاسم (بالإنجليزية)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $specialization->name_en) }}">
                        @error('name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                 <div class="mb-3">
                    <label for="description_ar" class="form-label">الوصف (بالعربية) <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="4" required>{{ old('description_ar', $specialization->description_ar) }}</textarea>
                    @error('description_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                 <div class="mb-3">
                    <label for="description_en" class="form-label">الوصف (بالإنجليزية)</label>
                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="4">{{ old('description_en', $specialization->description_en) }}</textarea>
                     @error('description_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">حفظ التعديلات</button>
                    <a href="{{ route('admin.specializations.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection