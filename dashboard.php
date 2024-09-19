<?php
session_start();
// Jika session user tidak ada, arahkan ke login page
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'mahasiswa_db');

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data mahasiswa
$sql = "SELECT * FROM mahasiswa";
$result = $conn->query($sql);

// Cek apakah query berhasil
if ($result === false) {
    echo "Error: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="icon" type="image/x-icon" href="../simles/img/mdi--ninja-star.png">
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2 id="tr">Data Mahasiswa</h2> 
            <ul>
                <li><a href="#">Data Mahasiswa</a></li>
                <li id="dropdown" class="active"><a href="#"> Jurusan</a>
                    <ul class="submenu">
                        <li><a href="#">Teknik Informatika</a></li>
                        <li><a href="#">Teknik Informasi</a></li>
                        <li><a href="#">Teknik Mesin</a></li>
                        <li><a href="#">Teknik Industri</a></li>
                        <li><a href="#">Teknik Sastra Inggris</a></li>
                    </ul>
                </li>
                <li><a href="#">Config</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
            <hr>
            <p class="small"><img src="#" alt=""><br>#</p>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h3><ion-icon name="home-outline"></ion-icon>Admin</h3>
                <span class="datetime" id="datetime"></span>
            </header>
            <div class="transaction-filters">
                <button class="btn open"><a href="tambah.php" style="color: inherit; text-decoration: none;">Tambahkan Mahasiswa</a></button>
                <input type="date" name="start_date" placeholder="Start Date">
                <select name="status">
                    <option value="">-- All Status --</option>
                </select>
                <input type="text" name="username_search" placeholder="Search...">
            </div>
            <div class="transaction-table">
                <table border="1" cellpadding="10">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Jurusan</th>
                            <th>Email</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['nim']) ?></td>
                            <td><?= htmlspecialchars($row['jurusan']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar Mahasiswa" width="100">
                                <?php else: ?>
                                    Tidak ada gambar
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= htmlspecialchars($row['id']) ?>" style="color: black;">Edit</a> |
                                <a href="hapus.php?id=<?= htmlspecialchars($row['id']) ?>" style="color: black;" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script>
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', timeZoneName: 'short' };
        const formattedDateTime = now.toLocaleString('en-GB', options);
        document.getElementById('datetime').innerHTML = formattedDateTime;
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
    </script>

</body>
</html>

<?php
$conn->close();
?>
