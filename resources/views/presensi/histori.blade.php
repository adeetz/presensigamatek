@extends('layouts.presensi')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Histori Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection

@section('content')
    <div class="row" style="margin-top:70px">
        <div class="col">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <select name="bulan" id="bulan" class="form-control">
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
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <select name="tahun" id="tahun" class="form-control">
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
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" id="getdata">
                            <ion-icon name="search-outline"></ion-icon> Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col" id="showhistori">
            <!-- Data histori akan muncul di sini -->
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#getdata").click(function(e) {

                var bulan = $('#bulan').val();
                var tahun = $('#tahun').val();

                // Validasi input
                if (!bulan || !tahun) {
                    $("#showhistori").html(
                        '<div class="alert alert-warning">Silakan pilih bulan dan tahun.</div>');
                    return;
                }

                $.ajax({
                    url: '{{ route('presensi.gethistori') }}', // Menggunakan route name
                    type: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun
                    },
                    success: function(respond) {
                        console.log(respond); // Debugging respons dari server
                        $("#showhistori").html(respond);
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Debugging error
                        $("#showhistori").html(
                            '<div class="alert alert-danger">Terjadi kesalahan saat mengambil data!</div>'
                        );
                    }
                });
            });
        });
    </script>
@endpush
