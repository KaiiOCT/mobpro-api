<div class="container">
    <h2>Tambah Bangun Ruang</h2>

    <form action="/bangun-ruang/store" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Nama:</label>
            <input type="text" name="nama" value="{{ old('nama') }}" required>
        </div>
        <div>
            <label>Gambar:</label>
            <input type="file" name="gambar" required>
        </div>
        <button type="submit">Simpan</button>
    </form>
</div>
