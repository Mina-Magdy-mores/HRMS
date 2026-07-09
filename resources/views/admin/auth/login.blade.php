<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="{{ asset('assets/fonts/SansPro/SansPro.min.css') }}" rel="stylesheet">
</head>
{{-- add background img--}}
<body class="hold-transition login-page" style="background-image: url('{{ asset('assets/images/login.jpg') }}') ; background-size: cover;">
    <div class="login-box">
        <div class="login-logo">
            <span><b>HR</b>sm</span>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body shadow-2xl shadow-gray-900">
                @session('error')
                    <p class="text-danger text-right  mb-3">
                        {{ session('error') }}
                    </p>
                @endsession
                <p class="login-box-msg">تسجيل الدخول</p>

                <form action="{{ route('admin.login') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <div class="input-group">
                            <input name="username" type="text" class="form-control text-right"
                                placeholder="اسم المستخدم" value="{{ old('username') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>

                        </div>
                        @error('username')
                            <p class="text-danger text-right  ">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group ">
                            <input name="password" type="password" class="form-control text-right"
                                placeholder="كلمة المرور">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>

                        </div>
                        @error('password')
                            <p class="text-danger text-right  mb-3">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    <div class="row">
                        <div class="col-6 m-auto">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">تسجيل الدخول</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        
        <div class="card mt-3">
            <div class="card-header bg-info text-white text-center p-2" style="font-size: 15px;">
                <strong>بيانات الدخول للتجربة (Demo Accounts)</strong>
            </div>
            <div class="card-body p-3 text-right" style="font-size: 14px; direction: rtl;">
                <div class="mb-2">
                    <strong>1. حساب مدير النظام (Admin):</strong><br>
                    اسم المستخدم: <code class="bg-light px-1 font-weight-bold" style="font-size: 14px;">admin</code><br>
                    كلمة المرور: <code class="bg-light px-1 font-weight-bold" style="font-size: 14px;">admin</code>
                </div>
                <hr class="my-2">
                <div>
                    <strong>2. حساب موظف (Employee Panel):</strong><br>
                    اسم المستخدم: <code class="bg-light px-1 font-weight-bold" style="font-size: 14px;">ahmed</code><br>
                    كلمة المرور: <code class="bg-light px-1 font-weight-bold" style="font-size: 14px;">password123</code>
                </div>
            </div>
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
