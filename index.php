<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <!-- Bagian Selamat Datang -->
    <div class="text-center mb-4">
        <h1 class="display-4">Selamat Datang di Aplikasi Seleksi Lomba Matematika</h1>
        <p class="lead">Aplikasi ini dirancang untuk membantu dalam proses pemilihan siswa terbaik berdasarkan beberapa kriteria yang telah ditetapkan.</p>
        <img src="images/logo.png" alt="Math Competition" class="img-fluid rounded-circle" style="width: 150px;">
    </div>

    <!-- Deskripsi Aplikasi -->
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading">Tentang Aplikasi</h4>
        <p>Aplikasi ini menggunakan metode Simple Additive Weighting (SAW) untuk mengelola dan menghitung preferensi siswa secara efisien dan akurat. Berikut adalah fitur utama dari aplikasi ini:</p>
        <ul class="list-unstyled">
            <li><i class="bi bi-check-circle text-success"></i> <strong>Kelola Kriteria:</strong> Menambah, mengedit, dan menghapus kriteria penilaian.</li>
            <li><i class="bi bi-check-circle text-success"></i> <strong>Kelola Alternatif:</strong> Menambah, mengedit, dan menghapus data siswa.</li>
            <li><i class="bi bi-check-circle text-success"></i> <strong>Proses Normalisasi:</strong> Menghitung normalisasi nilai berdasarkan kriteria yang ditetapkan.</li>
            <li><i class="bi bi-check-circle text-success"></i> <strong>Proses Perhitungan Nilai Akhir:</strong> Menghitung nilai akhir dari setiap siswa dan menampilkan hasil akhir berdasarkan peringkat.</li>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- CSS Kustom -->
<style>
    .img-fluid {
        max-width: 150px;
    }
    
    .alert {
        background-color: #eaf4f4;
        border-color: #bce8f1;
    }
    
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }
    
    .text-success {
        color: #28a745;
    }
    
    .display-4 {
        font-size: 3.5rem;
    }
    
    .lead {
        font-size: 1.25rem;
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
