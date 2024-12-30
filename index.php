<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>DesainHub UMRI</title>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <!-- Banner -->
    <div class="container mt-md-4 banner d-flex justify-content-center align-items-center" style="height: 50vh;">
        <div class="text-center col-lg-9">
            <h4 class="ts-md-sm"><strong>Desain</strong>HUB UMRI</h4>
            <h1>Tingkatkan tim profesional Anda dengan bantuan talenta mahasiswa UMRI!</h1>
            <div class="mt-5 offset-2 col-8">
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Cari layanan yang Anda butuhkan...">
                    <button class="search-button text-light">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="text-start mt-4">
                    <span class="text-muted">Populer:</span>
                    <a href="#" class="btn btn-sm btn-outline-secondary ms-2">Logo Design</a>
                    <a href="#" class="btn btn-sm btn-outline-secondary ms-2">Web Development</a>
                    <a href="#" class="btn btn-sm btn-outline-secondary ms-2">Content Writing</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Kategori -->
    <div class="container mt-4">
        <!-- <h2 class="text-center">Telusuri Kategori Layanan</h2> -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
            <div class="col">
                <a href="pages/kategori.php" class="text-decoration-none">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-pencil-square display-4" style="color: #004d00;"></i>
                            <h5 class="card-title mt-2">Desain Grafis</h5>
                            <p class="card-text">Jasa desain logo, brosur, kartu nama, dan lainnya.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="pages/kategori.php" class="text-decoration-none">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-code-slash display-4" style="color: #004d00;"></i>
                            <h5 class="card-title mt-2">Pengembangan Web</h5>
                            <p class="card-text">Layanan pembuatan website, aplikasi, dan sistem informasi.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="pages/kategori.php" class="text-decoration-none">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-camera display-4" style="color: #004d00;"></i>
                            <h5 class="card-title mt-2">Fotografi & Videografi</h5>
                            <p class="card-text">Jasa fotografi, editing foto, editing video, dan animasi.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="pages/kategori.php" class="text-decoration-none">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-mic display-4" style="color: #004d00;"></i>
                            <h5 class="card-title mt-2">Penulisan & Editing</h5>
                            <p class="card-text">Layanan penulisan konten, artikel, dan proofreading.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- <div class="m-5"><br></div> -->

    <!-- Section Fitur Utama -->
    <div class="container my-5">
        <h2 class="text-center">Kenapa Memilih DesainHub UMRI?</h2>
        <div class="row text-center mt-4">
            <div class="col-md-4">
                <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                <h5>Jaminan Kualitas</h5>
                <p>Setiap freelancer melalui proses kurasi ketat untuk menjamin kualitas layanan.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-wallet2 text-success" style="font-size: 2rem;"></i>
                <h5>Harga Terjangkau</h5>
                <p>Dapatkan layanan berkualitas dengan harga yang sesuai budget Anda.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-search text-success" style="font-size: 2rem;"></i>
                <h5>Cari Layanan Mudah</h5>
                <p>Dapatkan layanan dengan mudah melalui pencarian dan kategori kami.</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="container" style="overflow: hidden;">
        <div id="carouselExampleAutoplaying" class="carousel slide position-relative" data-bs-ride="carousel">

            <div class="cta-section position-absolute top-50 start-50 translate-middle text-center">
                <h2>Siap Memulai?</h2>
                <h4>Bergabung sekarang dan dapatkan layanan terbaik dari freelancer UMRI.</h4>
                <a href="pages/masuk.php" class="btn btn-success btn-lg mt-2">Daftar Sekarang</a>
            </div>

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner rounded-3">
                <div class="carousel-item active">
                    <img src="assets/images/image (1).jpeg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/image (2).jpeg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/image (3).jpeg" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Section Testimoni -->
    <div class="container my-5">
        <h2 class="text-center mb-5">Apa Kata Pengguna Kami</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p>"Platform yang luar biasa, saya menemukan freelancer terbaik di sini!"</p>
                    <footer class="blockquote-footer">Mahasiswa UMRI, <cite title="Source Title">Yudi</cite></footer>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p>"Sangat mudah dan banyak pilihan, akan selalu menggunakan DesainHub UMRI."</p>
                    <footer class="blockquote-footer">Dosen UMRI, <cite title="Source Title">Dewi</cite></footer>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p>"Layanan freelancer yang sesuai dengan kebutuhan kampus."</p>
                    <footer class="blockquote-footer">Staff UMRI, <cite title="Source Title">Budi</cite></footer>
                </blockquote>
            </div>
        </div>
    </div>

    <!-- Card -->
    <div class="container my-4">
        <div class="row">
            <!-- Pengguna Terbaru Section -->
            <div class="col-xl-6">
                <h2 class="mb-4">Jasa Terbaru</h2>
                <div class="row">
                    <!-- Example of a card for a new user -->
                    <div class="col-md-6 mb-4">
                        <?php include 'includes/card.php'; ?>
                    </div>
                </div>
            </div>

            <!-- Freelancer Terpopuler Section -->
            <div class="col-xl-6">
                <h2 class="mb-4">Jasa Terpopuler</h2>
                <div class="row">
                    <!-- Example of a card for a popular freelancer -->
                    <div class="col-md-6 mb-4">
                        <?php include 'includes/card.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.js"></script>

</body>

</html>