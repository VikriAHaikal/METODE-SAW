<?php
include 'includes/header.php';
include 'includes/db.php';

// Mendapatkan data normalisasi
$normalisasiResult = $conn->query("SELECT n.id_alternatif, n.id_kriteria, n.nilai_normalisasi, k.bobot FROM normalisasi n JOIN kriteria k ON n.id_kriteria = k.id");
$normalisasi = [];
while ($row = $normalisasiResult->fetch_assoc()) {
    $normalisasi[$row['id_alternatif']][] = $row;
}

// Menghitung preferensi
$preferensi = [];
$prosesPerhitungan = [];
foreach ($normalisasi as $id_alternatif => $kriterias) {
    $nilai_preferensi = 0;
    $proses = [];
    foreach ($kriterias as $data) {
        $nilai_preferensi += $data['nilai_normalisasi'] * $data['bobot'];
        $proses[] = "(" . $data['bobot'] . " * " . number_format($data['nilai_normalisasi'], 2) . ")";
    }
    $preferensi[$id_alternatif] = $nilai_preferensi;
    $prosesPerhitungan[$id_alternatif] = implode(' + ', $proses);
}

// Simpan preferensi ke tabel
$conn->query("TRUNCATE TABLE preferensi");
foreach ($preferensi as $id_alternatif => $nilai_preferensi) {
    $conn->query("INSERT INTO preferensi (id_alternatif, nilai_preferensi) VALUES ('$id_alternatif', '$nilai_preferensi')");
}

// Mendapatkan data alternatif
$alternatifResult = $conn->query("SELECT * FROM alternatif");
$alternatif = [];
while ($row = $alternatifResult->fetch_assoc()) {
    $alternatif[$row['id']] = $row['nama_siswa'];
}

// Mengurutkan preferensi
arsort($preferensi);
$rankingList = $preferensi; // Use all records for ranking
$top3 = array_slice($rankingList, 0, 3, true);
?>

<div class="container mt-5">
    <h2>Proses Perhitungan Nilai Akhir</h2>
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Alternatif</th>
                    <th>Proses Perhitungan</th>
                    <th>Hasil</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($prosesPerhitungan as $id_alternatif => $proses) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($alternatif[$id_alternatif]); ?></td>
                        <td><?php echo htmlspecialchars($proses); ?></td>
                        <td><?php echo number_format($preferensi[$id_alternatif], 3); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2 class="mt-5">Hasil Akhir</h2>
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama">
    </div>
    <div class="table-container" id="resultTableContainer">
        <table class="table table-bordered" id="resultTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                foreach ($rankingList as $id_alternatif => $nilai_preferensi) :
                    $crownIcon = '';
                    if ($rank === 1) {
                        $crownIcon = '👑'; // Crown for 1st place
                    } elseif ($rank === 2) {
                        $crownIcon = '🥈'; // Silver medal for 2nd place
                    } elseif ($rank === 3) {
                        $crownIcon = '🥉'; // Bronze medal for 3rd place
                    }
                    ?>
                    <tr data-rank="<?php echo $rank; ?>" class="<?php echo array_key_exists($id_alternatif, $top3) ? 'highlight' : ''; ?>">
                        <td><?php echo $rank; ?></td>
                        <td><?php echo htmlspecialchars($alternatif[$id_alternatif]); ?> <?php echo $crownIcon; ?></td>
                        <td><?php echo number_format($nilai_preferensi, 3); ?></td>
                    </tr>
                    <?php
                    $rank++;
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    var input = document.getElementById('searchInput');
    var filter = input.value.toLowerCase();
    var table = document.getElementById('resultTable');
    var tr = table.getElementsByTagName('tr');
    
    for (var i = 1; i < tr.length; i++) {
        var tdName = tr[i].getElementsByTagName('td')[1];
        if (tdName) {
            var txtValue = tdName.textContent || tdName.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }       
    }
});
</script>

<style>
/* Mengatur tinggi maksimum kontainer tabel dan enable scrolling */
.table-container {
    max-height: 400px; /* Sesuaikan tinggi maksimum tabel */
    overflow-y: auto; /* Enable scrolling vertikal */
}

/* Mengatur tampilan tabel */
.table {
    width: 100%; /* Tabel mengambil lebar penuh dari kontainer */
    border-collapse: collapse; /* Menghilangkan jarak antara border sel tabel */
}

.table th, .table td {
    text-align: left; /* Mengatur teks di kiri */
    padding: 10px; /* Padding dalam sel tabel */
}

.table thead th {
    background-color: #000; /* Warna latar belakang header tabel */
    color: #fff; /* Warna teks header tabel */
    position: sticky; /* Membuat header tetap terlihat */
    top: 0; /* Menempatkan header di atas saat scrolling */
    z-index: 1; /* Menjaga header di atas elemen lain saat scrolling */
}

/* Highlight row for top 3 rankings */
.highlight {
    background-color: #f0f8ff; /* Light color to highlight top 3 rows */
}

/* Additional styling for resultTableContainer */
#resultTableContainer {
    max-height: 400px; /* Sesuaikan tinggi maksimum tabel */
    overflow-y: auto; /* Enable scrolling vertikal */
}
</style>

<?php include 'includes/footer.php'; ?>
