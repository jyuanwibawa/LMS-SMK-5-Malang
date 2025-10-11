<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menampilkan halaman register.
     */
    public function showRegisterForm()
    {
        // Ambil data roles 'guru' dan 'siswa' untuk ditampilkan di form
        $roles = Role::whereIn('name', ['guru', 'siswa'])->get();
        return view('auth.register', ['roles' => $roles]);
    }

    /**
     * Memproses data dari form login.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            // Bisa login menggunakan email atau identity_number
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Cek apakah input 'login' adalah email
        $isEmail = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL);

        // 2. Coba lakukan autentikasi
        $attempt = Auth::attempt([
            $isEmail ? 'email' : 'identity_number' => $credentials['login'],
            'password' => $credentials['password'],
        ]);

        if ($attempt) {
            // Jika berhasil, regenerate session untuk keamanan
            $request->session()->regenerate();

            // Arahkan ke halaman dashboard
            return redirect()->intended('dashboard');
        }

        // 3. Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'login' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ])->onlyInput('login');
    }

    /**
     * Memproses data dari form register.
     */
    public function register(Request $request)
    {
        // 1. Validasi semua input dari form
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'identity_number' => ['required', 'numeric', 'unique:users'],
            'password'        => ['required', 'confirmed', Password::min(8)], // 'confirmed' akan mencocokkan dengan 'password_confirmation'
            'role_id'         => ['required', 'exists:roles,id'], // Pastikan role_id ada di tabel roles
        ]);

        // 2. Buat user baru jika validasi berhasil
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'identity_number' => $request->identity_number,
            'password'        => Hash::make($request->password), // Password wajib di-hash!
            'role_id'         => $request->role_id,
        ]);

        // 3. Login-kan user yang baru dibuat
        Auth::login($user);

        // 4. Arahkan ke halaman dashboard
        return redirect('/dashboard');
    }

    /**
     * Memproses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
