<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Baru - LMS Portal</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS Anda tetap sama, hanya ditambahkan .error-message */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        .left-panel {
            flex: 1;
            background-color: #121212;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        .left-panel .content-wrapper {
            max-width: 380px;
            text-align: center;
        }

        .logo-header {
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background-color: #fff;
            color: #121212;
            border-radius: 18px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .logo-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .logo-header p {
            font-size: 14px;
            color: #a0a0a0;
            line-height: 1.5;
        }

        .welcome-section {
            margin-bottom: 40px;
        }

        .welcome-section h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .welcome-section p {
            color: #a0a0a0;
            line-height: 1.6;
        }

        .feature-list .feature-item {
            display: flex;
            align-items: center;
            background-color: #222;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            text-align: left;
        }

        .feature-item i {
            font-size: 22px;
            margin-right: 15px;
            color: #e0e0e0;
        }

        .feature-item span {
            font-size: 15px;
            font-weight: 500;
        }

        .right-panel {
            flex: 1.2;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
            background-color: #f8f9fa;
            overflow-y: auto;
        }

        .register-form-container {
            width: 100%;
            max-width: 450px;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .register-form-container h3 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #121212;
            text-align: center;
        }

        .register-form-container .subtitle {
            color: #6c757d;
            margin-bottom: 30px;
            text-align: center;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e9ecef;
            background-color: #f8f9fa;
            border-radius: 8px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-group input::placeholder {
            color: #adb5bd;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: #333;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
        }

        .register-button {
            width: 100%;
            padding: 15px;
            margin-top: 15px;
            background-color: #121212;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .register-button:hover {
            background-color: #333;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            font-size: 15px;
            color: #6c757d;
        }

        .login-link a {
            color: #121212;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-panel">
            <div class="content-wrapper">
                <div class="logo-header">
                    <div class="logo-icon"><i class='bx bxs-graduation'></i></div>
                    <h1>LMS Portal</h1>
                    <p>Learning Management System <br> SMK Negeri 5 Malang</p>
                </div>
                <div class="welcome-section">
                    <h2>Mulai Perjalanan Belajar Anda!</h2>
                    <p>Bergabunglah dengan ribuan pengguna yang sudah mempercayai platform kami</p>
                </div>
                <div class="feature-list">
                    <div class="feature-item"><i class='bx bxs-widget'></i><span>Gratis dan mudah digunakan</span></div>
                    <div class="feature-item"><i class='bx bxs-shield-alt-2'></i><span>Data Anda aman</span></div>
                    <div class="feature-item"><i class='bx bxs-bolt'></i><span>Akses instan ke semua fitur</span></div>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <div class="register-form-container">
                <h3>Buat Akun Baru</h3>
                <p class="subtitle">Isi formulir di bawah untuk mendaftar</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf <div class="input-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap"
                            value="{{ old('name') }}" required>
                        @error('name') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="nama@email.com"
                            value="{{ old('email') }}" required>
                        @error('email') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="input-group">
                        <label for="identity_number">NISN/NUPTK</label>
                        <input type="text" id="identity_number" name="identity_number"
                            placeholder="Masukkan NISN atau NUPTK" value="{{ old('identity_number') }}" required>
                        @error('identity_number') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="input-group">
                        <label for="role_id">Saya mendaftar sebagai</label>
                        <select name="role_id" id="role_id" required>
                            <option value="" disabled selected>Pilih peran Anda</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                            @endforeach
                        </select>
                        @error('role_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
                        @error('password') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="input-group">
                        <label for="password_confirmation">Ulangi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Masukkan ulang password" required>
                    </div>

                    <button type="submit" class="register-button">Daftar Sekarang</button>
                </form>

                <p class="login-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                </p>

            </div>
        </div>
    </div>
</body>

</html>