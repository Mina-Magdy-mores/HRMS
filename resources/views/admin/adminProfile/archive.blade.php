@extends('admin.layouts.admin')

@section('title', 'أرشيف - ' . $admin->name)
@section('contentHeader')
    <i class="fas fa-history"></i>
    المستخدمين
@endsection

@section('contentHeaderActiveLink')
    <a href="{{ route('admin.admin-profiles.index') }}">المستخدمين</a>
@endsection
@section('contentHeaderActive', 'الأرشيف')

@section('content')
<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-history"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي سجلات الأرشيف</span>
                    <span class="info-box-number">{{ $archives->total() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-edit"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عمليات التعديل</span>
                    <span class="info-box-number">{{ $archives->getCollection()->where('action', 'update')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-trash"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عمليات الحذف</span>
                    <span class="info-box-number">{{ $archives->getCollection()->where('action', 'delete')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-user"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الأدمن</span>
                    <span class="info-box-number">{{ $admin->name }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Card -->
    <div class="card card-info card-outline shadow">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history"></i>
                سجل الأرشيف — {{ $admin->name }}
                <small class="text-muted">({{ $admin->username }})</small>
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.admin-profiles.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        <div class="card-body">

            @if($archives->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                لا توجد سجلات أرشيف لهذا الأدمن حتى الآن
            </div>
            @else

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="bg-info text-white">
                        <tr>
                            <th>#</th>
                            <th>نوع العملية</th>
                            <th>الصورة القديمة</th>
                            <th>الاسم</th>
                            <th>اسم المستخدم</th>
                            <th>البريد</th>
                            <th>الهاتف</th>
                            <th>الرقم القومي</th>
                            <th>الجنس</th>
                            <th>الحالة</th>
                            <th>تاريخ الميلاد</th>
                            <th>العنوان</th>
                            <th>نفّذ بواسطة</th>
                            <th>تاريخ الأرشفة</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($archives as $archive)
                        <tr>
                            <td>{{ $archive->id }}</td>

                            <td>
                                @if($archive->action === 'update')
                                <span class="badge badge-warning px-3 py-2">
                                    <i class="fas fa-edit"></i> تعديل
                                </span>
                                @else
                                <span class="badge badge-danger px-3 py-2">
                                    <i class="fas fa-trash"></i> حذف
                                </span>
                                @endif
                            </td>

                            <td>
                                @if($archive->image)
                                <img src="{{ asset('storage/' . $archive->image) }}"
                                    class="rounded-circle shadow"
                                    width="40" height="40"
                                    style="object-fit: cover;">
                                @else
                                <span class="badge badge-secondary px-2 py-2">
                                    <i class="fas fa-user"></i>
                                </span>
                                @endif
                            </td>

                            <td>{{ $archive->name }}</td>

                            <td>{{ $archive->username }}</td>

                            <td>{{ $archive->email ?? '---' }}</td>

                            <td>{{ $archive->phone ?? '---' }}</td>

                            <td>{{ $archive->national_id ?? '---' }}</td>

                            <td>
                                @if($archive->gender == 'male')
                                <span class="badge badge-info px-2 py-1">
                                    <i class="fas fa-mars"></i> ذكر
                                </span>
                                @elseif($archive->gender == 'female')
                                <span class="badge px-2 py-1" style="background:#e83e8c;color:#fff;">
                                    <i class="fas fa-venus"></i> أنثى
                                </span>
                                @else
                                <span class="text-muted">---</span>
                                @endif
                            </td>

                            <td>
                                @if($archive->status == 1)
                                <span class="badge badge-success px-2 py-1">
                                    <i class="fas fa-check-circle"></i> مفعّل
                                </span>
                                @else
                                <span class="badge badge-danger px-2 py-1">
                                    <i class="fas fa-times-circle"></i> معطّل
                                </span>
                                @endif
                            </td>

                            <td>{{ $archive->birth_date ?? '---' }}</td>

                            <td>{{ $archive->address ?? '---' }}</td>

                            <td>
                                @if($archive->archivedBy)
                                {{ $archive->archivedBy->name }}
                                @else
                                <span class="text-muted">---</span>
                                @endif
                            </td>

                            <td>
                                <small>{{ $archive->created_at }}</small>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            {{ $archives->links() }}

            @endif

        </div>
    </div>

</div>
@endsection
