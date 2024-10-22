<?php
include 'includes/header.php';
include 'includes/db.php';

// Pagination variables
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Tambah alternatif
if (isset($_POST['add'])) {
    $nisn = $_POST['nisn'];
    $nama_siswa = $_POST['nama_siswa'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    $sql = "INSERT INTO alternatif (nisn, nama_siswa, jenis_kelamin) VALUES ('$nisn', '$nama_siswa', '$jenis_kelamin')";
    $conn->query($sql);
}

// Edit dan Delete alternatif
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nisn = $_POST['nisn'];
    $nama_siswa = $_POST['nama_siswa'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    $sql = "UPDATE alternatif SET nisn='$nisn', nama_siswa='$nama_siswa', jenis_kelamin='$jenis_kelamin' WHERE id='$id'";
    $conn->query($sql);
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM alternatif WHERE id='$id'";
    $conn->query($sql);
}

// Get total number of items
$total_result = $conn->query("SELECT COUNT(*) AS total FROM alternatif");
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];

// Get items for the current page
$sql = "SELECT * FROM alternatif LIMIT $items_per_page OFFSET $offset";
$result = $conn->query($sql);
?>

<div class="container mt-2">
    <div class="d-flex align-items-center mb-4">
        <h2 class="me-3">Kelola Alternatif</h2>
        <button id="toggle-form" class="btn btn-info">
            <i class="fas fa-cogs"></i> <!-- Ikon settings dari Font Awesome -->
        </button>
    </div>

    <div id="form-container" class="mb-4" style="display:none;">
        <form method="POST">
            <div class="mb-3">
                <input type="hidden" name="id" id="edit-id">
                <input type="text" name="nisn" id="edit-nisn" class="form-control" placeholder="NISN" required>
            </div>
            <div class="mb-3">
                <input type="text" name="nama_siswa" id="edit-nama" class="form-control" placeholder="Nama Siswa" required>
            </div>
            <div class="mb-3">
                <select name="jenis_kelamin" id="edit-jenis_kelamin" class="form-select" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <button type="submit" id="form-submit" class="btn btn-success">Tambah</button>
            <button type="button" id="form-cancel" class="btn btn-secondary">Batal</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">NISN</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th class="text-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = $offset + 1; // Initialize the row number
                while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td> <!-- Center align No -->
                    <td class="text-center"><?php echo htmlspecialchars($row['nisn']); ?></td> <!-- Center align NISN -->
                    <td><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                    <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary btn-sm edit-btn" data-id="<?php echo $row['id']; ?>" data-nisn="<?php echo $row['nisn']; ?>" data-nama="<?php echo $row['nama_siswa']; ?>" data-jenis_kelamin="<?php echo $row['jenis_kelamin']; ?>">
                                <i class="fas fa-edit"></i> <!-- Ikon pensil untuk Edit -->
                            </button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm ms-2">
                                    <i class="fas fa-trash"></i> <!-- Ikon tempat sampah untuk Delete -->
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <!-- Pagination Links -->
            <?php if ($page > 1) : ?>
            <a href="?page=<?php echo $page - 1; ?>" class="pagination-text"><< Previous</a>
            <?php endif; ?>

            <?php if ($page * $items_per_page < $total_items) : ?>
            <a href="?page=<?php echo $page + 1; ?>" class="pagination-text ms-3">Next >> </a>
            <?php endif; ?>
        </div>
        <div class="text-center">
            Page <?php echo $page; ?> of <?php echo ceil($total_items / $items_per_page); ?>
        </div>
    </div>
</div>

<style>
.pagination-text {
    color: #6c757d; /* Warna teks hitam keabu-abuan */
    text-decoration: none; /* Hapus garis bawah dari link */
    font-size: 16px; /* Ukuran font */
    /* Hapus bold font */
}

.pagination-text:hover {
    text-decoration: underline; /* Garis bawah saat hover */
}

/* Untuk menghindari terlalu dekat dengan footer */
.container {
    margin-bottom: 2rem; /* Jarak bawah untuk menghindari mepet dengan footer */
}

/* CSS untuk center alignment */
.table td.text-center,
.table th.text-center {
    text-align: center;
}
</style>

<script>
document.getElementById('toggle-form').addEventListener('click', () => {
    const formContainer = document.getElementById('form-container');
    formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
    document.getElementById('form-submit').textContent = 'Tambah'; // Reset text content to 'Tambah'
    document.getElementById('form-submit').name = 'add'; // Reset action to 'add'
    
    // Clear form fields
    document.getElementById('edit-id').value = '';
    document.getElementById('edit-nisn').value = '';
    document.getElementById('edit-nama').value = '';
    document.getElementById('edit-jenis_kelamin').value = '';
});

document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const nisn = button.getAttribute('data-nisn');
        const nama = button.getAttribute('data-nama');
        const jenisKelamin = button.getAttribute('data-jenis_kelamin');
        
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nisn').value = nisn;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-jenis_kelamin').value = jenisKelamin;
        
        const formContainer = document.getElementById('form-container');
        formContainer.style.display = 'block';
        document.getElementById('form-submit').textContent = 'Update'; // Change text to 'Update'
        document.getElementById('form-submit').name = 'edit'; // Set action to 'edit'
    });
});

document.getElementById('form-cancel').addEventListener('click', () => {
    const formContainer = document.getElementById('form-container');
    formContainer.style.display = 'none';
    
    // Clear form fields when canceling
    document.getElementById('edit-id').value = '';
    document.getElementById('edit-nisn').value = '';
    document.getElementById('edit-nama').value = '';
    document.getElementById('edit-jenis_kelamin').value = '';
});
</script>

<?php include 'includes/footer.php'; ?>
