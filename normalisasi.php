<?php
include 'includes/header.php';
include 'includes/db.php';

// Mendapatkan data kriteria
$kriteriaResult = $conn->query("SELECT * FROM kriteria");
$kriteria = [];
while ($row = $kriteriaResult->fetch_assoc()) {
    $kriteria[] = $row;
}

// Mendapatkan data alternatif tanpa batasan pagination
$alternatifResult = $conn->query("SELECT * FROM alternatif");
$alternatif = [];
while ($row = $alternatifResult->fetch_assoc()) {
    $alternatif[] = $row;
}

// Mendapatkan data nilai dari database
$nilaiResult = $conn->query("SELECT * FROM nilai");
$nilai = [];
while ($row = $nilaiResult->fetch_assoc()) {
    $nilai[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai'];
}

// Proses input nilai dan normalisasi
if (isset($_POST['hitung'])) {
    $nilai = [];
    foreach ($alternatif as $alt) {
        foreach ($kriteria as $k) {
            $nilai[$alt['id']][$k['id']] = $_POST['nilai'][$alt['id']][$k['id']];
        }
    }

    // Simpan nilai ke database
    $conn->query("TRUNCATE TABLE nilai");
    foreach ($nilai as $id_alternatif => $kriterias) {
        foreach ($kriterias as $id_kriteria => $nilai_input) {
            $conn->query("INSERT INTO nilai (id_alternatif, id_kriteria, nilai) VALUES ('$id_alternatif', '$id_kriteria', '$nilai_input')");
        }
    }

    // Normalisasi
    $normalisasi = [];
    $prosesNormalisasi = [];
    foreach ($kriteria as $k) {
        $kolom_nilai = array_map(fn($a) => $nilai[$a['id']][$k['id']], $alternatif);
        if (count($kolom_nilai) > 0) {
            $max = max($kolom_nilai);
            $min = min($kolom_nilai);

            foreach ($alternatif as $alt) {
                $nilai_asli = $nilai[$alt['id']][$k['id']];
                if ($k['jenis'] == 'Benefit') {
                    $normalisasi[$alt['id']][$k['id']] = $max > 0 ? $nilai_asli / $max : 0;
                    $prosesNormalisasi[$alt['id']][$k['id']] = "$nilai_asli / $max";
                } else {
                    $normalisasi[$alt['id']][$k['id']] = $min > 0 ? $min / $nilai_asli : 0;
                    $prosesNormalisasi[$alt['id']][$k['id']] = "$min / $nilai_asli";
                }
            }
        }
    }

    // Simpan normalisasi ke tabel
    $conn->query("TRUNCATE TABLE normalisasi");
    foreach ($normalisasi as $id_alternatif => $kriterias) {
        foreach ($kriterias as $id_kriteria => $nilai_normalisasi) {
            $conn->query("INSERT INTO normalisasi (id_alternatif, id_kriteria, nilai_normalisasi) VALUES ('$id_alternatif', '$id_kriteria', '$nilai_normalisasi')");
        }
    }
}
?>

<style>
/* Mengatur tinggi maksimum kontainer tabel dan enable scrolling */
.table-container {
    max-height: 400px;
    overflow-y: auto;
}

/* Mengatur tampilan tabel */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    text-align: center;
    padding: 10px;
}

.table thead th {
    background-color: #000;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 1;
}

/* Menambahkan gaya untuk nama siswa */
.nama-siswa {
    text-align: left;
}

/* Mengatur tombol penjelasan */
.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: #fff;
}

/* Mengatur tampilan penjelasan */
#penjelasan {
    background-color: #e9ecef;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#penjelasan h5 {
    margin-top: 0;
}

#penjelasan ul {
    margin: 0;
    padding-left: 20px;
}

/* Menyesuaikan ukuran dan styling tombol */
.btn-success {
    font-size: 16px;
    padding: 8px 16px;
}

.btn-success i {
    margin-right: 8px;
}
</style>

<div class="container mt-5">
    <h2 class="mb-4">Input Nilai</h2>
    <form method="POST">
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Siswa</th>
                        <?php foreach ($kriteria as $k) : ?>
                            <th><?php echo htmlspecialchars($k['nama_kriteria']); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alternatif as $index => $alt) : ?>
                        <tr>
                            <td class="text-center"><?php echo $index + 1; ?></td>
                            <td class="nama-siswa"><?php echo htmlspecialchars($alt['nama_siswa']); ?></td>
                            <?php foreach ($kriteria as $k) : ?>
                                <td>
                                    <input type="number" class="form-control" name="nilai[<?php echo $alt['id']; ?>][<?php echo $k['id']; ?>]" step="0.01" value="<?php echo isset($nilai[$alt['id']][$k['id']]) ? htmlspecialchars($nilai[$alt['id']][$k['id']]) : ''; ?>" required>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Center button with spacing -->
        <div class="d-flex justify-content-center mt-3 mb-5">
            <button type="submit" name="hitung" class="btn btn-success">
                <i class="fas fa-calculator"></i> Hitung
            </button>
        </div>
    </form>

    <?php if (isset($_POST['hitung'])): ?>
        <h2 class="mt-5">Proses Normalisasi</h2>
        <button class="btn btn-info mt-3" onclick="togglePenjelasan()">Klik tombol dibawah ini untuk melihat penjelasan</button>

        <div id="penjelasan" class="mt-3" style="display:none;">
            <h5><strong>Kriteria Benefit :</strong></h5>
            <p>Rii = ( Xij / max{Xij})</p>
            <p><strong>Maksud dari rumus diatas adalah :</strong></p>
            <ul>
                <li>Rii : Nilai normalisasi dari alternatif i pada kriteria j</li>
                <li>Xij : Nilai asli dari alternatif i pada kriteria j</li>
                <li>max{Xij} : Nilai maksimum dari semua alternatif pada kriteria j</li>
            </ul>
            <p><strong>Penjelasan :</strong> Setiap nilai pada kolom dengan kriteria benefit dibagi dengan nilai tertinggi dari kolom tersebut</p>
            
            <h5 class="mt-3"><strong>Kriteria Cost :</strong></h5>
            <p>Rii = ( min{Xij} / Xij)</p>
            <p><strong>Maksud dari rumus diatas adalah :</strong></p>
            <ul>
                <li>Rii : Nilai normalisasi dari alternatif i pada kriteria j</li>
                <li>Xij : Nilai asli dari alternatif i pada kriteria j</li>
                <li>min{Xij} : Nilai minimum dari semua alternatif pada kriteria j</li>
            </ul>
            <p><strong>Penjelasan :</strong> Nilai pada kolom dengan kriteria cost, nilai terkecil akan dibagi dengan setiap nilai pada kolom tersebut</p>
        </div>
        
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Siswa</th>
                        <?php foreach ($kriteria as $k) : ?>
                            <th><?php echo htmlspecialchars($k['nama_kriteria']); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prosesNormalisasi as $id_alternatif => $kriterias) : ?>
                        <tr>
                            <td class="text-center"><?php echo array_search($id_alternatif, array_column($alternatif, 'id')) + 1; ?></td>
                            <td class="nama-siswa"><?php echo htmlspecialchars($alternatif[array_search($id_alternatif, array_column($alternatif, 'id'))]['nama_siswa']); ?></td>
                            <?php foreach ($kriterias as $id_kriteria => $proses) : ?>
                                <td><?php echo htmlspecialchars($proses); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 class="mt-5">Hasil Normalisasi</h2>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Siswa</th>
                        <?php foreach ($kriteria as $k) : ?>
                            <th><?php echo htmlspecialchars($k['nama_kriteria']); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($normalisasi as $id_alternatif => $kriterias) : ?>
                        <tr>
                            <td class="text-center"><?php echo array_search($id_alternatif, array_column($alternatif, 'id')) + 1; ?></td>
                            <td class="nama-siswa"><?php echo htmlspecialchars($alternatif[array_search($id_alternatif, array_column($alternatif, 'id'))]['nama_siswa']); ?></td>
                            <?php foreach ($kriterias as $id_kriteria => $nilai_normalisasi) : ?>
                                <td><?php echo htmlspecialchars(number_format($nilai_normalisasi, 3)); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function togglePenjelasan() {
    var penjelasan = document.getElementById('penjelasan');
    penjelasan.style.display = penjelasan.style.display === 'none' ? 'block' : 'none';
}
</script>

<?php include 'includes/footer.php'; ?>
