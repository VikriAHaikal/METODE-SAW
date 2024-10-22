<?php
include 'includes/header.php';
include 'includes/db.php';

// Tambah kriteria
if (isset($_POST['add'])) {
    $nama_kriteria = $_POST['nama_kriteria'];
    $bobot = $_POST['bobot'];
    $jenis = $_POST['jenis'];

    $sql = "INSERT INTO kriteria (nama_kriteria, bobot, jenis) VALUES ('$nama_kriteria', '$bobot', '$jenis')";
    $conn->query($sql);
}

// Edit dan Delete kriteria
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $bobot = $_POST['bobot'];
    $jenis = $_POST['jenis'];

    $sql = "UPDATE kriteria SET nama_kriteria='$nama_kriteria', bobot='$bobot', jenis='$jenis' WHERE id='$id'";
    $conn->query($sql);
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM kriteria WHERE id='$id'";
    $conn->query($sql);
}

$result = $conn->query("SELECT * FROM kriteria");
?>

<div class="container mt-5">
    <div class="d-flex align-items-center mb-4">
        <h2 class="me-3">Kelola Kriteria</h2>
        <button id="toggle-form" class="btn btn-info">
            <i class="fas fa-cogs"></i> <!-- Ikon settings dari Font Awesome -->
        </button>
    </div>
    
    <div id="form-container" class="mb-4" style="display:none;">
        <form method="POST">
            <div class="mb-3">
                <input type="hidden" name="id" id="edit-id">
                <input type="text" name="nama_kriteria" id="edit-nama" class="form-control" placeholder="Nama Kriteria" required>
            </div>
            <div class="mb-3">
                <input type="number" step="0.01" name="bobot" id="edit-bobot" class="form-control" placeholder="Bobot" required>
            </div>
            <div class="mb-3">
                <select name="jenis" id="edit-jenis" class="form-select" required>
                    <option value="">Pilih Jenis</option>
                    <option value="Benefit">Benefit</option>
                    <option value="Cost">Cost</option>
                </select>
            </div>
            <button type="submit" id="form-submit" class="btn btn-success">Tambah</button>
            <button type="button" id="form-cancel" class="btn btn-secondary">Batal</button>
        </form>
    </div>
    
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th>Nama Kriteria</th>
                <th>Bobot</th>
                <th>Jenis</th>
                <th class="text-nowrap">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nama_kriteria']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['bobot']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['jenis']); ?></td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-sm edit-btn" data-id="<?php echo $row['id']; ?>" data-nama="<?php echo $row['nama_kriteria']; ?>" data-bobot="<?php echo $row['bobot']; ?>" data-jenis="<?php echo $row['jenis']; ?>">
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

<script>
document.getElementById('toggle-form').addEventListener('click', () => {
    const formContainer = document.getElementById('form-container');
    if (formContainer.style.display === 'none') {
        formContainer.style.display = 'block';
        // Set form to 'add' mode
        document.getElementById('form-submit').textContent = 'Tambah'; 
        document.getElementById('form-submit').name = 'add';
        
        // Clear form fields
        document.getElementById('edit-id').value = '';
        document.getElementById('edit-nama').value = '';
        document.getElementById('edit-bobot').value = '';
        document.getElementById('edit-jenis').value = '';
    } else {
        formContainer.style.display = 'none';
    }
});

document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const bobot = button.getAttribute('data-bobot');
        const jenis = button.getAttribute('data-jenis');
        
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-bobot').value = bobot;
        document.getElementById('edit-jenis').value = jenis;
        
        const formContainer = document.getElementById('form-container');
        formContainer.style.display = 'block';
        // Set form to 'edit' mode
        document.getElementById('form-submit').textContent = 'Update'; 
        document.getElementById('form-submit').name = 'edit';
    });
});

document.getElementById('form-cancel').addEventListener('click', () => {
    const formContainer = document.getElementById('form-container');
    formContainer.style.display = 'none';
    
    // Clear form fields
    document.getElementById('edit-id').value = '';
    document.getElementById('edit-nama').value = '';
    document.getElementById('edit-bobot').value = '';
    document.getElementById('edit-jenis').value = '';
});
</script>

<?php include 'includes/footer.php'; ?>
