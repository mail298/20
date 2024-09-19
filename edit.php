<?php
session_start();

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'mahasiswa_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah ada data ID yang dikirim
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM mahasiswa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $mahasiswa = $result->fetch_assoc();
} else {
    echo "ID tidak ditemukan.";
    exit;
}

// Proses update data ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $nim = $_POST['nim'];
    $jurusan = $_POST['jurusan'];
    $email = $_POST['email'];
    $newImageName = $mahasiswa['gambar']; // Ambil nama gambar lama
    
    // Cek apakah ada file gambar yang diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar']['tmp_name'];
        $fileName = $_FILES['gambar']['name'];
        $fileSize = $_FILES['gambar']['size'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Validasi ekstensi dan ukuran gambar
        $validImageExtension = ['jpg'];
        if (in_array($fileExtension, $validImageExtension) && $fileSize <= 1000000) {
            // Tentukan direktori tujuan penyimpanan
            $uploadFileDir = './uploads/';
            // Pastikan direktori ada
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true); // Buat folder jika belum ada
            }

            $newImageName = uniqid() . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newImageName;

            // Pindahkan file gambar baru ke direktori uploads
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Hapus gambar lama dari folder
                if (!empty($mahasiswa['gambar']) && file_exists($uploadFileDir . $mahasiswa['gambar'])) {
                    unlink($uploadFileDir . $mahasiswa['gambar']);
                }
                echo 'File berhasil diunggah dan gambar lama dihapus.';
            } else {
                echo 'Terjadi kesalahan saat mengunggah file.';
            }
        } else {
            echo 'Gambar tidak valid atau ukurannya terlalu besar.';
        }
    }

    // Update data mahasiswa ke database, termasuk gambar baru jika ada
    $sql = "UPDATE mahasiswa SET name = ?, nim = ?, jurusan = ?, email = ?, gambar = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Cek apakah query berhasil disiapkan
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $stmt->bind_param('sssssi', $name, $nim, $jurusan, $email, $newImageName, $id);

    if ($stmt->execute()) {
        echo "Data mahasiswa berhasil diperbarui.";
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
</head>
<body>
    <h2>Edit Mahasiswa</h2>
    <form action="edit.php?id=<?= htmlspecialchars($mahasiswa['id']) ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($mahasiswa['id']) ?>">

        <label for="name">Nama:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($mahasiswa['name']) ?>" required><br>

        <label for="nim">NIM:</label><br>
        <input type="text" name="nim" value="<?= htmlspecialchars($mahasiswa['nim']) ?>" required><br>

        <label for="jurusan">Jurusan:</label><br>
        <input type="text" name="jurusan" value="<?= htmlspecialchars($mahasiswa['jurusan']) ?>" required><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($mahasiswa['email']) ?>" required><br>

        <!-- Input gambar -->
        <label for="gambar">Upload Gambar:</label><br>
        <input type="file" name="gambar" id="gambar"><br>

        <!-- Preview gambar sebelumnya -->
        <?php if (!empty($mahasiswa['gambar'])): ?>
        <img src="./uploads/<?= htmlspecialchars($mahasiswa['gambar']) ?>" width="100" height="100" alt="Foto Sebelumnya">
        <!-- Debug path -->
        <?php echo "<p>Path gambar: ./uploads/" . htmlspecialchars($mahasiswa['gambar']) . "</p>"; ?>
        <?php endif; ?>

        <button type="submit">Update</button>
    </form>
</body>
</html>
