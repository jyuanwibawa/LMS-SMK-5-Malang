<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiswaProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('siswa.profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email,'.$user->id],
            'password' => ['nullable','string','min:6'],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();

        return redirect()->route('siswa.profil.show')->with('status', 'Profil berhasil diperbarui.');
    }
}