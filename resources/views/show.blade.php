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
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody id="data-table">
            @foreach($data as $bangun)
            <tr>
                <td>{{ $bangun->nama }}</td>
                <td><img src="{{ asset('storage/' . $bangun->gambar) }}" width="100" alt="{{ $bangun->nama }}"></td>
                <td>
                    <form action="{{ route('destroy', $bangun->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background-color:red; color:white; border:none; padding:5px 10px; cursor:pointer;">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
