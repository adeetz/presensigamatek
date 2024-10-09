@extends('layouts.presensi')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi Gama Tekno</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->

    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 250px;
            margin-top: 15px;
            /* Jarak atas untuk map */
        }

        .btn-block {
            margin-top: 15px;
            /* Jarak atas untuk tombol absen */
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('content')
    <div id="appCapsule">
        <div class="row" style="margin-top: 70px">
            <div class="col">
                <input type="hidden" id="lokasi">
                <div class="webcam-capture"></div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                @if ($cek > 0)
                    <button id="takeabsen" class="btn btn-danger btn-block">
                        <ion-icon name="camera-outline"></ion-icon> Absen Pulang
                    </button>
                @else
                    <button id="takeabsen" class="btn btn-primary btn-block">
                        <ion-icon name="camera-outline"></ion-icon> Absen Masuk
                    </button>
                @endif
            </div>
        </div>
        <div class="row mt-2">
            <div class="col">
                <div id="map"></div>
            </div>
        </div>
    </div>
@endsection

<audio id="notifikasi_in" src="{{ asset('assets/sound/notifikasi_in.mp3') }}" preload="auto"></audio>
<audio id="notifikasi_out" src="{{ asset('assets/sound/notifikasi_out.mp3') }}" preload="auto"></audio>
<audio id="radius" src="{{ asset('assets/sound/notifikasi_radius.mp3') }}" preload="auto"></audio>

@push('myscript')
    <script>
        // Pengaturan webcam
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80,
        });
        Webcam.attach('.webcam-capture');

        var lokasi = document.getElementById('lokasi');

        // Mengambil geolokasi
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
            var lokasi_kantor = "{{ $lok_kantor->lokasi_kantor }}";
            var lok = lokasi_kantor.split(",");
            var lat_kantor = lok[0];
            var long_kantor = lok[1];
            var radius = "{{ $lok_kantor->radius }}";

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            L.circle([lat_kantor, long_kantor], {
                color: 'yellow',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);
        }

        function errorCallback() {
            alert("Geolocation tidak didukung oleh browser ini atau tidak diizinkan.");
        }
        var image = ""; // Definisikan variabel image di luar snap function

        // Tombol absen (masuk atau pulang)
        $("#takeabsen").click(function(e) {
            e.preventDefault(); // Mencegah form submit otomatis
            Webcam.snap(function(uri) {
                image = uri;
                var lokasi = $('#lokasi').val();
                if (!lokasi || !image) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Lokasi atau foto tidak ditemukan.',
                        icon: 'error'
                    });
                    return;
                }

                // Mengirim data presensi
                $.ajax({
                    type: 'POST',
                    url: '/presensi/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        image: image,
                        lokasi: lokasi
                    },
                    cache: false,
                    success: function(response) {
                        if (response.status == 0) {
                            // Memeriksa pesan dari server apakah presensi masuk atau pulang
                            if (response.message.includes('Selamat bekerja')) {
                                var audioIn = document.getElementById('notifikasi_in');
                                audioIn.play();
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response
                                    .message, // Pesan berhasil presensi masuk
                                    icon: 'success'
                                }).then(function() {
                                    setTimeout(function() {
                                        window.location.href = '/dashboard';
                                    }, 3000);
                                });
                            } else if (response.message.includes(
                                    'Terima kasih, hati-hati di jalan')) {
                                var audioOut = document.getElementById('notifikasi_out');
                                audioOut.play();
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response
                                    .message, // Pesan berhasil presensi pulang
                                    icon: 'success'
                                }).then(function() {
                                    setTimeout(function() {
                                        window.location.href = '/dashboard';
                                    }, 3000);
                                });
                            }
                        } else {
                            var audioError = document.getElementById('radius');
                            audioError.play(); // Mainkan notifikasi audio error
                            Swal.fire({
                                title: 'Error!',
                                text: response
                                .message, // Tampilkan pesan error dari server
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menghubungi server: ' + error,
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@endpush
