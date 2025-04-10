<?php 
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle transaction action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'simpan_transaksi') {
        $nama_pelanggan = cleanInput($_POST['nama_pelanggan']);
        $total_harga = cleanInput($_POST['total_harga']);
        $menu_items = isset($_POST['menu_items']) ? json_decode($_POST['menu_items'], true) : [];

        try {
            $pdo->beginTransaction();

            // Insert main transaction
            $stmt = $pdo->prepare("INSERT INTO transaksi (nama_pelanggan, total_harga) VALUES (?, ?)");
            $stmt->execute([$nama_pelanggan, $total_harga]);
            $transaksi_id = $pdo->lastInsertId();

            // Insert transaction details
            $stmt = $pdo->prepare("INSERT INTO transaksi_detail (transaksi_id, menu_id, ukuran, harga) VALUES (?, ?, ?, ?)");
            foreach ($menu_items as $item) {
                $stmt->execute([
                    $transaksi_id,
                    $item['menu_id'],
                    $item['ukuran'],
                    $item['harga']
                ]);
            }

            $pdo->commit();
            $successMessage = "Transaksi berhasil dicatat!";
        } catch(PDOException $e) {
            $pdo->rollBack();
            $errorMessage = "Gagal mencatat transaksi: " . $e->getMessage();
        }
    }
}

// Get all menus with their details
$stmt = $pdo->query("SELECT * FROM menus ORDER BY kategori, nama");
$menus = $stmt->fetchAll();
$menusJson = json_encode($menus);
?>

<?php include '../includes/header.php'; ?>

<div class="bg-white rounded-lg shadow-lg p-6">
    <h1 class="text-2xl font-bold text-donker mb-6">Transaksi Baru</h1>

    <?php if (isset($successMessage)): ?>
        <?php echo showAlert($successMessage, 'success'); ?>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)): ?>
        <?php echo showAlert($errorMessage, 'error'); ?>
    <?php endif; ?>

    <form method="POST" id="transaksiForm">
        <input type="hidden" name="action" value="simpan_transaksi">
        <input type="hidden" name="menu_items" id="menu_items" value="[]">
        
        <div class="mb-4">
            <label for="nama_pelanggan" class="block text-gray-700">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" id="nama_pelanggan" required 
                   class="border border-gray-300 rounded-lg w-full p-2">
        </div>

        <div class="mb-4 p-4 border border-gray-300 rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Tambah Menu</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="menu" class="block text-gray-700">Menu</label>
                    <select name="menu" id="menu" class="border border-gray-300 rounded-lg w-full p-2">
                        <option value="">Pilih Menu</option>
                        <?php foreach ($menus as $menu): ?>
                            <option value="<?php echo $menu['id']; ?>"
                                    data-nama="<?php echo htmlspecialchars($menu['nama']); ?>"
                                    data-harga="<?php echo $menu['harga_dasar']; ?>"
                                    data-toppings='<?php echo htmlspecialchars($menu['toppings']); ?>'>
                                <?php echo htmlspecialchars($menu['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="ukuranContainer" class="hidden">
                    <label for="ukuran" class="block text-gray-700">Ukuran</label>
                    <select name="ukuran" id="ukuran" class="border border-gray-300 rounded-lg w-full p-2">
                        <option value="">Pilih Ukuran</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="button" id="tambahMenu" class="bg-cerah text-donker px-4 py-2 rounded-lg hover:bg-yellow-400 transition duration-300">
                    + Tambah ke Pesanan
                </button>
            </div>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold mb-4">Detail Pesanan</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Menu</th>
                            <th class="px-4 py-2 text-left">Ukuran</th>
                            <th class="px-4 py-2 text-right">Harga</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="menuTable">
                        <!-- Menu items will be added here -->
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200">
                            <td colspan="2" class="px-4 py-2 text-right font-bold">Total:</td>
                            <td class="px-4 py-2 text-right font-bold" id="totalDisplay">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <input type="hidden" name="total_harga" id="total_harga" value="0">
        <button type="submit" class="bg-donker text-white px-4 py-2 rounded-lg hover:bg-blue-900 transition duration-300">
            Simpan Transaksi
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuSelect = document.getElementById('menu');
    const ukuranContainer = document.getElementById('ukuranContainer');
    const ukuranSelect = document.getElementById('ukuran');
    const tambahMenuBtn = document.getElementById('tambahMenu');
    const menuTable = document.getElementById('menuTable');
    const totalDisplay = document.getElementById('totalDisplay');
    const totalHargaInput = document.getElementById('total_harga');
    const menuItemsInput = document.getElementById('menu_items');
    let menuItems = [];

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateTotal() {
        const total = menuItems.reduce((sum, item) => sum + parseInt(item.harga), 0);
        totalDisplay.textContent = formatRupiah(total);
        totalHargaInput.value = total;
        menuItemsInput.value = JSON.stringify(menuItems);
    }

    function removeMenuItem(index) {
        menuItems.splice(index, 1);
        renderMenuItems();
        updateTotal();
    }

    function renderMenuItems() {
        menuTable.innerHTML = menuItems.map((item, index) => `
            <tr>
                <td class="px-4 py-2">${item.nama}</td>
                <td class="px-4 py-2">${item.ukuran || '-'}</td>
                <td class="px-4 py-2 text-right">${formatRupiah(item.harga)}</td>
                <td class="px-4 py-2 text-center">
                    <button type="button" onclick="removeMenuItem(${index})" 
                            class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    window.removeMenuItem = removeMenuItem;

    menuSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (!selectedOption.value) {
            ukuranContainer.classList.add('hidden');
            return;
        }

        let toppings = null;
        try {
            toppings = JSON.parse(selectedOption.dataset.toppings);
        } catch (e) {
            toppings = null;
        }

        ukuranSelect.innerHTML = '<option value="">Pilih Ukuran</option>';

        if (toppings && Object.keys(toppings).length > 0) {
            ukuranContainer.classList.remove('hidden');
            for (const [size, harga] of Object.entries(toppings)) {
                const option = document.createElement('option');
                option.value = size;
                option.textContent = size;
                option.dataset.harga = harga;
                ukuranSelect.appendChild(option);
            }
        } else {
            ukuranContainer.classList.add('hidden');
        }
    });

    tambahMenuBtn.addEventListener('click', function() {
        const selectedOption = menuSelect.options[menuSelect.selectedIndex];
        if (!selectedOption.value) {
            alert('Silakan pilih menu terlebih dahulu');
            return;
        }

        let harga = parseInt(selectedOption.dataset.harga);
        let ukuran = '';

        try {
            const toppings = JSON.parse(selectedOption.dataset.toppings);
            if (toppings && Object.keys(toppings).length > 0) {
                if (!ukuranSelect.value) {
                    alert('Silakan pilih ukuran terlebih dahulu');
                    return;
                }
                ukuran = ukuranSelect.value;
                harga = parseInt(toppings[ukuran]);
            }
        } catch (e) {}

        menuItems.push({
            menu_id: parseInt(selectedOption.value),
            nama: selectedOption.dataset.nama,
            ukuran: ukuran,
            harga: harga
        });

        renderMenuItems();
        updateTotal();

        // Reset selection
        menuSelect.value = '';
        ukuranContainer.classList.add('hidden');
    });
});
</script>

<?php include '../includes/footer.php'; ?>
