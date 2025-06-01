<div class="container">
    <h2>Data Bangun Ruang</h2>

    <!-- Notifikasi sukses -->
    <div id="success-message" style="display: none;"></div>

    <a href="{{ route('create') }}">Tambah Bangun Ruang</a>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
        <tr>
            <th>Nama</th>
            <th>Gambar</th>
        </tr>
        </thead>
        <tbody id="data-table">
            @foreach($data as $bangun)
            <tr>
                <td>{{ $bangun->nama }}</td>
                <td><img src="{{ asset('storage/' . $bangun->gambar) }}" width="100" alt="{{ $bangun->nama }}"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
