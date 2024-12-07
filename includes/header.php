<?php
require $_SERVER['DOCUMENT_ROOT'] . '/DesainHub/config/config.php';
?>
<!-- Navbar -->
<nav class="navbar sticky-top navbar-expand-lg bg-dark navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php"><strong>Desain</strong>Hub <em>UMRI</em></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Bagian ini disembunyikan jika user login -->
        <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2 text-light">
                <li class="nav-item">
                    <a class="nav-link" href="daftar-kerja.html">Tawarkan Jasa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo ASSETS_PATH_PAGES; ?>daftar.php">Daftar</a>
                </li>
            </ul>
            <form class="d-flex ms-lg-3" role="search">
                <a href="<?php echo ASSETS_PATH_PAGES; ?>masuk.php" class="btn btn-outline-success" type="submit">Masuk</a>
            </form>
        </div> -->

        <!-- Bagian ini muncul jika user login -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3 text-light d-flex align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown" href="" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg" aria-labelledby="notificationDropdown"
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
                            <!-- Tambahkan notifikasi lain di sini -->
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown" href="" data-bs-toggle="dropdown">
                        <i class="bi bi-envelope"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg" aria-labelledby="notificationDropdown"
                        style="width: 300px; max-height: 400px; overflow-y: auto;">
                        <h6 class="dropdown-header pt-0">Pesan</h6>
                        <div class="list-group">
                            <!-- Contoh Notifikasi -->
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Another User</h6>
                                    <small class="text-muted">2 jam lalu</small>
                                </div>
                                <p class="mb-1">Pesan dari user lain muncul di sini</p>
                            </a>
                            <!-- Tambahkan notifikasi lain di sini -->
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="bi bi-heart"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Pesanan</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle p-0" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="<?php echo ASSETS_PATH_IMG ?>profile/profile-pic.webp" class="img-fluid ms-2"
                            style="height: 30px;width: 30px;; border-radius: 50px;" alt="">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-3">
                        <li><a class="dropdown-item"
                                href="<?php echo ASSETS_PATH_PAGES; ?>dashboard-profile.php">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?php echo ASSETS_PATH_PAGES; ?>daftar-freelance.php">Daftar Sebagai Freelancher</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>