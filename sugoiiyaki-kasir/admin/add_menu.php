<?php 
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle add action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = cleanInput($_POST['nama']);
    $kategori = cleanInput($_POST['kategori']);
    $harga_dasar = cleanInput($_POST['harga_dasar']);
    
    // Process toppings into JSON format
    $toppings = [];
    if (isset($_POST['toppings']) && isset($_POST['toppings_harga'])) {
        for ($i = 0; $i < count($_POST['toppings']); $i++) {
            if (!empty($_POST['toppings'][$i]) && !empty($_POST['toppings_harga'][$i])) {
                $toppings[$_POST['toppings'][$i]] = intval($_POST['toppings_harga'][$i]);
            }
        }
    }
    $toppings_json = !empty($toppings) ? json_encode($toppings) : null;

    try {
        $stmt = $pdo->prepare("INSERT INTO menus (nama, kategori, harga_dasar, toppings) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $kategori, $harga_dasar, $toppings_json]);
        $successMessage = "Menu berhasil ditambahkan!";
    } catch(PDOException $e) {
        $errorMessage = "Gagal menambahkan menu: " . $e->getMessage();
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="bg-white rounded-lg shadow-lg p-6">
    <h1 class="text-2xl font-bold text-donker mb-6">Tambah Menu Baru</h1>

    <?php if (isset($successMessage)): ?>
        <?php echo showAlert($successMessage, 'success'); ?>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)): ?>
        <?php echo showAlert($errorMessage, 'error'); ?>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label for="nama" class="block text-gray-700">Nama Menu</label>
            <input type="text" name="nama" id="nama" required class="border border-gray-300 rounded-lg w-full p-2">
        </div>
        <div class="mb-4">
            <label for="kategori" class="block text-gray-700">Kategori</label>
            <select name="kategori" id="kategori" required class="border border-gray-300 rounded-lg w-full p-2">
                <option value="takoyaki_classic">Takoyaki Classic</option>
                <option value="kuroyaki">Kuroyaki</option>
                <option value="canai">Canai</option>
                <option value="okonomiyaki">Okonomiyaki</option>
                <option value="gyoza">Gyoza</option>
                <option value="dimsum_siumai">Dimsum Siumai</option>
                <option value="piscok">Piscok</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="harga_dasar" class="block text-gray-700">Harga Dasar</label>
            <input type="number" name="harga_dasar" id="harga_dasar" required class="border border-gray-300 rounded-lg w-full p-2">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Toppings (opsional)</label>
            <div id="topping-container">
                <div class="flex items-center mb-2">
                    <input type="text" name="toppings[]" placeholder="Ukuran (S/M/L)" class="border border-gray-300 rounded-lg w-full p-2 mr-2">
                    <input type="number" name="toppings_harga[]" placeholder="Harga" class="border border-gray-300 rounded-lg w-32 p-2">
                </div>
            </div>
            <button type="button" id="add-topping" class="bg-cerah text-donker px-4 py-2 rounded-lg hover:bg-yellow-400 transition duration-300">
                Tambah Topping
            </button>
        </div>
        <button type="submit" class="bg-donker text-white px-4 py-2 rounded-lg hover:bg-blue-900 transition duration-300">
            Simpan Menu
        </button>
    </form>
</div>

<script>
    document.getElementById('add-topping').addEventListener('click', function() {
        const container = document.getElementById('topping-container');
        const newTopping = document.createElement('div');
        newTopping.classList.add('flex', 'items-center', 'mb-2');
        newTopping.innerHTML = `
            <input type="text" name="toppings[]" placeholder="Ukuran (S/M/L)" class="border border-gray-300 rounded-lg w-full p-2 mr-2">
            <input type="number" name="toppings_harga[]" placeholder="Harga" class="border border-gray-300 rounded-lg w-32 p-2">
        `;
        container.appendChild(newTopping);
    });
</script>

<?php include '../includes/footer.php'; ?>
