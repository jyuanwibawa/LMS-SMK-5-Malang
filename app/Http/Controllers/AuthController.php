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
        return view('auth.register');
    }

    /**
     * Memproses data dari form login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        $isEmail = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL);

        $attempt = Auth::attempt([
            $isEmail ? 'email' : 'identity_number' => $credentials['login'],
            'password' => $credentials['password'],
        ]);

        if ($attempt) {
            $request->session()->regenerate();

            // ================== MULAI PERUBAHAN ==================

            $user = Auth::user();

            // Cek peran user dan arahkan ke dashboard yang sesuai
            if ($user->role->name === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role->name === 'guru') {
                return redirect()->intended('/guru/dashboard');
            } elseif ($user->role->name === 'siswa') {
                return redirect()->intended('/siswa/dashboard');
            }

            // Fallback jika user tidak punya peran yang dikenali
            return redirect()->intended('/dashboard');

            // ================== AKHIR PERUBAHAN ==================
        }

        return back()->withErrors([
            'login' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ])->onlyInput('login');
    }

    /**
     * Memproses data dari form register.
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'identity_number' => ['required', 'numeric', 'unique:users'],
            'jenis_kelamin'   => ['required', 'in:Laki-Laki,Perempuan'],
            'password'        => ['required', 'confirmed', Password::min(8)],
        ]);

        $siswaRole = Role::where('name', 'siswa')->firstOrFail();

        $user = User::create([
            'name'            => $validatedData['name'],
            'email'           => $validatedData['email'],
            'identity_number' => $validatedData['identity_number'],
            'jenis_kelamin'   => $validatedData['jenis_kelamin'],
            'password'        => Hash::make($validatedData['password']),
            'role_id'         => $siswaRole->id,
        ]);

        Auth::login($user);

        // --- Redirect diubah agar konsisten ---
        return redirect('/siswa/dashboard');
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
