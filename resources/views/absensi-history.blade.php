@extends('layout.app')

@section('title', 'Riwayat Absensi')

@section('content')

    <section>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Riwayat</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="absensi-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($absenList); $i++)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $absenList[$i]->tgl }}</td>
                            <td>{{ $absenList[$i]->waktu }}</td>
                            <td>
                                @if(isset($absenList[$i]->status))
                                    <span class="badge {{ $absenList[$i]->status == 'Masuk Terlambat' ? 'bg-warning' : ($absenList[$i]->status == 'Pulang Awal' ? 'bg-info' : ($absenList[$i]->status == 'Masuk' ? 'bg-success' : 'bg-danger')) }}">
                                        {{ $absenList[$i]->status }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

    </section>

@endsection