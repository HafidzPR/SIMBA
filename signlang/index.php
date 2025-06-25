<?php
include 'koneksi.php'; // Sertakan file koneksi

echo "<h1>SIMBA</h1>";

// --- FUNGSI UNTUK MENAMBAHKAN DATA ---

// Fungsi tambahPengguna sekarang menerima parameter $password
function tambahPengguna($conn, $username, $email, $password)
{
    // Hashing password sebelum menyimpannya ke database
    // PASSWORD_DEFAULT adalah algoritma hashing yang direkomendasikan saat ini
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO pengguna (username, email, password) VALUES (?, ?, ?)");
    // 'sss' karena ketiga parameter adalah string (username, email, password_hash)
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<p>Pengguna **$username** berhasil ditambahkan.</p>";
        return $stmt->insert_id; // Mengembalikan ID dari pengguna yang baru ditambahkan
    } else {
        echo "<p style='color:red;'>Error menambah pengguna: " . $stmt->error . "</p>";
        return false;
    }
    $stmt->close();
}

function tambahKonfigurasi($conn, $id_admin, $nama_konfigurasi, $nilai_konfigurasi)
{
    $stmt = $conn->prepare("INSERT INTO konfigurasi (id_admin, nama_konfigurasi, nilai_konfigurasi) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_admin, $nama_konfigurasi, $nilai_konfigurasi); // 'iss' (integer, string, string)

    if ($stmt->execute()) {
        echo "<p>Konfigurasi **$nama_konfigurasi** untuk admin ID **$id_admin** berhasil ditambahkan.</p>";
    } else {
        echo "<p style='color:red;'>Error menambah konfigurasi: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

function tambahRiwayatTerjemahan($conn, $id_admin, $hasil_terjemahan)
{
    $stmt = $conn->prepare("INSERT INTO riwayat_terjemahan (id_admin, hasil_terjemahan) VALUES (?, ?)");
    $stmt->bind_param("is", $id_admin, $hasil_terjemahan); // 'is' (integer, string)

    if ($stmt->execute()) {
        echo "<p>Riwayat terjemahan untuk admin ID **$id_admin** berhasil ditambahkan.</p>";
    } else {
        echo "<p style='color:red;'>Error menambah riwayat terjemahan: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// // --- CONTOH PENGGUNAAN FUNGSI (MENAMBAH DATA) ---
// echo "<h2>Menambah Data</h2>";

// // 1. Tambah Pengguna dengan password
// $id_admin_baru = tambahPengguna($conn, "admin_dua", "admin2@example.com", "Rahasia123!");
// if ($id_admin_baru) {
//     // 2. Tambah Konfigurasi untuk admin_dua
//     tambahKonfigurasi($conn, $id_admin_baru, "notifikasi_email", "true");
//     tambahKonfigurasi($conn, $id_admin_baru, "bahasa_default", "en");

//     // 3. Tambah Riwayat Terjemahan untuk admin_dua
//     tambahRiwayatTerjemahan($conn, $id_admin_baru, "Good morning -> Selamat pagi");
//     tambahRiwayatTerjemahan($conn, $id_admin_baru, "Thank you -> Terima kasih");
// }

// echo "<hr>";

// --- FUNGSI UNTUK MENAMPILKAN DATA ---

function tampilkanPengguna($conn)
{
    echo "<h2>Data Pengguna (Password tidak ditampilkan!)</h2>";
    // Jangan pernah menampilkan kolom password dari database!
    $sql = "SELECT id_admin, username, email FROM pengguna";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID Admin</th><th>Username</th><th>Email</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["id_admin"] . "</td><td>" . $row["username"] . "</td><td>" . $row["email"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Belum ada data pengguna.</p>";
    }
}

function tampilkanKonfigurasi($conn)
{
    echo "<h2>Data Konfigurasi</h2>";
    $sql = "SELECT k.id_konfigurasi, p.username, k.nama_konfigurasi, k.nilai_konfigurasi
            FROM konfigurasi k
            JOIN pengguna p ON k.id_admin = p.id_admin";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID Konfigurasi</th><th>Username Admin</th><th>Nama Konfigurasi</th><th>Nilai Konfigurasi</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["id_konfigurasi"] . "</td><td>" . $row["username"] . "</td><td>" . $row["nama_konfigurasi"] . "</td><td>" . $row["nilai_konfigurasi"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Belum ada data konfigurasi.</p>";
    }
}

function tampilkanRiwayatTerjemahan($conn)
{
    echo "<h2>Data Riwayat Terjemahan</h2>";
    $sql = "SELECT r.id_riwayat, p.username, r.waktu_terjemahan, r.hasil_terjemahan
            FROM riwayat_terjemahan r
            JOIN pengguna p ON r.id_admin = p.id_admin";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID Riwayat</th><th>Username Admin</th><th>Waktu Terjemahan</th><th>Hasil Terjemahan</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["id_riwayat"] . "</td><td>" . $row["username"] . "</td><td>" . $row["waktu_terjemahan"] . "</td><td>" . $row["hasil_terjemahan"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Belum ada data riwayat terjemahan.</p>";
    }
}

// --- PEMROSESAN FORM TAMBAH DATA ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_pengguna'])) {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($email) && !empty($password)) {
            tambahPengguna($conn, $username, $email, $password);
        } else {
            echo "<p style='color:red;'>Error: Semua kolom pengguna harus diisi.</p>";
        }
    } elseif (isset($_POST['submit_konfigurasi'])) {
        $id_admin_konfigurasi = $_POST['id_admin_konfigurasi'] ?? '';
        $nama_konfigurasi = $_POST['nama_konfigurasi'] ?? '';
        $nilai_konfigurasi = $_POST['nilai_konfigurasi'] ?? '';

        if (!empty($id_admin_konfigurasi) && !empty($nama_konfigurasi)) {
            tambahKonfigurasi($conn, $id_admin_konfigurasi, $nama_konfigurasi, $nilai_konfigurasi);
        } else {
            echo "<p style='color:red;'>Error: ID Admin dan Nama Konfigurasi harus diisi.</p>";
        }
    } elseif (isset($_POST['submit_riwayat'])) {
        $id_admin_riwayat = $_POST['id_admin_riwayat'] ?? '';
        $hasil_terjemahan = $_POST['hasil_terjemahan'] ?? '';

        if (!empty($id_admin_riwayat) && !empty($hasil_terjemahan)) {
            tambahRiwayatTerjemahan($conn, $id_admin_riwayat, $hasil_terjemahan);
        } else {
            echo "<p style='color:red;'>Error: ID Admin dan Hasil Terjemahan harus diisi.</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMBA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        h1,
        h2 {
            color: #0056b3;
        }

        hr {
            border: 0;
            height: 1px;
            background-color: #ccc;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        form input[type="submit"]:hover {
            background-color: #218838;
        }

        p.success {
            color: green;
            font-weight: bold;
        }

        p.error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php

    // --- CONTOH PENGGUNAAN FUNGSI (MENAMPILKAN DATA) ---
    echo "<h2>Menampilkan Data</h2>";
    tampilkanPengguna($conn);
    tampilkanKonfigurasi($conn);
    tampilkanRiwayatTerjemahan($conn);

    $conn->close(); // Tutup koneksi setelah semua operasi selesai

    ?>

    <hr>

    <h2>Tambah Data Pengguna Baru</h2>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" name="submit_pengguna" value="Tambah Pengguna">
    </form>

    <hr>

    <h2>Tambah Data Konfigurasi</h2>
    <form action="" method="post">
        <label for="id_admin_konfigurasi">ID Admin (untuk konfigurasi ini):</label>
        <input type="text" id="id_admin_konfigurasi" name="id_admin_konfigurasi" required pattern="[0-9]+" title="Masukkan ID Admin berupa angka">

        <label for="nama_konfigurasi">Nama Konfigurasi:</label>
        <input type="text" id="nama_konfigurasi" name="nama_konfigurasi" required>

        <label for="nilai_konfigurasi">Nilai Konfigurasi:</label>
        <input type="text" id="nilai_konfigurasi" name="nilai_konfigurasi">

        <input type="submit" name="submit_konfigurasi" value="Tambah Konfigurasi">
    </form>

    <hr>

    <h2>Tambah Data Riwayat Terjemahan</h2>
    <form action="" method="post">
        <label for="id_admin_riwayat">ID Admin (untuk riwayat ini):</label>
        <input type="text" id="id_admin_riwayat" name="id_admin_riwayat" required pattern="[0-9]+" title="Masukkan ID Admin berupa angka">

        <label for="hasil_terjemahan">Hasil Terjemahan:</label>
        <textarea id="hasil_terjemahan" name="hasil_terjemahan" rows="4" required></textarea>

        <input type="submit" name="submit_riwayat" value="Tambah Riwayat">
    </form>

</body>

</html>