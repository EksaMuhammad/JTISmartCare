{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - JTISmartCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin: 0; 
            height: 100vh; 
            overflow: hidden; 
            background-color: #f8fafc;
        }
        .split-layout {
            display: flex;
            height: 100vh;
            width: 100%;
        }
        .left-side {
            flex: 1;
            background:
                linear-gradient(
                    135deg,
                    rgba(79, 70, 229, 0.5),
                    rgba(6, 182, 212, 0.5)
                ),
                url('{{ asset("assets/images/login-bg.png") }}');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding: 40px;
            padding-bottom: 80px;
            color: white;
            position: relative;
        }
        .left-side-content {
            text-align: center;
            z-index: 1;
        }
        .brand-title {
            font-family: 'Sora', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .brand-title .text-teal { color: #28B5B5; }
        .brand-title .text-white-light { color: #9CD5FF; }
        .brand-subtitle {
            font-size: 1.1rem;
            color: #9CD5FF;
            max-width: 550px;
            line-height: 1.5;
            margin: 0 auto;
        }
        .right-side {
            width: 550px;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
            overflow-y: auto;
        }
        .auth-title {
            color: #2b5b84;
            font-family: 'Sora', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 30px;
        }
        .input-group-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }
        .input-group-custom {
            position: relative;
            margin-bottom: 1.2rem;
        }
        .logo-img {
            width: 600px;
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            margin-bottom: 25px;
        }
        .input-group-custom .form-control {
            border-radius: 12px;
            border: 1px solid #355872;
            padding: 14px 20px 14px 50px;
            font-size: 0.95rem;
            background-color: #f8fafc;
            transition: all 0.2s;
            height: 56px;
        }
        .input-group-custom .form-control::placeholder {
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .input-group-custom .form-control:focus {
            border-color: #355872;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(53, 88, 114, 0.1);
            outline: none;
        }
        .input-group-custom .icon-left {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.2rem;
            z-index: 5;
            transition: all 0.2s;
        }
        .input-group-custom .form-control:focus ~ .icon-left {
            color: #355872;
        }
        .input-group-custom .icon-right {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.2rem;
            z-index: 5;
            cursor: pointer;
            transition: all 0.2s;
        }
        .input-group-custom .icon-right:hover {
            color: #355872;
        }
        .btn-auth { 
            background-color: #355872;
            border: none;
            width: 100%; 
            height: 56px;
            border-radius: 12px; 
            font-weight: 500; 
            font-size: 1.05rem; 
            color: #fff; 
            cursor: pointer; 
            transition: all 0.3s; 
            margin-top: 10px;
        }
        .btn-auth:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(53, 88, 114, 0.3);
        }
        .form-check-input {
            border-color: #355872;
        }
        .form-check-input:checked {
            background-color: #355872;
            border-color: #355872;
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(53, 88, 114, 0.1);
            border-color: #355872;
        }
        .auth-links {
            margin-top: 30px;
            text-align: center;
            font-size: 0.95rem;
            color: #64748b;
        }
        .auth-links a {
            color: #355872;
            text-decoration: none;
            font-weight: 700;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 992px) {
            .left-side { display: none; }
            .right-side { width: 100%; padding: 40px; }
            body { overflow: auto; }
            .split-layout { height: auto; min-height: 100vh; }
        }
    </style>
</head>
<body>

<div class="split-layout">
    <!-- Left Side -->
    <div class="left-side">
        <div class="left-side-content">
            <div class="brand-title">
                <span class="text-teal">JTI Smart</span><span class="text-white-light">Care</span>
            </div>
            <div class="brand-subtitle">
                Sistem Pakar Deteksi dan Rekomendasi Burnout Mahasiswa
            </div>
        </div>
    </div>

    <!-- Right Side -->
    <div class="right-side">
        
        <div class="text-center">
            <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo JTISmartCare" class="logo-img">
            <h2 class="auth-title">Buat Akun Baru</h2>
        </div>
        
        @if($errors->any())
        <div class="alert alert-danger mb-4" style="border-radius:12px;font-size:0.9rem;border:none;background-color:#fee2e2;color:#991b1b;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <label class="input-group-label">Nama</label>
            <div class="input-group-custom">
                <input type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Ismi Atika"
                    required
                    style="padding-left: 20px;">
            </div>

            <label class="input-group-label">NIM</label>
            <div class="input-group-custom">
                <input type="text" name="nim"
                    class="form-control"
                    value="{{ old('nim') }}"
                    placeholder="Contoh: 2341760123">
                <i class="bi bi-person icon-left"></i>
            </div>

            <label class="input-group-label">Program Studi</label>
            <div class="input-group-custom">
                <input type="text" name="jurusan"
                    class="form-control @error('jurusan') is-invalid @enderror"
                    value="{{ old('jurusan') }}"
                    placeholder="Contoh: D-IV Teknik Informatika"
                    required>
                <i class="bi bi-mortarboard icon-left"></i>
            </div>

            <label class="input-group-label">Angkatan</label>
            <div class="input-group-custom">
                <input type="text" name="angkatan" class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan') }}" placeholder="Contoh: 2022" required>
                <i class="bi bi-calendar-event icon-left"></i>
            </div>
            
            <label class="input-group-label">Email</label>
            <div class="input-group-custom">
               <input type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="Contoh: ismixx@gmail.com"
                    required>
                <i class="bi bi-envelope icon-left"></i>
            </div>
            
            <label class="input-group-label">Password</label>
            <div class="input-group-custom">
                <input type="password" name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Minimal 6 karakter"
                    required>
                <i class="bi bi-lock icon-left"></i>
                <i class="bi bi-eye icon-right" id="togglePassword"></i>
            </div>
            
            <label class="input-group-label">Konfirmasi Password</label>
            <div class="input-group-custom">
               <input type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="form-control"
                    placeholder="Ulangi password Anda"
                    required>
                <i class="bi bi-lock-fill icon-left"></i>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms" required style="cursor:pointer;">
                    <label class="form-check-label" for="terms" style="font-size:0.85rem;color:#64748b;cursor:pointer;">
                        Saya setuju dengan <a href="#" style="color:#355872;text-decoration:none;">Syarat dan Ketentuan Layanan</a> serta <a href="#" style="color:#355872;text-decoration:none;">Kebijakan Privasi</a>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-auth">Mendaftar</button>
        </form>

        <div class="auth-links">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>
</body>
</html>
