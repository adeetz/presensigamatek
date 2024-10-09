<!-- resources/views/departemen/index.blade.php -->

@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        Data Departemen
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
                                    <div class="container-fluid">
                                        <div class="page-header d-print-none">
                                            <div class="row align-items-center">
                                            </div>
                                            <!-- Flash Messages -->
                                            <div class="mb-3">
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

                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul class="mb-0">
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Tambah Data Button -->
                                            <div class="mb-3">
                                                <a href="#" class="btn btn-primary" id="btnTambahDepartemen">
                                                    <!-- Icon SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icon-tabler-plus me-2">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 5l0 14" />
                                                        <path d="M5 12l14 0" />
                                                    </svg>
                                                    Tambah Data
                                                </a>
                                            </div>

                                            <!-- Form Pencarian (Opsional) -->
                                            <div class="mb-3">
                                                <form action="{{ route('departemen.index') }}" method="GET">
                                                    <div class="row g-2">
                                                        <div class="col-md-10">
                                                            <input type="text" name="nama_dept" id="nama_dept"
                                                                class="form-control" placeholder="Cari Nama Departemen"
                                                                value="{{ Request('nama_dept') }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-primary">
                                                                <!-- Icon SVG -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icon-tabler-search me-2">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                                    <path d="M21 21l-6 -6" />
                                                                </svg>
                                                                Cari
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- Tabel Data Departemen -->
                                            <div class="col-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 0.9rem; text-align: center;">No</th>
                                                            <th style="font-size: 0.9rem; text-align: center;">Kode
                                                                Departemen
                                                            </th>
                                                            <th style="font-size: 0.9rem; text-align: center;">Nama
                                                                Departemen
                                                            </th>
                                                            <th style="font-size: 0.9rem; text-align: center;">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($departemen as $d)
                                                            <tr>
                                                                <td style="text-align: center;">{{ $loop->iteration }}</td>
                                                                <td style="text-align: center;">{{ $d->kode_dept }}</td>
                                                                <td style="text-align: center;">{{ $d->nama_dept }}</td>
                                                                <td style="text-align: center;">
                                                                    <div class="d-flex justify-content-center gap-2">
                                                                        <!-- Tombol Edit -->
                                                                        <a href="#"
                                                                            class="btn btn-info btn-sm d-flex align-items-center edit"
                                                                            data-kode_dept="{{ $d->kode_dept }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="16"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" class="me-1">
                                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                                    fill="none" />
                                                                                <path
                                                                                    d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                                <path
                                                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                                <path d="M16 5l3 3" />
                                                                            </svg>
                                                                            Edit
                                                                        </a>
                                                                        <!-- Tombol Hapus -->
                                                                        <form
                                                                            action="{{ route('departemen.destroy', $d->kode_dept) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-sm d-flex align-items-center delete-confirm">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="16" height="16"
                                                                                    viewBox="0 0 24 24" fill="currentColor"
                                                                                    class="me-1">
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

                                            <div class="modal modal-blur fade" id="modal-inputdepartemen" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tambah Departemen</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('departemen.store') }}" method="POST"
                                                                id="frmDepartemen" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="input-icon mb-3">
                                                                            <span class="input-icon-addon">
                                                                                <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                                        fill="none" />
                                                                                    <path
                                                                                        d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                                                                    <path
                                                                                        d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                                                    <path d="M15 8l2 0" />
                                                                                    <path d="M15 12l2 0" />
                                                                                    <path d="M7 16l10 0" />
                                                                                </svg>
                                                                            </span>
                                                                            <input type="text" value=""
                                                                                id="kode_dept" class="form-control"
                                                                                name="kode_dept"
                                                                                placeholder="Kode Departemen">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="input-icon mb-3">
                                                                            <span class="input-icon-addon">
                                                                                <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                                        fill="none" />
                                                                                    <path
                                                                                        d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                                                    <path
                                                                                        d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                                                </svg>
                                                                            </span>
                                                                            <input type="text" value=""
                                                                                id="nama_dept" class="form-control"
                                                                                name="nama_dept"
                                                                                placeholder="Nama Departemen">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <button class="btn btn-primary w-100"> <svg
                                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                                        width="24" height="24"
                                                                                        viewBox="0 0 24 24" fill="none"
                                                                                        stroke="currentColor"
                                                                                        stroke-width="2"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-send">
                                                                                        <path stroke="none"
                                                                                            d="M0 0h24v24H0z"
                                                                                            fill="none" />
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
                                            <!-- Modal Edit Departemen -->
                                            <div class="modal modal-blur fade" id="modal-editdepartemen" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Data Departemen</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="loadeditform">
                                                            <!-- Form edit akan dimuat melalui AJAX -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endsection
                                    @push('myscript')
                                        <script>
                                            $(document).ready(function() {
                                                // Toggle Menu
                                                $("#menu-toggle").click(function(e) {
                                                    e.preventDefault();
                                                    $("#wrapper").toggleClass("toggled");
                                                });

                                                // Event klik tombol tambah departemen
                                                $("#btnTambahDepartemen").click(function() {
                                                    $("#modal-inputdepartemen").modal("show");
                                                });

                                                // Event klik tombol edit departemen
                                                $(".edit").click(function(e) {
                                                    e.preventDefault();
                                                    var kode_dept = $(this).data('kode_dept');
                                                    $.ajax({
                                                        type: 'GET',
                                                        url: '{{ route('departemen.edit') }}',
                                                        data: {
                                                            kode_dept: kode_dept
                                                        },
                                                        success: function(respond) {
                                                            $("#loadeditform").html(respond);
                                                            $("#modal-editdepartemen").modal("show");
                                                        },
                                                        error: function(xhr, status, error) {
                                                            // Menampilkan pesan error jika AJAX gagal
                                                            Swal.fire({
                                                                title: 'Error!',
                                                                text: 'Gagal memuat form edit.',
                                                                icon: 'error',
                                                                confirmButtonText: 'OK'
                                                            });
                                                            console.log("Error: " + error);
                                                        }
                                                    });
                                                });

                                                // Konfirmasi Hapus dengan SweetAlert
                                                $(".delete-confirm").click(function(e) {
                                                    var form = $(this).closest('form');
                                                    e.preventDefault();
                                                    Swal.fire({
                                                        title: "Apakah Anda Yakin Data ini Mau Dihapus?",
                                                        text: "Jika Ya Maka Data Akan Terhapus Permanen",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#3085d6",
                                                        cancelButtonColor: "#d33",
                                                        confirmButtonText: "Ya Hapus Saja!"
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            form.submit();
                                                        }
                                                    });
                                                });

                                                // Validasi form tambah departemen
                                                $("#frmTambahDepartemen").submit(function(e) {
                                                    var kode_dept = $("#kode_dept").val().trim();
                                                    var nama_dept = $("#nama_dept").val().trim();

                                                    // Logging nilai input untuk debugging
                                                    console.log('Kode Dept:', kode_dept);
                                                    console.log('Nama Dept:', nama_dept);

                                                    // Validasi jika Kode Dept kosong
                                                    if (kode_dept === "") {
                                                        e.preventDefault(); // Mencegah form submit
                                                        Swal.fire({
                                                            title: 'Warning!',
                                                            text: 'Kode Dept harus diisi!',
                                                            icon: 'warning',
                                                            confirmButtonText: 'OK'
                                                        }).then(() => {
                                                            $("#kode_dept").focus();
                                                        });
                                                        return false;
                                                    }

                                                    // Validasi jika Nama Departemen kosong
                                                    if (nama_dept === "") {
                                                        e.preventDefault(); // Mencegah form submit
                                                        Swal.fire({
                                                            title: 'Warning!',
                                                            text: 'Nama Departemen harus diisi!',
                                                            icon: 'warning',
                                                            confirmButtonText: 'OK'
                                                        }).then(() => {
                                                            $("#nama_dept").focus();
                                                        });
                                                        return false;
                                                    }

                                                    return true; // Submit form jika validasi berhasil
                                                });

                                            });
                                        </script>
                                    @endpush
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
