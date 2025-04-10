-- Tabel `menus`
CREATE TABLE IF NOT EXISTS menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    harga_dasar INT NOT NULL,
    toppings TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel `transaksi`
CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(255) NOT NULL,
    total_harga INT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel `transaksi_detail`
CREATE TABLE IF NOT EXISTS transaksi_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    menu_id INT NOT NULL,
    ukuran VARCHAR(50),
    harga INT NOT NULL,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
);

-- Insert sample menu data
INSERT INTO menus (nama, kategori, harga_dasar, toppings) VALUES
('Takoyaki Classic Sosis', 'takoyaki_classic', 12000, '{"S": 12000, "M": 24000, "L": 28000}'),
('Takoyaki Classic Keju', 'takoyaki_classic', 12000, '{"S": 12000, "M": 24000, "L": 28000}'),
('Takoyaki Classic Mix', 'takoyaki_classic', 12000, '{"S": 12000, "M": 24000, "L": 28000}'),
('Takoyaki Classic Octopus', 'takoyaki_classic', 15000, '{"S": 15000, "M": 27000, "L": 30000}'),
('Kuroyaki Sosis', 'kuroyaki', 15000, '{"S": 15000, "M": 27000, "L": 30000}'),
('Kuroyaki Keju', 'kuroyaki', 15000, '{"S": 15000, "M": 27000, "L": 30000}'),
('Kuroyaki Mix', 'kuroyaki', 15000, '{"S": 15000, "M": 27000, "L": 30000}'),
('Kuroyaki Octopus', 'kuroyaki', 18000, '{"S": 18000, "M": 30000, "L": 32000}'),
('Canai Original', 'canai', 15000, '{"harga": 15000}'),
('Canai Coklat', 'canai', 15000, '{"harga": 15000}'),
('Okonomiyaki', 'okonomiyaki', 25000, NULL),
('Gyoza', 'gyoza', 25000, NULL),
('Dimsum Siumai', 'dimsum_siumai', 25000, NULL),
('Piscok', 'piscok', 15000, NULL);
