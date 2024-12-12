<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manajemen Mahasiswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .title {
            font-size: 24px;
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .btn-primary, .btn-success, .btn-danger {
            border-radius: 5px;
        }
        .table th, .table td {
            text-align: center;
        }
        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <!-- Pesan Notifikasi -->
    <div id="alertMessage" class="alert d-none"></div>

    <!-- Form Tambah Mahasiswa -->
    <div>
        <h2 class="title">Tambah Mahasiswa</h2>
        <form id="addStudentForm">
            @csrf
            <div class="mb-3">
                <label for="npm" class="form-label">NPM:</label>
                <input type="text" name="npm" id="npm" class="form-control" placeholder="Masukkan NPM" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama:</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama" required>
            </div>
            <div class="mb-3">
                <label for="prodi" class="form-label">Program Studi:</label>
                <input type="text" name="prodi" id="prodi" class="form-control" placeholder="Masukkan Program Studi" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Mahasiswa</button>
        </form>
    </div>

    <!-- Tabel Daftar Mahasiswa -->
    <div class="mt-4">
        <h2 class="title">Daftar Mahasiswa</h2>
        <div class="text-end mb-3">
            <a href="{{ route('mahasiswa.export') }}" class="btn btn-success">Export Data ke Excel</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>NPM</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="studentTable">
                @foreach($mahasiswa as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->npm }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->prodi }}</td>
                        <td>
                            <form action="{{ route('mahasiswa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function () {
    $('#addStudentForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('mahasiswa.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    // Menambahkan row baru ke tabel menggunakan data dari response
                    $('#studentTable').append(`
                        <tr>
                            <td>${response.data.id}</td>
                            <td>${response.data.npm}</td>
                            <td>${response.data.nama}</td>
                            <td>${response.data.prodi}</td>
                            <td>
                                <form action="/mahasiswa/${response.data.id}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    `);

                    // Reset form setelah berhasil
                    $('#addStudentForm')[0].reset();

                    // Optional: Tampilkan pesan sukses jika diinginkan
                    $('#alertMessage').removeClass('d-none').addClass('alert-success').text('Mahasiswa berhasil ditambahkan!');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText); // Debugging jika ada error
                alert('Gagal menambahkan data!');
            }
        });
    });
});
</script>
</body>
</html>
