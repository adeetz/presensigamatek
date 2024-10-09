<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Pastikan model User di-import

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        // Validasi input dengan pesan kustom
        $request->validate([
            'nik' => 'required|string',
            'password' => 'required|string',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Autentikasi untuk karyawan
        if (Auth::guard('karyawan')->attempt(['nik' => $request->nik, 'password' => $request->password])) {
            return redirect('/dashboard');
        } else {
            return redirect('/')
                ->with('warning', 'NIK atau Password salah')
                ->withInput(); // Kembalikan input agar bisa diisi ulang
        }
    }

    public function prosesloginadmin(Request $request)
    {
        // Validasi input dengan pesan kustom
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Autentikasi untuk admin
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->route('admin.dashboard');
        } else {
            // Log percobaan login yang gagal, tetapi tidak menyimpan password
            \Log::info('Login attempt failed', [
                'email' => $request->email,
                'user_exists' => User::where('email', $request->email)->exists(),
            ]);

            return redirect()->back()
                ->with('warning', 'Email atau Password salah')
                ->withInput(); // Kembalikan input agar bisa diisi ulang
        }
    }

    public function proseslogout()
    {
        Auth::guard('karyawan')->logout();
        return redirect('/');
    }

    public function proseslogoutadmin()
    {
        Auth::guard('user')->logout();  // Logout untuk admin yang menggunakan guard 'user'
        return redirect('/panel');  // Redirect ke halaman login admin setelah logout
    }

}
