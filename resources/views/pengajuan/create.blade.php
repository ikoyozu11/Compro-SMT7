<form method="POST" action="{{ route('pengajuan.store') }}">
    @csrf
    <label>Nama Pemohon</label>
    <input type="text" name="nama_pemohon">

    <label>No HP Pemohon</label>
    <input type="text" name="no_hp">

    <label>Nama Anggota Kelompok (pisahkan dengan ;)</label>
    <textarea name="nama_anggota"></textarea>

    <label>Asal Instansi</label>
    <input type="text" name="asal_instansi">

    <label>Jurusan</label>
    <input type="text" name="jurusan">

    <label>Keahlian yang Dipelajari</label>
    <textarea name="keahlian"></textarea>

    <label>Lokasi Magang</label>
    <select name="lokasi_id">
        @foreach($lokasi as $item)
            <option value="{{ $item->id }}">{{ $item->nama_lokasi }}</option>
        @endforeach
    </select>

    <label>Mulai Magang</label>
    <input type="date" name="mulai_magang">

    <label>Selesai Magang</label>
    <input type="date" name="selesai_magang">

    <button type="submit">Ajukan</button>
</form>
