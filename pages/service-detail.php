<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Jasa - Umrifess</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .package-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
        }

        .package-price {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .review-rating {
            color: #ffc107;
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <!-- Service Detail Section -->
    <div class="container my-3">
        <div class="row">
            <div class="col-lg-8">
                <!-- Profile Header -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-2 d-flex justify-content-center">
                        <img src="../assets/Images/CoverImage.jpg" alt="User Photo" class="img-fluid rounded-circle"
                            style="height: 100px; width: 100px;">
                    </div>
                    <div class="col-md-6">
                        <h2>User Name</h2>
                        <p class="text-muted">Professional Tagline (e.g., Graphic Designer, Web Developer)</p>
                        <div class="d-flex align-items-center">
                            <span class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </span>
                            <span class="ms-2 text-muted">(4.5) 120 Reviews</span>
                        </div>
                    </div>
                    <!-- <div class="col-md-3 text-md-end">
                    <a href="#" class="btn btn-outline-secondary">Edit Profile</a>
                </div> -->
                </div>
                <!-- Service Image and Description -->
                <h1 class="mb-3">Judul Jasa</h1>
                <p class="text-muted">Kategori: Desain Grafis</p>

                <img src="../assets/Images/CoverImage.jpg" alt="Service Image" class="img-fluid rounded mb-3">

                <h5>Deskripsi Layanan</h5>
                <p>Deskripsi lengkap tentang jasa ini, termasuk apa saja yang akan diterima klien, teknik dan alat
                    yang digunakan, serta keunggulan freelancer dalam layanan ini.</p>

                <hr>

                <!-- Packages Section -->
                <h5>Paket Layanan</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="package-card">
                            <h6>Basic</h6>
                            <p>Deskripsi singkat untuk paket Basic.</p>
                            <div class="package-price">$XX</div>
                            <a href="#" class="btn btn-outline-primary w-100 mt-2">Order Basic</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="package-card">
                            <h6>Standard</h6>
                            <p>Deskripsi singkat untuk paket Standard.</p>
                            <div class="package-price">$YY</div>
                            <a href="#" class="btn btn-outline-primary w-100 mt-2">Order Standard</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="package-card">
                            <h6>Premium</h6>
                            <p>Deskripsi singkat untuk paket Premium.</p>
                            <div class="package-price">$ZZ</div>
                            <a href="#" class="btn btn-outline-primary w-100 mt-2">Order Premium</a>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- FAQ Section -->
                <h5>FAQ</h5>
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Apa saja yang termasuk dalam layanan ini?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Penjelasan mengenai detail layanan yang disediakan.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Berapa lama waktu pengerjaan?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Penjelasan mengenai estimasi waktu pengerjaan.
                            </div>
                        </div>
                    </div>
                    <!-- Tambahkan lebih banyak pertanyaan jika diperlukan -->
                </div>
            </div>

            <!-- Order Summary and Reviews -->
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="border p-4 mb-4 rounded">
                    <h4 class="text-center">Order Summary</h4>
                    <p>Ringkasan paket yang dipilih serta harga dan estimasi waktu pengerjaan.</p>
                    <a href="#" class="btn btn-primary w-100">Proceed to Order</a>
                </div>

                <!-- Reviews Section -->
                <h5>Reviews</h5>

                <div class="border p-3 rounded mb-3">
                    <div class="d-flex align-items-center mb-3">
                        <span class="review-rating">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </span>
                        <span class="ms-2">(4.5) 120 Reviews</span>
                    </div>

                    <!-- Example Review -->
                    <div class="mb-3">
                        <h6>User Name</h6>
                        <p class="mb-1 text-muted">Tanggal Review</p>
                        <p>Ulasan detail yang ditulis oleh pengguna mengenai pengalaman mereka dengan jasa ini.</p>
                    </div>
                </div>
                <!-- Repeat for more reviews -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>