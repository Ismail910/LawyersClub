@extends('layouts.master')

@section('title', 'تحديث الملف الشخصي')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>تحديث الملف الشخصي</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- حقل اسم المستخدم -->
                            <div class="mb-3">
                                <label for="user_name" class="form-label">اسم المستخدم</label>
                                <input type="text" name="user_name" id="user_name" class="form-control" value="{{ old('user_name', $user->user_name) }}">
                            </div>

                            <!-- حقل البريد الإلكتروني -->
                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                            </div>

                            <!-- حقل كلمة المرور -->
                            <div class="mb-3">
                                <label for="password" class="form-label">كلمة المرور</label>
                                <input type="password" name="password" id="password" class="form-control">
                                <small class="form-text text-muted">اترك الحقل فارغًا إذا كنت لا تريد تغيير كلمة المرور</small>
                            </div>

                            <!-- زر الحفظ -->
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
