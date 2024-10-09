<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Models\Pengajuanizin;

class PresensiController extends Controller
{
    public function create()
{
    $hariini = date("Y-m-d");
    $nik = Auth::guard('karyawan')->user()->nik;

    // Cek presensi berdasarkan tanggal dan NIK karyawan
    $cek = DB::table('presensi')
        ->where('tgl_presensi', $hariini)
        ->where('nik', $nik)
        ->count();

    // Ambil lokasi kantor berdasarkan ID
    $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

    return view('presensi.create', compact('cek', 'lok_kantor'));
}


    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'lokasi' => 'required|string',
        ]);

        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $tgl_presensi = date("Y-m-d");
            $jam = now()->format('H:i:s');
            $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
            $lok = explode(",", $lok_kantor->lokasi_kantor);
            $latitudekantor = $lok[0];
            $longitudekantor = $lok [1];
            $lokasi = $request->lokasi;
            $lokasiuser = explode(",", $lokasi);
            $latitudeuser = $lokasiuser[0];
            $longitudeuser = $lokasiuser[1];

            $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
            $radius = round($jarak["meters"]);

            if ($radius > $lok_kantor->radius) {
                return response()->json([
                    'status' => 1,
                    'message' => 'Anda berada di luar radius. Jarak Anda ' . $radius . ' meter dari kantor.',
                ]);
            }

            $image = $request->image;

            $existingPresensi = DB::table('presensi')
                ->where('nik', $nik)
                ->where('tgl_presensi', $tgl_presensi)
                ->first();

            if ($existingPresensi) {
                if (is_null($existingPresensi->jam_out)) {
                    return $this->handlePresensiOut($existingPresensi, $jam, $image, $lokasi, $nik, $tgl_presensi);
                } else {
                    return response()->json(['status' => 1, 'message' => 'Karyawan sudah melakukan presensi pulang hari ini']);
                }
            } else {
                return $this->handlePresensiIn($jam, $image, $lokasi, $nik, $tgl_presensi);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 1, 'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()]);
        }
    }

    private function handlePresensiIn($jam, $image, $lokasi, $nik, $tgl_presensi)
    {
        $folderPath = "public/uploads/absensi/";
        $fileNameIn = $nik . "_" . $tgl_presensi . "_in.png";
        $filePathIn = $folderPath . $fileNameIn;

        $image_parts = explode(";base64,", $image);
        if (count($image_parts) < 2) {
            return response()->json(['status' => 1, 'message' => 'Invalid image format']);
        }

        $image_base64 = base64_decode($image_parts[1]);
        Storage::put($filePathIn, $image_base64);

        $presensi = new Presensi();
        $presensi->nik = $nik;
        $presensi->tgl_presensi = $tgl_presensi;
        $presensi->jam_in = $jam;
        $presensi->foto_in = $fileNameIn;
        $presensi->lokasi_in = $lokasi;

        if ($presensi->save()) {
            return response()->json(['status' => 0, 'message' => 'Terima Kasih, Selamat bekerja!']);
        } else {
            return response()->json(['status' => 1, 'message' => 'Gagal menyimpan presensi masuk']);
        }
    }

    private function handlePresensiOut($existingPresensi, $jam, $image, $lokasi, $nik, $tgl_presensi)
    {
        $folderPath = "public/uploads/absensi/";
        $fileNameOut = $nik . "_" . $tgl_presensi . "_out.png";
        $filePathOut = $folderPath . $fileNameOut;

        $image_parts = explode(";base64,", $image);
        if (count($image_parts) < 2) {
            return response()->json(['status' => 1, 'message' => 'Invalid image format']);
        }

        $image_base64 = base64_decode($image_parts[1]);
        Storage::put($filePathOut, $image_base64);

        DB::table('presensi')
            ->where('tgl_presensi', $tgl_presensi)
            ->where('nik', $nik)
            ->update([
                'jam_out' => $jam,
                'foto_out' => $fileNameOut,
                'lokasi_out' => $lokasi,
            ]);

        return response()->json(['status' => 0, 'message' => 'Terima kasih, hati-hati di jalan!']);
    }

    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request)
    {
        // Mendapatkan NIK dari karyawan yang sedang login
        $nik = Auth::guard('karyawan')->user()->nik;

        // Ambil input dari form
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;

        // Ambil data karyawan dari database berdasarkan NIK
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        // Jika ada file foto baru
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();

            // Hapus foto lama jika ada
            if ($karyawan->foto && Storage::exists("public/uploads/karyawan/{$karyawan->foto}")) {
                Storage::delete("public/uploads/karyawan/{$karyawan->foto}");
            }
        } else {
            // Jika tidak ada foto baru, gunakan foto lama
            $foto = $karyawan->foto;
        }

        // Data yang akan diupdate
        $data = [
            'nama_lengkap' => $nama_lengkap,
            'no_hp' => $no_hp,
            'foto' => $foto, // Update kolom foto juga
        ];

        // Jika ada input password, tambahkan password yang di-hash ke data yang diupdate
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update data karyawan di database
        $update = DB::table('karyawan')->where('nik', $nik)->update($data);

        if ($update) {
            // Jika ada file foto baru, simpan file ke direktori yang ditentukan
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } else {
            // Redirect dengan pesan error jika gagal
            return redirect()->back()->with('error', 'Failed to update profile.');
        }
    }

    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $nik = Auth::guard('karyawan')->user()->nik;

        // Ambil histori berdasarkan bulan, tahun, dan nik
        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulan])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahun])
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        // Generate HTML untuk histori
        return view('presensi.gethistori', compact('histori'));
    }

    public function izin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')->where('nik', $nik)->get();
        return view('presensi.izin', compact('dataizin'));

    }

    public function buatizin(Request $request)
    {
        return view('presensi.buatizin');
    }
    public function storeizin(Request $request)
    {
        // Ambil data dari request
        $nik_izin = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin; // Ambil dari request input tgl_izin
        $status = $request->status; // Ambil dari request input status
        $keterangan = $request->keterangan;

        // Data yang akan disimpan
        $data = [
            'nik' => $nik_izin,
            'tgl_izin' => $tgl_izin, // Ini harus $tgl_izin, bukan $status
            'status' => $status, // Tambahkan field status jika perlu
            'keterangan' => $keterangan,
        ];

        // Simpan data ke database
        $simpan = DB::table('pengajuan_izin')->insert($data);

        // Cek apakah penyimpanan berhasil
        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;

        $presensi = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept') // Gabungkan tabel presensi dengan tabel karyawan
            ->select('presensi.*', 'nama_lengkap', 'nama_dept') // Pilih semua data dari presensi dan nama_karyawan dari tabel karyawan
            ->where('tgl_presensi', $tanggal) //IINFO: Filter berdasarkan tanggal yang dikirimkan oleh request
            ->get();
        return view('presensi.getpresensi', compact('presensi')); // Return data presensi dalam format JSON
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;

        // Pastikan nama tabel dalam tanda kutip
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->first();

        // Cek apakah data ditemukan
        if (!$presensi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }

    public function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $namabulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        // Mengambil data karyawan dan departemen
        $karyawan = DB::table('karyawan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('karyawan.nik', $nik)
            ->first();

        // Cek jika data karyawan tidak ditemukan
        if (!$karyawan) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        // Mengambil data presensi
        $presensi = DB::table('presensi')
            ->where('nik', $nik)
            ->whereMonth('tgl_presensi', $bulan) // Menggunakan whereMonth
            ->whereYear('tgl_presensi', $tahun) // Menggunakan whereYear
            ->orderBy('tgl_presensi')
            ->get();

        // Mengirim data ke view
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
{
    // Validasi input bulan dan tahun
    $request->validate([
        'bulan' => 'required|integer|between:1,12',
        'tahun' => 'required|integer|min:1900',
    ]);

    $bulan = $request->bulan;
    $tahun = $request->tahun;

    $namabulan = [
        1 => "Januari",
        2 => "Februari",
        3 => "Maret",
        4 => "April",
        5 => "Mei",
        6 => "Juni",
        7 => "Juli",
        8 => "Agustus",
        9 => "September",
        10 => "Oktober",
        11 => "November",
        12 => "Desember",
    ];
    
        $rekap = DB::table('presensi')
            ->selectRaw('presensi.nik,
            karyawan.nama_lengkap,
            MAX(IF(DAY(tgl_presensi) = 1, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_1,
            MAX(IF(DAY(tgl_presensi) = 2, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_2,
            MAX(IF(DAY(tgl_presensi) = 3, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_3,
            MAX(IF(DAY(tgl_presensi) = 4, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_4,
            MAX(IF(DAY(tgl_presensi) = 5, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_5,
            MAX(IF(DAY(tgl_presensi) = 6, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_6,
            MAX(IF(DAY(tgl_presensi) = 7, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_7,
            MAX(IF(DAY(tgl_presensi) = 8, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_8,
            MAX(IF(DAY(tgl_presensi) = 9, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_9,
            MAX(IF(DAY(tgl_presensi) = 10, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_10,
            MAX(IF(DAY(tgl_presensi) = 11, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_11,
            MAX(IF(DAY(tgl_presensi) = 12, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_12,
            MAX(IF(DAY(tgl_presensi) = 13, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_13,
            MAX(IF(DAY(tgl_presensi) = 14, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_14,
            MAX(IF(DAY(tgl_presensi) = 15, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_15,
            MAX(IF(DAY(tgl_presensi) = 16, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_16,
            MAX(IF(DAY(tgl_presensi) = 17, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_17,
            MAX(IF(DAY(tgl_presensi) = 18, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_18,
            MAX(IF(DAY(tgl_presensi) = 19, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_19,
            MAX(IF(DAY(tgl_presensi) = 20, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_20,
            MAX(IF(DAY(tgl_presensi) = 21, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_21,
            MAX(IF(DAY(tgl_presensi) = 22, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_22,
            MAX(IF(DAY(tgl_presensi) = 23, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_23,
            MAX(IF(DAY(tgl_presensi) = 24, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_24,
            MAX(IF(DAY(tgl_presensi) = 25, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_25,
            MAX(IF(DAY(tgl_presensi) = 26, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_26,
            MAX(IF(DAY(tgl_presensi) = 27, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_27,
            MAX(IF(DAY(tgl_presensi) = 28, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_28,
            MAX(IF(DAY(tgl_presensi) = 29, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_29,
            MAX(IF(DAY(tgl_presensi) = 30, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_30,
            MAX(IF(DAY(tgl_presensi) = 31, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_31')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->whereMonth('tgl_presensi', $bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->groupBy('presensi.nik', 'karyawan.nama_lengkap')
            ->get();
    
            return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));
    }
    public function izinsakit(Request $request)
{
    // Validasi input tanggal 'dari' dan 'sampai'
    $this->validate($request, [
        'dari' => 'nullable|date',
        'sampai' => 'nullable|date|after_or_equal:dari',
    ]);

    // Membuat query dasar
    $query = Pengajuanizin::select(
        'pengajuan_izin.id',
        'pengajuan_izin.tgl_izin', 
        'pengajuan_izin.nik',
        'karyawan.nama_lengkap', 
        'karyawan.jabatan', 
        'pengajuan_izin.status', 
        'pengajuan_izin.status_approved', 
        'pengajuan_izin.keterangan'
    )
    ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');

    // Pengecekan untuk input tanggal 'dari' dan 'sampai'
    if (!empty($request->dari) && !empty($request->sampai)) {
        // Pastikan format tanggal dalam bentuk Y-m-d
        $dari = date('Y-m-d', strtotime($request->dari));
        $sampai = date('Y-m-d', strtotime($request->sampai));
        $query->whereBetween('pengajuan_izin.tgl_izin', [$dari, $sampai]);
    }
    
    if (!empty($request->nik)) {
        $query->where('pengajuan_izin.nik', $request->nik);
    }
    
    if (!empty($request->nama_lengkap)) {
        $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
    }
    
    // Perbaikan: pastikan status_approved juga menghandle '0' dengan lebih baik
    if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
        $query->where('status_approved', $request->status_approved);
    }
    

    // Chaining orderBy setelah if
    $query->orderBy('pengajuan_izin.tgl_izin', 'desc');
    
    // Mengambil hasil query
    $izinsakit = $query->paginate(10);
    $izinsakit->appends($request->all()); 

    // Return view dengan data 'izinsakit'
    return view('presensi.izinsakit', compact('izinsakit'));
}



    public function approveizinsakit(Request $request) {
        // Validasi data yang diterima
        $request->validate([
            'id_izinsakit' => 'required|exists:pengajuan_izin,id', // Pastikan ID izin ada
            'status_approved' => 'required|in:1,2', // Hanya izinkan nilai 1 (disetujui) dan 2 (ditolak)
        ]);
    
        // Ambil data dari request
        $id_izinsakit = $request->input('id_izinsakit');
        $status_approved = $request->input('status_approved');
    
        // Lakukan update pada tabel pengajuan_izin
        $update = DB::table('pengajuan_izin')
            ->where('id', $id_izinsakit)
            ->update(['status_approved' => $status_approved]);
    
        // Cek apakah update berhasil
        if ($update) {
            return redirect()->back()->with('success', 'Status izin/sakit berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status.');
        }
    }

    public function batalizinsakit(Request $request) {
        // Validasi data yang diterima
        $request->validate([
            'id_izinsakit' => 'required|exists:pengajuan_izin,id',
        ]);
    
        // Ambil data dari request
        $id_izinsakit = $request->input('id_izinsakit');
    
        // Lakukan update pada tabel pengajuan_izin untuk membatalkan approval
        $update = DB::table('pengajuan_izin')
            ->where('id', $id_izinsakit)
            ->update(['status_approved' => 0]); // Status dikembalikan ke 0 (pending)
    
        // Cek apakah update berhasil
        if ($update) {
            return response()->json(['success' => true, 'message' => 'Status berhasil dikembalikan ke pending.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status.']);
        }
    }

    public function cekpengajuanizin(Request $request) {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin',$tgl_izin)->count();
        return $cek;
    }

    
    
    
    
}
