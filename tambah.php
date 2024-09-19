<?php
$conn = new mysqli('localhost', 'root', '', 'mahasiswa_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $nim = $_POST['nim'];
    $jurusan = $_POST['jurusan'];
    $email = $_POST['email'];
    
    // Mengecek apakah file gambar ada
    if($_FILES["gambar"]["error"] === 4){
        echo
        "<script> alert('gambar tidak ada.'); </script>";
    } else {
        $fileName = $_FILES["gambar"]["name"];
        $fileSize = $_FILES["gambar"]["size"];
        $tmpName = $_FILES["gambar"]["tmp_name"];
        
        // Mengecek ekstensi file gambar
        $validImageExtension = ['jpg']; // Hanya jpg diperbolehkan
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        
        if (!in_array($imageExtension, $validImageExtension)) {
            echo
            "<script> alert('Ekstensi gambar tidak valid, pastikan format gambar .jpg'); </script>";
        } else if ($fileSize > 1000000) {
            echo
            "<script> alert('Ukuran gambar terlalu besar.'); </script>";
        } else {
            // Membuat nama baru untuk gambar
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;
            
            // Memindahkan file gambar ke folder uploads
            move_uploaded_file($tmpName, './uploads/' . $newImageName);
            
            // Query insert data ke database
            $query = "INSERT INTO mahasiswa (name, nim, jurusan, email, gambar) VALUES ('$name', '$nim', '$jurusan', '$email', '$newImageName')";
            
            if (mysqli_query($conn, $query)) {
                echo
                "<script>
                alert('Data berhasil ditambahkan');
                document.location.href = 'dashboard.php';
                </script>";
            } else {
                echo
                "<script> alert('Gagal menambahkan data.'); </script>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <h2>Tambah Mahasiswa</h2>
    <a href="dashboard.php">Kembali</a>
    <form action="tambah.php" method="POST" enctype="multipart/form-data">
    <label for="name">Nama:</label><br>
    <input type="text" name="name" required><br>

    <label for="nim">NIM:</label><br>
    <input type="text" name="nim" required><br>

    <label for="jurusan">Jurusan:</label><br>
    <input type="text" name="jurusan" required><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" required><br>

    <!-- Input gambar -->
    <label for="gambar">Upload Gambar:</label><br>
    <input type="file" name="gambar" id="gambar" accept=".jpg" onchange="previewImage();"><br>

    <!-- Tempat Preview Gambar -->
    <img id="image-preview" style="display: none; width: 150px; height: 150px;"><br>

    <button type="submit">Tambah</button>
</form>


<script>
    function previewImage() {
        const file = document.getElementById('gambar').files[0];
        const preview = document.getElementById('image-preview');
        
        // Jika file tidak ada, jangan tampilkan preview
        if (!file) {
            preview.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block'; // Menampilkan gambar setelah dipilih
        };

        reader.readAsDataURL(file);
    }
</script>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {
    const gambarInput = document.getElementById('gambar');
    if (gambarInput.files.length > 0) {
        const gambarFile = gambarInput.files[0];
        const maxSize = 1048576; // 1MB in bytes

        if (gambarFile.size > maxSize) {
            e.preventDefault();
            alert("Ukuran gambar tidak boleh lebih dari 1MB.");
        }
    }
});
</script>


</body>
</html>
