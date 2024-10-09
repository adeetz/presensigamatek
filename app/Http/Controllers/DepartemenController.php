<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departemen;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DepartemenController extends Controller
{
    // Menampilkan Daftar Departemen
    public function index(Request $request)
    {
        if ($request->has('nama_dept')) {
            $departemen = Departemen::where('nama_dept', 'like', '%' . $request->nama_dept . '%')
                                    ->orderBy('kode_dept')
                                    ->paginate(10); // Menampilkan 10 record per halaman
        } else {
            $departemen = Departemen::orderBy('kode_dept')->paginate(10);
        }

        return view('departemen.index', compact('departemen'));
    }

    // Menyimpan Departemen Baru
    public function store(Request $request)
{
    // Logging data yang diterima
    Log::info('Data yang diterima:', $request->all());

    // Validasi Input
    $validatedData = $request->validate([
        'kode_dept' => 'required|string|max:10|unique:departemen,kode_dept',
        'nama_dept' => 'required|string|max:255|unique:departemen,nama_dept',
    ], [
        'kode_dept.required' => 'Kode Departemen wajib diisi.',
        'kode_dept.unique' => 'Kode Departemen sudah digunakan.',
        'nama_dept.required' => 'Nama Departemen wajib diisi.',
        'nama_dept.unique' => 'Nama Departemen sudah digunakan.',
    ]);

    try {
        // Menyimpan data ke database
        Departemen::create([
            'kode_dept' => $validatedData['kode_dept'],
            'nama_dept' => $validatedData['nama_dept'],
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with(['success' => 'Data Berhasil Disimpan']);
    } catch (\Exception $e) {
        // Menangani error
        return redirect()->back()->with(['warning' => 'Data Gagal Disimpan: ' . $e->getMessage()]);
    }
    }



    // Menampilkan Form Edit Departemen
    public function edit(Request $request)
    {
        $kode_dept = $request->kode_dept;
        Log::info('Edit Request Data:', ['kode_dept' => $kode_dept]);

        $departemen = Departemen::find($kode_dept);

        if ($departemen) {
            // Kembalikan view form edit dengan data departemen
            return view('departemen.edit', compact('departemen'));
        } else {
            return response()->json(['error' => 'Departemen tidak ditemukan'], 404);
        }
    }

    // Update Departemen
    public function update(Request $request, $kode_dept)
    {
        // Logging data yang diterima
        Log::info('Update Request Data:', ['kode_dept' => $kode_dept, 'data' => $request->all()]);

        // Validasi Input dengan aturan unique pada nama_dept, mengabaikan record saat ini
        $validatedData = $request->validate([
            'nama_dept' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departemen', 'nama_dept')->ignore($kode_dept, 'kode_dept'),
            ],
        ], [
            'nama_dept.required' => 'Nama Departemen wajib diisi.',
            'nama_dept.unique' => 'Nama Departemen sudah digunakan.',
        ]);

        try {
            // Cari Departemen
            $departemen = Departemen::find($kode_dept);
            if ($departemen) {
                $departemen->nama_dept = $validatedData['nama_dept'];
                $departemen->save();

                return Redirect::back()->with(['success' => 'Data Berhasil Diperbarui']);
            } else {
                return Redirect::back()->with(['warning' => 'Data Tidak Ditemukan']);
            }
        } catch (\Exception $e) {
            // Menangani Error yang Mungkin Terjadi
            return Redirect::back()->with(['warning' => 'Data Gagal Diperbarui: ' . $e->getMessage()]);
        }
    }

    // Menghapus Departemen
    public function destroy($kode_dept)
    {
        // Cari Departemen berdasarkan kode_dept
        $departemen = Departemen::find($kode_dept);

        if ($departemen) {
            $departemen->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Tidak Ditemukan']);
        }
    }

    
}
