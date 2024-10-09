@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Rekap Presensi
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="card-body">
                <form id="laporanForm" action="{{ route('presensi.cetakrekap') }}" target="_blank" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
                                            {{ $namabulan[$i] }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Tahun</option>
                                    @php
                                        $tahunmulai = 2024;
                                        $tahunskrg = date('Y');
                                    @endphp
                                    @for ($tahun = $tahunmulai; $tahun <= $tahunskrg; $tahun++)
                                        <option value="{{ $tahun }}" {{ date('Y') == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-3">
                            <div class="form-group">
                                <button type="submit" id="btnCetak" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                        <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                                    </svg>
                                    Cetak
                                </button>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <button type="button" id="btnExport" class="btn btn-success w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-file-spreadsheet">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M8 11h8v7h-8z" />
                                        <path d="M8 15h8" />
                                        <path d="M11 11v7" />
                                    </svg>
                                    Eksport to Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan logika JavaScript untuk validasi -->
<script>
    document.getElementById('btnCetak').addEventListener('click', function () {
        var nik = document.getElementById('nik').value;
        if (nik === "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Silakan pilih karyawan sebelum mencetak laporan!',
            });
        }
    });

    document.getElementById('btnExport').addEventListener('click', function () {
        var nik = document.getElementById('nik').value;
        if (nik === "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Silakan pilih karyawan sebelum mengekspor laporan!',
            });
        } else {
            // Tambahkan input hidden untuk exportexcel jika tombol export diklik
            var form = document.getElementById('laporanForm');
            var exportInput = document.createElement('input');
            exportInput.type = 'hidden';
            exportInput.name = 'exportexcel';
            exportInput.value = 'true';
            form.appendChild(exportInput);
            
            form.submit();
        }
    });
</script>
@endsection
