<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <style>
        @page {
            size: A4 landscape; /* Set the page size to landscape */
            margin: 10mm; /* Adjust margin to fit the content */
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .tabelpresensi {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            text-align: center; /* Center text in the table */
        }

        .tabelpresensi th,
        .tabelpresensi td {
            border: 1px solid #131212;
            padding: 4px; /* Adjusted padding */
            font-size: 8px; /* Adjusted font size */
        }

        .tabelpresensi th {
            background-color: #f2f2f2;
        }

        .signature-section {
            margin-top: 50px;
        }

        /* Add page break for long tables */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body class="A4 landscape">

    <section class="sheet padding-10mm">
        <table style="width: 100%">
            <tr>
                <td style="width: 70px">
                    <img src="{{ asset('assets/img/logopresensi.png') }}" width="100" height="100" alt="Logo Presensi">
                </td>
                <td style="text-align: center;">
                    <span id="title">
                        REKAP PRESENSI KARYAWAN <br>
                        PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
                        PT. GARUDA MAHADHIKA TEKNOLOGI <br>
                    </span>
                    <span><i>Jln. Ahmad Yani Km 32,5 Kecamatan Banjarbaru Utara, Kota Banjarbaru</i></span>
                </td>
            </tr>
        </table>

        <table class="tabelpresensi">
            <tr>
                <th rowspan="2">NIK</th>
                <th rowspan="2">Nama Karyawan</th>
                <th colspan="31">Tanggal</th>
                <th rowspan="2">TH</th>
                <th rowspan="2">TT</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 31; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
            @foreach ($rekap as $d)
                <tr>
                    <td>{{ $d->nik }}</td>
                    <td>{{ $d->nama_lengkap }}</td>
                    @php
                        $totalhadir = 0; // Initialize total attendance counter
                        $totalterlambat = 0; // Initialize total lateness counter
                    @endphp
                    @for ($i = 1; $i <= 31; $i++)
                        <td>
                            @php
                                $tgl = 'tgl_' . $i; // Dynamic property name
                                $hadir = empty($d->$tgl) ? ['', ''] : explode('-', $d->$tgl);
                            @endphp

                            @if (isset($hadir[0]) && !empty($hadir[0]))
                                @php
                                    $totalhadir++; // Increment total attendance for each entry
                                @endphp

                                @if ($hadir[0] > '09:00:00')
                                    <span style="color:red;">{{ $hadir[0] }}</span><br> <!-- Late entry -->
                                    @php $totalterlambat++; // Increment late counter if entry is late @endphp
                                @else
                                    {{ $hadir[0] }}<br> <!-- On-time entry -->
                                @endif

                                <!-- Show exit time but do not affect the attendance count -->
                                @if (isset($hadir[1]))
                                    <span style="color:rgb(0, 0, 0);">{{ $hadir[1] }}</span> <!-- Display exit time -->
                                @else
                                    <span>-</span> <!-- Show '-' if there is no jam keluar -->
                                @endif
                            @else
                                <span>-</span> <!-- Show '-' if no attendance data -->
                            @endif
                        </td>
                    @endfor
                    <td>{{ $totalhadir }}</td> <!-- Display total attendance -->
                    <td>{{ $totalterlambat }}</td> <!-- Display total lateness -->
                </tr>
                @if ($loop->iteration % 20 == 0) <!-- Add page break after every 20 rows -->
                    <div class="page-break"></div>
                @endif
            @endforeach
        </table>

        <!-- Signature Section -->
        <div class="signature-section">
            <table width="100%">
                <tr>
                    <td style="text-align: center; width: 50%; padding-right: 20px;">
                        <img src="{{ asset('assets/img/ttdhrd.png') }}" alt="Signature" style="width: 100px; height: auto; margin-bottom: 5px; transform: rotate(30deg);"><br>
                        <u>M. Noor Aditya Rahman</u><br>
                        <i><b>Manager</b></i>
                    </td>
                    <td style="text-align: center; width: 50%; padding-left: 20px;">
                        Banjarbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <!-- Translated date format -->
                        <br><br>
                        <img src="{{ asset('assets/img/ttddirektur.png') }}" alt="Signature" style="width: 200px; height: auto; margin-bottom: 5px;">
                        <br>
                        <u>Irfan Noor Asyikin</u><br>
                        <i><b>Direktur</b></i>
                    </td>
                </tr>
            </table>
        </div>
    </section>

</body>

</html>
