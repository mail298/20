<?php
session_start();
// Jika session user tidak ada, arahkan ke login page (tambahkan pengecekan ini jika perlu)

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'mahasiswa_db');

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah ada data ID yang dikirim untuk dihapus
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data mahasiswa berdasarkan ID (termasuk nama gambar)
    $sql = "SELECT gambar FROM mahasiswa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $mahasiswa = $result->fetch_assoc();
    
    if ($mahasiswa) {
        // Tentukan path file gambar yang akan dihapus
        $uploadFileDir = './uploads/';
        $fileToDelete = $uploadFileDir . $mahasiswa['gambar'];

        // Hapus gambar dari folder jika ada
        if (!empty($mahasiswa['gambar']) && file_exists($fileToDelete)) {
            if (unlink($fileToDelete)) {
                echo "Gambar berhasil dihapus.";
            } else {
                echo "Gagal menghapus gambar.";
            }
        }

        // Hapus data mahasiswa dari database
        $sql = "DELETE FROM mahasiswa WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo "Data mahasiswa berhasil dihapus.";
            header('Location: dashboard.php');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Data mahasiswa tidak ditemukan.";
    }

    $stmt->close();
} else {
    echo "ID tidak ditemukan.";
}

$conn->close();
?>
