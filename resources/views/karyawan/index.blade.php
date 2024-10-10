@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        Data Karyawan
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    @if (Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif
                                    @if (Session::get('warning'))
                                        <div class="alert alert-warning">
                                            {{ Session::get('warning') }}
                                        </div>
                                    @endif
                                    @if ($errors->has('nik'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('nik') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="#" class="btn btn-primary" id="btnTambahkaryawan">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        Tambah Data </a>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <form action="/karyawan" method="GET">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <input type="text" name="nama_karyawan" id="nama_karyawan"
                                                        class="form-control" placeholder="nama karyawan"
                                                        value="{{ Request('nama_karyawan') }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <select name="kode_dept" id="kode_dept" class="form-select">
                                                        <option value="">Departemen</option>
                                                        @foreach ($departemen as $d)
                                                            <option value="{{ $d->kode_dept }}"
                                                                {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                                                {{ $d->nama_dept }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                            <path d="M21 21l-6 -6" />
                                                        </svg>
                                                        Cari
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="font-size: 0.9rem; text-align: center;">No</th>
                                                <th style="font-size: 0.9rem; text-align: center;">NIK</th>
                                                <th style="font-size: 0.9rem; text-align: center;">Nama</th>
                                                <th style="font-size: 0.9rem; text-align: center;">Jabatan</th>
                                                <th style="font-size: 0.9rem; text-align: center;">No HP</th>
                                                <th style="font-size: 0.9rem; text-align: center;">Foto</th>
                                                <th style="font-size: 0.9rem; text-align: center;">Departemen</th>
                                                <th style="font-size: 0.9rem; text-align: center;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($karyawan as $d)
                                                @php
                                                    $path = Storage::url('uploads/karyawan/' . $d->foto); // Perbaikan penulisan path
                                                @endphp
                                                <tr>
                                                    <td style="font-size: 0.9rem; text-align: center;">
                                                        {{ $loop->iteration + $karyawan->firstItem() - 1 }}</td>
                                                    <!-- Nomor urut -->
                                                    <td style="font-size: 0.9rem; text-align: center;">{{ $d->nik }}
                                                    </td> <!-- NIK karyawan -->
                                                    <td style="font-size: 0.9rem; text-align: center;">
                                                        {{ $d->nama_lengkap }}</td> <!-- Nama lengkap karyawan -->
                                                    <td style="font-size: 0.9rem; text-align: center;">{{ $d->jabatan }}
                                                    </td> <!-- Jabatan karyawan -->
                                                    <td style="font-size: 0.9rem; text-align: center;">{{ $d->no_hp }}
                                                    </td> <!-- Nomor HP karyawan -->
                                                    <td style="font-size: 0.9rem; text-align: center;">
                                                        @if (empty($d->foto))
                                                            <img src="{{ asset('assets/img/nofoto.png') }}" class="avatar"
                                                                alt="">
                                                        @else
                                                            <img src="{{ url($path) }}" alt="avatar"
                                                                style="width: 50px; height: auto;">
                                                        @endif

                                                    </td> <!-- Foto karyawan -->
                                                    <td style="font-size: 0.9rem; text-align: center;">{{ $d->nama_dept }}
                                                    </td> <!-- Nama departemen -->
                                                    <td style="font-size: 0.9rem; text-align: center;">
                                                        <div class="btn-group" role="group" aria-label="Action Buttons">
                                                            <!-- Tombol Edit -->
                                                            <a href="#" class="edit btn btn-info btn-sm"
                                                                nik="{{ $d->nik }}" <svg
                                                                xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icon-tabler-edit">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                <path
                                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                <path d="M16 5l3 3" />
                                                                </svg>
                                                                Edit
                                                            </a>
                                                            <!-- Tombol Hapus -->
                                                            <form action="{{ route('karyawan.destroy', $d->nik) }}"
                                                                method="POST"
                                                                style="display: inline-block; margin-left: 5px;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm delete-confirm">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        fill="currentColor" `
                                                                        class="icon icon-tabler icon-tabler-trash">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" />
                                                                        <path
                                                                            d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" />
                                                                    </svg>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Penutupan tag table dipindahkan ke tempat yang benar -->
                            {{ $karyawan->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/karyawan/store" method="POST" id="frmKaryawan" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                            <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M15 8l2 0" />
                                            <path d="M15 12l2 0" />
                                            <path d="M7 16l10 0" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="nik" class="form-control"
                                        name="nik" placeholder="Nik">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="nama_lengkap" class="form-control"
                                        name="nama_lengkap" placeholder="Nama Lengkap">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-barcode">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 7v-1a2 2 0 0 1 2 -2h2" />
                                                <path d="M4 17v1a2 2 0 0 0 2 2h2" />
                                                <path d="M16 4h2a2 2 0 0 1 2 2v1" />
                                                <path d="M16 20h2a2 2 0 0 0 2 -2v-1" />
                                                <path d="M5 11h1v2h-1z" />
                                                <path d="M10 11l0 2" />
                                                <path d="M14 11h1v2h-1z" />
                                                <path d="M19 11l0 2" />
                                            </svg>
                                        </span>
                                        <input type="text" value="" id="jabatan" class="form-control"
                                            name="jabatan" placeholder="Jabatan">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                            </svg>
                                        </span>
                                        <input type="text" value="" id="no_hp" class="form-control"
                                            name="no_hp" placeholder="No Hp">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <input type="file" name="foto" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <select name="kode_dept" id="kode_dept" class="form-select">
                                    <option value="">Departemen</option>
                                    @foreach ($departemen as $d)
                                        <option value="{{ $d->kode_dept }}"
                                            {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                            {{ $d->nama_dept }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <button class="btn btn-primary w-100"> <svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-send">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 14l11 -11" />
                                            <path
                                                d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                        </svg> Simpan </button>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    {{-- Modal Edit --}}

    <div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadeditform">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            // Event klik tombol tambah karyawan
            $("#btnTambahkaryawan").click(function() {
                $("#modal-inputkaryawan").modal("show");
            });

            // Event klik tombol edit karyawan
            $(".edit").click(function() {
                var nik = $(this).attr('nik');
                console.log("Editing NIK: ", nik); // Log NIK yang diklik
                $.ajax({
                    type: 'GET', // Ubah metode menjadi GET
                    url: '/karyawan/' + nik + '/edit', // Ubah URL untuk menyertakan NIK
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}" // Token CSRF masih diperlukan untuk permintaan GET di Laravel
                    },
                    success: function(respond) {
                        console.log("Response: ", respond); // Log response dari server
                        $("#loadeditform").html(respond);
                        $("#modal-editkaryawan").modal("show");
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: ", error); // Log error jika AJAX gagal
                    }
                });
            });

            $(".delete-confirm").click(function(e) {
                var form = $(this).closest('form');
                e.preventDefault(),
                    Swal.fire({
                        title: "Apakah Anda Yakin Data ini Mau Dihapus ?",
                        text: "Jika Ya Maka Data Akan Terhapus Permanen",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya Hapus Saja!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                            Swal.fire({
                                title: "Deleted!",
                                text: "Data Berhasil Dihapus",
                                icon: "success"
                            });
                        }
                    });

            });



            // Validasi form karyawan
            $("#frmKaryawan").submit(function(e) {
                var nik = $("#nik").val();
                var nama_lengkap = $("#nama_lengkap").val();
                var jabatan = $("#jabatan").val();
                var kode_dept = $("frmKaryawan").find("#kode_dept").val();
                var no_hp = $("#no_hp").val();

                // Validasi jika NIK kosong
                if (nik == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'NIK harus diisi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#nik").focus();
                    });
                    return false;
                }

                // Validasi jika Nama Lengkap kosong
                if (nama_lengkap == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nama Lengkap harus diisi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#nama_lengkap").focus();
                    });
                    return false;
                }

                // Validasi jika Jabatan kosong
                if (jabatan == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jabatan harus diisi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#jabatan").focus();
                    });
                    return false;
                }

                // Validasi jika No HP kosong
                if (no_hp == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'No HP harus diisi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#no_hp").focus();
                    });
                    return false;
                }

                // Validasi jika Departemen kosong
                if (kode_dept == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Departemen harus diisi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#kode_dept").focus();
                    });
                    return false;
                }

                return true; // Lolos validasi, form bisa di-submit
            });

        });
    </script>
@endpush
