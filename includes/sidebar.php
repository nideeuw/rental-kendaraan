<?php

$listMenu = [
    [
        'section' => 'Dashboard',
        'items' => [
            [
                'url' => 'index.php?action=dashboard',
                'label' => 'Dashboard'
            ]
        ]
    ],
    [
        'section' => 'Master Data',
        'items' => [
            [
                'type' => 'submenu',
                'label' => 'Master',
                'submenu_id' => 'crud-menu',
                'children' => [
                    [
                        'url' => 'index.php?action=tipe_list',
                        'label' => 'Data Tipe Kendaraan'
                    ],
                    [
                        'url' => 'index.php?action=kendaraan_list',
                        'label' => 'Data Kendaraan'
                    ],
                    [
                        'url' => 'index.php?action=sopir_list',
                        'label' => 'Data Sopir'
                    ],
                    [
                        'url' => 'index.php?action=pelanggan_list',
                        'label' => 'Data Pelanggan'
                    ]
                ]
            ]
        ]
    ],
    [
        'section' => 'Transaksi',
        'items' => [
            [
                'url' => 'index.php?action=rental_list',
                'label' => 'Data Rental'
            ],
            [
                'url' => 'index.php?action=pengembalian_list',
                'label' => 'Data Pengembalian'
            ]
        ]
    ],
    [
        'section' => 'Ketersediaan',
        'items' => [
            [
                'type' => 'submenu',
                'label' => 'Cek Ketersediaan',
                'submenu_id' => 'available-menu',
                'children' => [
                    [
                        'url' => 'index.php?action=kendaraan_tersedia',
                        'label' => 'Kendaraan Tersedia'
                    ],
                    [
                        'url' => 'index.php?action=sopir_tersedia',
                        'label' => 'Sopir Tersedia'
                    ]
                ]
            ]
        ]
    ],
    [
        'section' => 'Laporan',
        'items' => [
            [
                'type' => 'submenu',
                'label' => 'Laporan',
                'submenu_id' => 'report-menu',
                'children' => [
                    [
                        'url' => 'index.php?action=laporan_transaksi',
                        'label' => 'Laporan Transaksi'
                    ],
                    [
                        'url' => 'index.php?action=laporan_pendapatan',
                        'label' => 'Laporan Pendapatan'
                    ],
                    [
                        'url' => 'index.php?action=pelanggan_total_denda',
                        'label' => 'Total Denda Pelanggan'
                    ]
                ]
            ]
        ]
    ],
    [
        'section' => 'Demo',
        'items' => [
            [
                'url' => 'index.php?action=demo_transaction',
                'label' => 'Demo Transaction Management'
            ]
        ]
    ]
];
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h1>RENTAL</h1>
        <p>Sistem Rental Kendaraan</p>
    </div>

    <nav class="sidebar-menu">
        <?php foreach ($listMenu as $section): ?>
            <div class="menu-section">
                <div class="menu-section-title"><?= $section['section'] ?></div>
                
                <?php foreach ($section['items'] as $item): ?>
                    <?php if (isset($item['type']) && $item['type'] === 'submenu'): ?>
                        <!-- Submenu Item -->
                        <div class="menu-item-with-submenu">
                            <div class="menu-link menu-parent" data-submenu="<?= $item['submenu_id'] ?>">
                                <span class="menu-text"><?= $item['label'] ?></span>
                                <span class="menu-arrow">▼</span>
                            </div>
                            <div class="submenu" id="<?= $item['submenu_id'] ?>">
                                <?php foreach ($item['children'] as $child): ?>
                                    <a href="<?= $child['url'] ?>" class="submenu-link">
                                        <span><?= $child['label'] ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Regular Menu Item -->
                        <a href="<?= $item['url'] ?>" class="menu-link">
                            <span class="menu-text"><?= $item['label'] ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </nav>
</aside>