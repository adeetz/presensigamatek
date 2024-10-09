<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // Mendapatkan tanggal hari ini
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");

        // Mendapatkan NIK dari pengguna yang sedang login
        $user = Auth::guard('karyawan')->user();  // Sesuaikan guard sesuai kebutuhan Anda
        $nik = $user->nik;

        // Mendapatkan data presensi pengguna pada hari ini
        $presensihariini = DB::table('presensi')
            ->where('nik', $nik)
            ->where('tgl_presensi', $hariini)
            ->first();

        // Mendapatkan histori presensi pengguna pada bulan ini
        $historibulanini = DB::table('presensi')
            ->where('nik', $nik)  // Filter sesuai dengan user yang login
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahunini])
            ->orderBy('tgl_presensi')
            ->get();

        // Mendapatkan rekap presensi
        $rekappresensi = DB::table('presensi')
            ->where('nik', $nik)    
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "09:00",1,0)) as jmlterlambat')  // Hitung jumlah kehadiran
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahunini])
            ->first();  // Mengambil satu baris hasil query

        // Nama-nama bulan dalam bahasa Indonesia
        $namabulan = [
            "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        $leaderboard = DB::table('presensi')
            ->join('karyawan', 'presensi.nik','=', "karyawan.nik")
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();

            $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw("SUM(IF(status = 'i', 1, 0)) as jmlizin, SUM(IF(status = 's', 1, 0)) as jmlsakit")
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_izin) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_izin) = ?', [$tahunini])
            ->where('status_approved', 1)
            ->first();
        

        // Kirim data ke view
        return view('dashboard.dashboard', compact(
            'presensihariini', 
            'historibulanini', 
            'namabulan', 
            'bulanini', 
            'tahunini',
            'rekappresensi',
            'leaderboard',
            'rekapizin'
        ));
    }

    public function dashboardadmin() {
        // Ambil tanggal hari ini dengan format Y-m-d
        $hariini = date("Y-m-d");
    
        // Query untuk mendapatkan jumlah karyawan hadir dan terlambat
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "09:00", 1, 0)) as jmlterlambat') 
            ->where('tgl_presensi', $hariini)
            ->first();  
    
        // Query untuk mendapatkan jumlah izin dan sakit
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw("SUM(IF(status = 'i', 1, 0)) as jmlizin, SUM(IF(status = 's', 1, 0)) as jmlsakit")
            ->where('tgl_izin', $hariini)
            ->where('status_approved', 1) // Pastikan status approved
            ->first();
    
        // Kirim data ke view 'dashboard.dashboardadmin'
        return view('dashboard.dashboardadmin', compact('rekappresensi', 'rekapizin'));
    }
    
}
