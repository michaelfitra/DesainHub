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
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="bi bi-bell"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="bi bi-envelope"></i>
                    </a>
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
                        <img src="<?php echo ASSETS_PATH_IMG ?>CoverImage.jpg" class="img-fluid ms-2"
                            style="height: 30px;width: 30px;; border-radius: 50px;" alt="">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-3">
                        <li><a class="dropdown-item" href="<?php echo ASSETS_PATH_PAGES; ?>dashboard-profile.php">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Daftar Sebagai Freelancher</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>