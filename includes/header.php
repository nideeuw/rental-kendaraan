<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rental Kendaraan</title>
    <link rel="stylesheet" href="/rental-kendaraan/css/style.css">
    <script src="/rental-kendaraan/js/script.js" defer></script>
</head>

<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>RENTAL</h1>
                <p>Sistem Rental Kendaraan</p>
            </div>

            <nav class="sidebar-menu">
                <?php
                $listMenu = [
                    [
                        'url' => 'action=tipe_list',
                        'label' => 'Data Tipe Kendaraan'
                    ],
                    [
                        'url' => 'action=kendaraan_list',
                        'label' => 'Data Kendaraan'
                    ],
                    [
                        'url' => 'action=sopir_list',
                        'label' => 'Data Sopir'
                    ],
                    [
                        'url' => 'action=pelanggan_list',
                        'label' => 'Data Pelanggan'
                    ],

                    [
                        'url' => 'action=pelanggan_total_denda',
                        'label' => 'Cek Total Denda'
                    ],

                    [
                        'url' => 'action=rental_list',
                        'label' => 'Data Rental'
                    ],
                    [
                        'url' => 'action=pengembalian_list',
                        'label' => 'Data Pengembalian'
                    ],
                    [
                        'url' => 'action=kendaraan_tersedia',
                        'label' => 'Kendaraan Tersedia'
                    ],
                    [
                        'url' => 'action=sopir_tersedia',
                        'label' => 'Sopir Tersedia'
                    ],
                    [
                        'url' => 'action=laporan_transaksi',
                        'label' => 'Laporan Transaksi'
                    ]
                ];

                foreach ($listMenu as $menu) {
                    echo '<a href="index.php?' . $menu['url'] . '" class="menu-link">' . $menu['label'] . '</a>' . "\n";
                }
                ?>
            </nav>
        </aside>

        <div class="main-wrapper">
            <!-- TOP NAVBAR -->
            <header class="top-navbar">
                <h1 class="page-title">
                    <?= isset($page_title) ? $page_title : 'Sistem Rental Kendaraan'; ?>
                </h1>

                <div class="user-info">
                    <span>Admin</span>
                </div>
            </header>

            <!-- MAIN CONTENT -->
            <main class="main-content">