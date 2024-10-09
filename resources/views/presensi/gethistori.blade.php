@if($histori->isEmpty())
<div class="alert alert-warning">
    <p>Data presensi belum ada.</p>
</div>
@else
<ul class="listview image-listview">
    @foreach ($histori as $d)
    <li>
        <div class="item">
            @php
                $path = Storage::url('uploads/absensi/' .$d->foto_in);
            @endphp
            <img src="{{ url($path) }}" alt="Foto Presensi" class="image">
            <div class="in">
                <div>
                    <b>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</b><br>
                </div>
                <span class="badge {{ $d->jam_in < '09:00' ? 'bg-success' : 'bg-danger' }}">
                    {{ $d->jam_in ?? '-' }}
                </span>
                @if(!empty($d->jam_out))
                    <span class="badge bg-primary">{{ $d->jam_out }}</span>
                @else
                    <span class="badge bg-secondary">Belum pulang</span>
                @endif
            </div>
        </div>
    </li>
    @endforeach
</ul>
@endif
