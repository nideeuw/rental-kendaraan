<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?>Sistem Rental Kendaraan</title>
    <link rel="stylesheet" href="/rental-kendaraan/css/style.css">
    <script src="/rental-kendaraan/js/script.js" defer></script>
</head>

<body>
    <div class="dashboard-layout">
        <?php include __DIR__ . '/sidebar.php'; ?>
        <div class="main-wrapper">
            <!-- TOP NAVBAR -->
            <header class="top-navbar">
                <div class="navbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <h1 class="page-title">
                        <?= isset($page_title) ? $page_title : 'Sistem Rental Kendaraan'; ?>
                    </h1>
                </div>

                <div class="navbar-right">
                    <div class="user-info">
                        <span class="user-icon">👤</span>
                        <span class="user-name">Admin</span>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT -->
            <main class="main-content">