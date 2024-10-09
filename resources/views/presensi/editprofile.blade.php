@extends('layouts.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Profile</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top:4rem">
    <div class="col">
    @php
        $messagesuccess = Session::get('success');
        $messageerror = Session::get('error');
    @endphp
    @if ($messagesuccess)
        <div class="alert alert-success">
            {{ $messagesuccess }}
        </div>
    @endif
    @if ($messageerror)
        <div class="alert alert-danger">
            {{ $messageerror }}
        </div>
    @endif
    </div>
</div>

<form action="{{ url('/presensi/' . $karyawan->nik . '/updateprofile') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="col">
        <!-- Nama Lengkap -->
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ $karyawan->nama_lengkap }}" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off">
            </div>
        </div>
        
        <!-- No HP -->
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ $karyawan->no_hp }}" name="no_hp" placeholder="No. HP" autocomplete="off">
            </div>
        </div>
        
        <!-- Password -->
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password (isi jika ingin mengubah)" autocomplete="off">
                <span class="input-icon" onclick="togglePassword()">
                    <ion-icon id="togglePasswordIcon" name="eye-off-outline"></ion-icon>
                </span>
            </div>
        </div>
        
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const toggleIcon = document.getElementById('togglePasswordIcon');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.setAttribute('name', 'eye-outline');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.setAttribute('name', 'eye-off-outline');
                }
            }
        </script>
        <style>
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;  /* Atur jarak dari tepi kanan */
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 1;  /* Pastikan ikon tetap di atas input */
        }
        </style>
        
        <!-- Upload Foto -->
        <div class="custom-file-upload" id="fileUpload1">
            <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg">
            <label for="fileuploadInput">
                <span>
                    <strong>
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                        <i>Tap to Upload</i>
                    </strong>
                </span>
            </label>
        </div>
        <!-- Submit Button -->
        <div class="form-group boxed">
            <div class="input-wrapper">
                <button type="submit" class="btn btn-primary btn-block">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
