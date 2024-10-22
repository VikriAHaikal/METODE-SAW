<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Metode SAW</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #E0412F;
        }
        .navbar-custom .navbar-brand {
            color: #ffffff;
            transition: none; /* Hilangkan transisi warna */
        }
        .navbar-custom .navbar-toggler-icon {
            background-color: #ffffff;
        }
        .navbar-custom .navbar-nav {
            margin-left: 0; /* Hilangkan margin yang menyebabkan tampilan terlalu rapat */
        }
        .navbar-custom .navbar-nav .nav-item {
            margin-right: 10px; /* Sesuaikan sesuai kebutuhan */
        }
        .navbar-custom .navbar-nav .nav-item:hover .nav-link {
            color: #f0ad4e; /* Ganti warna saat hover */
        }
        .navbar-custom .navbar-nav .nav-item .nav-link {
            color: #ffffff;
            transition: color 0.3s ease; /* Efek smooth transition pada warna teks */
        }
        /* Menghindari perubahan warna pada .navbar-brand */
        .navbar-custom .navbar-brand:hover,
        .navbar-custom .navbar-brand:focus,
        .navbar-custom .navbar-brand:active {
            color: #ffffff !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            Dashboard
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kriteria.php"><i class="fas fa-cogs"></i> Kriteria</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="alternatif.php"><i class="fas fa-users"></i> Alternatif</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="normalisasi.php"><i class="fas fa-chart-bar"></i> Normalisasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="preferensi.php"><i class="fas fa-star"></i> Preferensi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-door-open"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <!-- Sisa kode PHP Anda -->
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
