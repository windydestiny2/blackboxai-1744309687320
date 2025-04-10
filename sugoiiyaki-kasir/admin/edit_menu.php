<?php 
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: menu.php');
    exit();
}

$id = $_GET['id'];
$menu = getMenuById($id);

if (!$menu) {
    header('Location: menu.php');
    exit();
}

// Handle edit action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = cleanInput($_POST['nama']);
    $kategori = cleanInput($_POST['kategori']);
    $harga_dasar = cleanInput($_POST['harga_dasar']);
    $toppings = isset($_POST['toppings']) ? json_encode($_POST['toppings']) : null;

    try {
        $stmt = $pdo->prepare("UPDATE menus SET nama = ?, kategori = ?, harga_dasar = ?, toppings = ? WHERE id = ?");
        $stmt->execute([$nama, $kategori, $harga_dasar, $toppings, $id]);
        $successMessage = "Menu berhasil diperbarui!";
        $menu = getMenuById($id); // Refresh menu data
    } catch(PDOException $e) {
        $errorMessage = "Gagal memperbarui menu: " . $e->getMessage();
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-donker">Edit Menu</h1>
        <a href="menu.php" class="text-donker hover:text-blue-900">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <?php if (isset($successMessage)): ?>
        <?php echo showAlert($successMessage, 'success'); ?>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)): ?>
        <?php echo showAlert($errorMessage, 'error'); ?>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label for="nama" class="block text-gray-700">Nama Menu</label>
            <input type="text" name="nama" id="nama" required 
                   value="<?php echo htmlspecialchars($menu['nama']); ?>" 
                   class="border border-gray-300 rounded-lg w-full p-2">
        </div>
        <div class="mb-4">
            <label for="kategori" class="block text-gray-700">Kategori</label>
            <select name="kategori" id="kategori" required class="border border-gray-300 rounded-lg w-full p-2">
                <option value="takoyaki_classic" <?php echo $menu['kategori'] == 'takoyaki_classic' ? 'selected' : ''; ?>>
                    Takoyaki Classic
                </option>
                <option value="kuroyaki" <?php echo $menu['kategori'] == 'kuroyaki' ? 'selected' : ''; ?>>
                    Kuroyaki
                </option>
                <option value="canai" <?php echo $menu['kategori'] == 'canai' ? 'selected' : ''; ?>>
                    Canai
                </option>
                <option value="okonomiyaki" <?php echo $menu['kategori'] == 'okonomiyaki' ? 'selected' : ''; ?>>
                    Okonomiyaki
                </option>
                <option value="gyoza" <?php echo $menu['kategori'] == 'gyoza' ? 'selected' : ''; ?>>
                    Gyoza
                </option>
                <option value="dimsum_siumai" <?php echo $menu['kategori'] == 'dimsum_siumai' ? 'selected' : ''; ?>>
                    Dimsum Siumai
                </option>
                <option value="piscok" <?php echo $menu['kategori'] == 'piscok' ? 'selected' : ''; ?>>
                    Piscok
                </option>
            </select>
        </div>
        <div class="mb-4">
            <label for="harga_dasar" class="block text-gray-700">Harga Dasar</label>
            <input type="number" name="harga_dasar" id="harga_dasar" required 
                   value="<?php echo htmlspecialchars($menu['harga_dasar']); ?>" 
                   class="border border-gray-300 rounded-lg w-full p-2">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Toppings (opsional)</label>
            <div id="topping-container">
                <?php 
                if ($menu['toppings']) {
                    $toppings = json_decode($menu['toppings'], true);
                    foreach ($toppings as $name => $prices) {
                        echo '<div class="flex items-center mb-2">';
                        echo '<input type="text" name="toppings[]" value="' . htmlspecialchars($name) . '" 
                              class="border border-gray-300 rounded-lg w-full p-2 mr-2">';
                        echo '<input type="number" name="toppings_harga[]" value="' . htmlspecialchars($prices['harga'] ?? $prices['S']) . '" 
                              class="border border-gray-300 rounded-lg w-24 p-2">';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <button type="button" id="add-topping" class="bg-cerah text-donker px-4 py-2 rounded-lg hover:bg-yellow-400 transition duration-300">
                Tambah Topping
            </button>
        </div>
        <button type="submit" class="bg-donker text-white px-4 py-2 rounded-lg hover:bg-blue-900 transition duration-300">
            Simpan Perubahan
        </button>
    </form>
</div>

<script>
    document.getElementById('add-topping').addEventListener('click', function() {
        const container = document.getElementById('topping-container');
        const newTopping = document.createElement('div');
        newTopping.classList.add('flex', 'items-center', 'mb-2');
        newTopping.innerHTML = `
            <input type="text" name="toppings[]" placeholder="Nama Topping" class="border border-gray-300 rounded-lg w-full p-2 mr-2">
            <input type="number" name="toppings_harga[]" placeholder="Harga" class="border border-gray-300 rounded-lg w-24 p-2">
        `;
        container.appendChild(newTopping);
    });
</script>

<?php include '../includes/footer.php'; ?>
