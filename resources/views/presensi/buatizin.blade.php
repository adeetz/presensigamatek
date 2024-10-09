@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal{
        max-height: 430px !important;
    }
    .datepicker-date-display{
        background-color: #0f3a7e !important;
    }
</style>
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Izin</div>
    <div class="right"></div>
</div>
@endsection
@section('content')
 <div class="row" style="margin-top: 70px">
    <div class="col">
        <form method="POST" action="/presensi/storeizin" id="frm-izin">
            @csrf
            <div class="form-group">
                <input type="text" id="tgl_izin" name="tgl_izin" class="form-control datepicker" placeholder="Tanggal">
            </div>
            <div class="form-group">
                <select name="status" id="status" class="form-control">
                    <option value="">Izin / Sakit</option>
                    <option value="i">Izin</option>
                    <option value="s">Sakit</option>
                </select>
            </div>
            <div class="form-group">
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="keterangan"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary w-100">Kirim</button>
            </div>
        </form>
    </div>
 </div>
@endsection
@push('myscript')
    <script>
        var currYear = (new Date()).getFullYear();
        $(document).ready(function() {
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd"    
    });

    $("#tgl_izin").change(function(e){
    var tgl_izin = $(this).val();
    $.ajax({
        type: 'POST',
        url: '/presensi/cekpengajuanizin',
        data: {
            _token: "{{ csrf_token() }}",
            tgl_izin: tgl_izin
        },
        cache: false,
        success: function(respond) {
            if (respond == 1) {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Anda Sudah Melakukan Input pada Tanggal Tersebut',
                    icon: 'warning'
                }).then((result)=>{
                    $("#tgl_izin").val("");
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memproses permintaan.',
                icon: 'error'
            });
        }
    });
});


            $("#frm-izin").submit(function(event) {
            var tgl_izin = $("#tgl_izin").val(); 
            var status = $('#status').val();
            var keterangan = $('#keterangan').val();

            // Validasi input
            if (tgl_izin == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Tanggal harus diisi',
                    icon: 'warning'
                });
                event.preventDefault(); // Cegah form di-submit
                return false;
            } else if (status == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Status harus diisi',
                    icon: 'warning'
                });
                event.preventDefault();
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Keterangan harus diisi',
                    icon: 'warning'
                });
                event.preventDefault();
                return false;
            }
        });



    });
    </script>
@endpush