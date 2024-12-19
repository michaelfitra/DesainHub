<?php
require $_SERVER['DOCUMENT_ROOT'] . '/DesainHub/config/config.php';

// Cek status login
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Default profile photo
$profilePhoto = ASSETS_PATH_IMG . "profile/profile-pic.webp";

// Jika user sudah login, ambil foto profil
if ($isLoggedIn) {
    try {
        // Persiapkan statement untuk mengambil foto profil
        $stmt = $conn->prepare("SELECT profile_photo, is_freelancer FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika user ditemukan
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Gunakan foto profil dari database jika ada
            if (!empty($user['profile_photo'])) {
                $profilePhoto = $user['profile_photo'];
            }
        }

        $stmt->close();
    } catch (Exception $e) {
        // Log error atau tangani kesalahan
        error_log("Error retrieving profile photo: " . $e->getMessage());
    }
}
?>

<!-- Navbar -->
<nav class="navbar sticky-top navbar-expand-lg bg-dark navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">
            <strong>Desain</strong>Hub <em>UMRI</em>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <?php if ($isLoggedIn): ?>
            <!-- Navbar untuk user yang sudah login -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3 text-light d-flex align-items-center">
                    <!-- Notifikasi -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg"
                            style="width: 300px; max-height: 400px; overflow-y: auto;">
                            <h6 class="dropdown-header pt-0">Notifikasi</h6>
                            <div class="list-group">
                                <!-- Contoh Notifikasi -->
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Diskon 20%</h6>
                                        <small class="text-muted">19 jam lalu</small>
                                    </div>
                                    <p class="mb-1">Dapatkan diskon 20% pada pesanan pertama Anda. Gunakan kode GETMORE20
                                        saat checkout.</p>
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- Pesan -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-envelope"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg"
                            style="width: 300px; max-height: 400px; overflow-y: auto;">
                            <h6 class="dropdown-header pt-0">Pesan</h6>
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Another User</h6>
                                        <small class="text-muted">2 jam lalu</small>
                                    </div>
                                    <p class="mb-1">Pesan dari user lain muncul di sini</p>
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- Favorit -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ASSETS_PATH_PAGES; ?>favorites.php">
                            <i class="bi bi-heart"></i>
                        </a>
                    </li>

                    <!-- Pesanan -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ASSETS_PATH_PAGES; ?>orders.php">Pesanan</a>
                    </li>

                    <!-- Profil Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="<?php echo $profilePhoto; ?>" class="img-fluid ms-2"
                                style="height: 30px; width: 30px; border-radius: 50px; object-fit: cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-3">
                            <li><a class="dropdown-item"
                                    href="<?php echo ASSETS_PATH_PAGES; ?>dashboard-profile.php">Profil</a></li>
                            <li><a class="dropdown-item"
                                    href="<?php echo ASSETS_PATH_PAGES; ?>pengaturan.php">Pengaturan</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <?php if ($user['is_freelancer'] == 0): ?>
                                <li><a class="dropdown-item" href="<?php echo ASSETS_PATH_PAGES; ?>daftar-freelance.php">Daftar Sebagai Freelancer</a></li>
                            <?php else: ?>    
                                <li><a class="dropdown-item" href="<?php echo ASSETS_PATH_PAGES; ?>daftar-freelance.php">Dashboard Freelancer</a></li>
                            <?php endif; ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>config/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php else: ?>
            <!-- Navbar untuk user yang belum login -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2 text-light">
                    <li class="nav-item">
                        <a class="nav-link" href="daftar-kerja.html">Tawarkan Jasa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ASSETS_PATH_PAGES; ?>daftar.php">Daftar</a>
                    </li>
                </ul>
                <form class="d-flex ms-lg-3" role="search">
                    <a href="<?php echo ASSETS_PATH_PAGES; ?>masuk.php" class="btn btn-outline-success"
                        type="submit">Masuk</a>
                </form>
            </div>
        <?php endif; ?>
    </div>
</nav>