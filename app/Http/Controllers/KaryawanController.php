<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Models\Karyawan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();

        // Pilih kolom dari karyawan dan departemen
        $query->select('karyawan.*', 'departemen.nama_dept')
              ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
              ->orderBy('nama_lengkap');

        // Filter berdasarkan nama karyawan dan kode departemen jika ada input dari request
        if (!empty($request->nama_karyawan)) {
            $query->where('nama_lengkap', 'like', '%'.$request->nama_karyawan.'%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        $karyawan = $query->paginate(10);
        $departemen = DB::table('departemen')->get();

        return view('karyawan.index', compact('karyawan', 'departemen'));
    }

    public function store(Request $request)
    {
        // Validasi input dengan pesan kustom
        $request->validate([
            'nik' => 'required|string|max:10|unique:karyawan,nik', // Ubah max dari 20 ke 10
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'kode_dept' => 'required|string|max:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nik.unique' => 'NIK sudah terdaftar, masukkan NIK lain.',
            'nik.required' => 'NIK harus diisi.',
            'nik.max' => 'NIK tidak boleh lebih dari 10 karakter.', // Pesan untuk validasi max
            // Pesan kustom lainnya dapat ditambahkan di sini
        ]);
    
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('gamatek24');  // Password default
    
        // Cek apakah ada file foto yang di-upload
        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $nik . '.' . $request->file('foto')->getClientOriginalExtension();
            $folderPath = 'uploads/karyawan/';
            $request->file('foto')->storeAs($folderPath, $foto, 'public');
        }
    
        try {
            // Siapkan data untuk disimpan
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password
            ];
    
            // Simpan data ke database
            DB::table('karyawan')->insert($data);
    
            // Berhasil disimpan
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            // Tangani exception jika terjadi duplikasi NIK
            if ($e->getCode() == 23000) {
                // Pesan error khusus untuk duplikat NIK
                return Redirect::back()->with(['warning' => 'Data Gagal Disimpan: NIK ' . $nik . ' sudah terdaftar.']);
            }
    
            // Jika error lainnya
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan: ' . $e->getMessage()]);
        }
    }
    


    public function edit($nik)
    {
        // Ambil data karyawan berdasarkan NIK
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $departemen = DB::table('departemen')->get();

        if ($karyawan) {
            return view('karyawan.edit', compact('departemen', 'karyawan'));
        } else {
            return redirect()->back()->withErrors(['msg' => 'Karyawan tidak ditemukan']);
        }
    }

    public function update(Request $request, $nik)
    {
        // Validasi data
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'kode_dept' => 'required|string|max:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
            'password' => 'nullable|string|min:8|confirmed', // Validasi password jika ada
        ]);

        $nama_lengkap = $validatedData['nama_lengkap'];
        $jabatan = $validatedData['jabatan'];
        $no_hp = $validatedData['no_hp'];
        $kode_dept = $validatedData['kode_dept'];
        $old_foto = $request->old_foto; // Foto lama

        // Inisialisasi variabel foto dengan foto lama
        $foto = $old_foto;

        // Cek apakah ada file foto baru yang di-upload
        if ($request->hasFile('foto')) {
            $foto = $nik . '.' . $request->file('foto')->getClientOriginalExtension();
            $folderPath = 'uploads/karyawan/';

            // Hapus foto lama jika ada
            if ($old_foto && Storage::disk('public')->exists($folderPath . $old_foto)) {
                Storage::disk('public')->delete($folderPath . $old_foto); // Hapus file lama
            }

            // Simpan file baru
            $request->file('foto')->storeAs($folderPath, $foto, 'public');
        }

        // Siapkan data yang akan diupdate
        $data = [
            'nama_lengkap' => $nama_lengkap,
            'jabatan' => $jabatan,
            'no_hp' => $no_hp,
            'kode_dept' => $kode_dept,
            'foto' => $foto,
        ];

        // Jika ada perubahan password, update password yang sudah di-hash
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        try {
            // Update data berdasarkan NIK
            $update = DB::table('karyawan')->where('nik', $nik)->update($data);

            if ($update) {
                return redirect()->route('karyawan.index')->with(['success' => 'Data Berhasil Diupdate']);
            } else {
                return redirect()->route('karyawan.index')->with(['warning' => 'Tidak ada perubahan yang dilakukan.']);
            }
        } catch (\Exception $e) {
            return redirect()->route('karyawan.index')->with(['error' => 'Data Gagal Diupdate: ' . $e->getMessage()]);
        }
    }

    public function destroy($nik)
    {
        // Cari Karyawan berdasarkan NIK
        $karyawan = Karyawan::where('nik', $nik)->first();

        if ($karyawan) {
            // Hapus foto jika ada
            if ($karyawan->foto && Storage::disk('public')->exists('uploads/karyawan/' . $karyawan->foto)) {
                Storage::disk('public')->delete('uploads/karyawan/' . $karyawan->foto); // Hapus file foto
            }

            $karyawan->forceDelete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Tidak Ditemukan']);
        }
    }
}
