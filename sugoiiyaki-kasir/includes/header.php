<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugoiiyaki - Sistem Kasir</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        donker: '#1e1b4b',
                        cerah: '#ffd700'
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>
    <nav class="bg-donker text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-bold">Sugoiiyaki</a>
            <div class="space-x-4">
                <a href="/admin/transaksi.php" class="hover:text-cerah">Transaksi</a>
                <a href="/admin/menu.php" class="hover:text-cerah">Kelola Menu</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
