<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <style>
        @page {
            size: A4;
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .tabeldatakaryawan {
            margin-top: 40px;
            border-collapse: collapse;
        }

        .tabeldatakaryawan td {
            padding: 6px;
        }

        .karyawan-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .tabelpresensi {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            text-align: left;
        }

        .tabelpresensi th,
        .tabelpresensi td {
            border: 1px solid #131212;
            padding: 8px;
        }

        .tabelpresensi th {
            background-color: #f2f2f2;
        }

        .tabelpresensi td {
            font-size: 14px;
        }

        .foto-presensi {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        /* Add spacing above the signatures */
        .signature-section {
            margin-top: 50px; /* Adjust this value as needed */
        }
    </style>
</head>

<body class="A4">
    <?php
    function selisih($jam_masuk, $jam_keluar)
    {
        [$h, $m, $s] = explode(':', $jam_masuk);
        $dtAwal = mktime($h, $m, $s, 1, 1, 1970);
        [$h, $m, $s] = explode(':', $jam_keluar);
        $dtAkhir = mktime($h, $m, $s, 1, 1, 1970);
        $dtSelisih = $dtAkhir - $dtAwal;

        $totalmenit = $dtSelisih / 60;
        $jam = floor($totalmenit / 60);
        $sisamenit = $totalmenit % 60;
        return sprintf('%02d:%02d', $jam, round($sisamenit));
    }
    ?>

    <section class="sheet padding-10mm">
        <table style="width: 100%">
            <tr>
                <td style="width: 70px">
                    <img src="{{ asset('assets/img/logopresensi.png') }}" width="100" height="100" alt="Logo Presensi">
                </td>
                <td style="text-align: center;">
                    <span id="title">
                        LAPORAN PRESENSI KARYAWAN <br>
                        PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
                        PT. GARUDA MAHADHIKA TEKNOLOGI <br>
                    </span>
                    <span><i>Jln. Ahmad Yani Km 32,5 Kecamatan Banjarbaru Utara, Kota Banjarbaru</i></span>
                </td>
            </tr>
        </table>

        <table class="tabeldatakaryawan">
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <td>Nama Karyawan</td>
                <td>:</td>
                <td>{{ $karyawan->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $karyawan->jabatan }}</td>
            </tr>
            <tr>
                <td>Departemen</td>
                <td>:</td>
                <td>{{ $karyawan->nama_dept }}</td>
            </tr>
            <tr>
                <td>No Hp/Whatsapp</td>
                <td>:</td>
                <td>{{ $karyawan->no_hp }}</td>
            </tr>
        </table>

        <table class="tabelpresensi">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Keterangan</th>
                <th>Jml Jam</th>
            </tr>
            @foreach ($presensi as $d)
                @php
                    $jamterlambat = selisih('09:00:00', $d->jam_in);
                    $jmljamkerja = 0;
                    if ($d->jam_out != null) {
                        $jmljamkerja = selisih($d->jam_in, $d->jam_out);
                    }
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                    <td>{{ $d->jam_in }}</td>
                    <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                    <td>
                        @if ($d->jam_in > '09:00')
                            Terlambat {{ $jamterlambat }}
                        @else
                            Tepat Waktu
                        @endif
                    </td>
                    <td>{{ $jmljamkerja }}</td>
                </tr>
            @endforeach
        </table>

        <!-- Signature Section -->
        <div class="signature-section">
            <table width="100%">
                <tr>
                    <td style="text-align: center; height: 30px; width: 50%; padding-right: 20px;">
                       <br>
                        <u>M. Noor Aditya Rahman</u><br>
                        <i><b>Manager</b></i>
                    </td>
                    <td style="text-align: center; height: 30px; width: 50%; padding-left: 20px;">
                        Banjarbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <!-- Translated date format -->
                        <br><br>

                        <br>
                        <u>Irfan Noor Asyikin</u><br>
                        <i><b>Direktur</b></i>
                    </td>
                </tr>
            </table>
        </div>             
            
        </div>
        
        
    </section>

</body>

</html>
