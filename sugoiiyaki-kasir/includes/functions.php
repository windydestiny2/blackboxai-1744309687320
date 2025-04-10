<?php
// Function untuk membersihkan input
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function untuk menampilkan pesan alert
function showAlert($message, $type = 'success') {
    $bgColor = $type === 'success' ? 'bg-green-500' : 'bg-red-500';
    return "<div class='$bgColor text-white p-4 rounded-lg mb-4'>$message</div>";
}

// Function untuk mendapatkan semua menu
function getAllMenu() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM menus ORDER BY kategori, nama");
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error in getAllMenu: " . $e->getMessage());
        return [];
    }
}

// Function untuk mendapatkan menu berdasarkan ID
function getMenuById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Error in getMenuById: " . $e->getMessage());
        return false;
    }
}

// Function untuk format harga ke rupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Function untuk mendapatkan detail transaksi
function getTransaksiDetail($transaksi_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT td.*, m.nama as menu_nama 
            FROM transaksi_detail td 
            JOIN menus m ON td.menu_id = m.id 
            WHERE td.transaksi_id = ?
        ");
        $stmt->execute([$transaksi_id]);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error in getTransaksiDetail: " . $e->getMessage());
        return [];
    }
}

// Function untuk mendapatkan semua transaksi
function getAllTransaksi() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM transaksi ORDER BY tanggal DESC");
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error in getAllTransaksi: " . $e->getMessage());
        return [];
    }
}
