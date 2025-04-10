<?php 
require_once 'includes/config.php';
include 'includes/header.php'; 
?>

<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-donker mb-4">Selamat Datang di Kasir Sugoiiyaki</h1>
        <p class="text-gray-600">Sistem Kasir untuk Memudahkan Transaksi Anda</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Menu Management Card -->
        <div class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition duration-300">
            <div class="flex items-center mb-4">
                <i class="fas fa-utensils text-2xl text-maroon mr-3"></i>
                <h2 class="text-xl font-semibold">Kelola Menu</h2>
            </div>
            <p class="text-gray-600 mb-4">Tambah, edit, atau hapus menu Sugoiiyaki dengan mudah.</p>
            <a href="admin/menu.php" class="inline-block bg-maroon text-white px-4 py-2 rounded hover:bg-red-900 transition duration-300">
                Kelola Menu <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Transaction Card -->
        <div class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition duration-300">
            <div class="flex items-center mb-4">
                <i class="fas fa-cash-register text-2xl text-donker mr-3"></i>
                <h2 class="text-xl font-semibold">Transaksi Baru</h2>
            </div>
            <p class="text-gray-600 mb-4">Buat transaksi baru dan kelola pembayaran pelanggan.</p>
            <a href="admin/transaksi.php" class="inline-block bg-donker text-white px-4 py-2 rounded hover:bg-blue-900 transition duration-300">
                Mulai Transaksi <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<!-- Quick Menu Overview -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold text-donker mb-6">Menu Favorit</h2>
    <div class="grid md:grid-cols-3 gap-4">
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold text-lg mb-2">Takoyaki Classic</h3>
            <p class="text-gray-600">Mulai dari <?php echo formatRupiah(12000); ?></p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold text-lg mb-2">Kuroyaki</h3>
            <p class="text-gray-600">Mulai dari <?php echo formatRupiah(15000); ?></p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold text-lg mb-2">Okonomiyaki</h3>
            <p class="text-gray-600"><?php echo formatRupiah(25000); ?></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
