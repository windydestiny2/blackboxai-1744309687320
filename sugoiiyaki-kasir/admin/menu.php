<?php 
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle delete action
if (isset($_POST['delete']) && isset($_POST['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $successMessage = "Menu berhasil dihapus!";
    } catch(PDOException $e) {
        $errorMessage = "Gagal menghapus menu: " . $e->getMessage();
    }
}

// Get all menus
$menus = getAllMenu();
?>

<?php include '../includes/header.php'; ?>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-donker">Kelola Menu</h1>
        <a href="add_menu.php" class="bg-cerah text-donker px-4 py-2 rounded-lg hover:bg-yellow-400 transition duration-300">
            <i class="fas fa-plus mr-2"></i>Tambah Menu
        </a>
    </div>

    <?php if (isset($successMessage)): ?>
        <?php echo showAlert($successMessage, 'success'); ?>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)): ?>
        <?php echo showAlert($errorMessage, 'error'); ?>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Menu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($menus as $menu): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($menu['nama']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($menu['kategori']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php 
                        if (isset($menu['toppings']) && !empty($menu['toppings'])) {
                            $toppings = json_decode($menu['toppings'], true);
                            if (isset($toppings['harga'])) {
                                echo formatRupiah($toppings['harga']);
                            } else {
                                $prices = array_values($toppings);
                                echo "Mulai dari " . formatRupiah(min($prices));
                            }
                        } else {
                            echo formatRupiah($menu['harga_dasar']);
                        }
                        ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php 
                        if (isset($menu['toppings']) && !empty($menu['toppings'])) {
                            echo "Dengan pilihan topping";
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <a href="edit_menu.php?id=<?php echo $menu['id']; ?>" 
                               class="bg-donker text-white px-3 py-1 rounded hover:bg-blue-900 transition duration-300">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                                <button type="submit" name="delete" 
                                        class="bg-maroon text-white px-3 py-1 rounded hover:bg-red-900 transition duration-300">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
