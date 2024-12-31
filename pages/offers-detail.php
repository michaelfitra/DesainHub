<?php
include '../includes/header.php';

// Validate and get offer ID
$offer_id = isset($_GET['offer_id']) ? (int) $_GET['offer_id'] : 0;

if (!$offer_id) {
    header('Location: kategori.php');
    exit;
}

// Fetch offer details with related information
$query = "SELECT o.*, c.name as category_name, 
          u.id as freelancer_id, u.full_name, u.profile_photo, u.description as user_description,
          COUNT(DISTINCT t.id) as total_orders,
          COALESCE(AVG(r.rating), 0) as avg_rating,
          COUNT(DISTINCT r.id) as total_reviews
          FROM offers o
          JOIN users u ON o.user_id = u.id
          JOIN categories c ON o.category_id = c.id
          LEFT JOIN transactions t ON o.id = t.offer_id
          LEFT JOIN reviews r ON t.id = r.transaction_id
          WHERE o.id = ?
          GROUP BY o.id";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $offer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: kategori.php');
    exit;
}

$offer = $result->fetch_assoc();

// Fetch offer gallery images
$gallery_query = "SELECT * FROM offer_gallery WHERE offer_id = ?";
$gallery_stmt = $conn->prepare($gallery_query);
$gallery_stmt->bind_param("i", $offer_id);
$gallery_stmt->execute();
$gallery_images = $gallery_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($offer['title']); ?> | DesainHub</title>
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

        .carousel-thumbnails {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding: 10px 0;
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
        }

        .carousel-thumbnails::-webkit-scrollbar {
            height: 8px;
        }

        .carousel-thumbnails::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .carousel-thumbnails::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .carousel-thumbnails .thumb {
            flex: 0 0 auto;
            width: 100px;
            aspect-ratio: 4/3;
            object-fit: cover;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s;
            border-radius: .375rem;
        }

        .carousel-thumbnails .thumb.active,
        .carousel-thumbnails .thumb:hover {
            opacity: 1;
            border: 2px solid #007bff;
        }

        .carousel-item {
            aspect-ratio: 16 / 9;
            overflow: hidden;
            border-radius: .375rem;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background-color: #ddd;
        }
    </style>
</head>

<body>

    <!-- Service Detail Section -->
    <div class="container my-3">
        <div class="row">
            <div class="col-lg-8">
                <!-- Profile Header -->
                <div class="row align-items-center mb-4">
                    <div class="col-sm-2 d-flex justify-content-center mx-auto mx-sm-0" style="width: 124px">
                        <a href="public-profile.php?user_id=<?php echo $offer['freelancer_id']; ?>">
                            <img src="<?php echo htmlspecialchars($offer['profile_photo']); ?>"
                                alt="<?php echo htmlspecialchars($offer['full_name']); ?>"
                                class="img-fluid rounded-circle">
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="public-profile.php?user_id=<?php echo $offer['freelancer_id']; ?>"
                            class="text-black text-decoration-none">
                            <h2><?php echo htmlspecialchars($offer['full_name']); ?></h2>
                        </a>
                        <p class="text-muted"><?php echo htmlspecialchars($offer['user_description']); ?></p>
                        <div class="d-flex align-items-center">
                            <span class="text-warning">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i
                                        class="bi bi-star<?php echo $i <= round($offer['avg_rating']) ? '-fill' : ''; ?>"></i>
                                <?php endfor; ?>
                            </span>
                            <span class="ms-2 text-muted">
                                (<?php echo number_format($offer['avg_rating'], 1); ?>)
                                <?php echo $offer['total_reviews']; ?> Reviews
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Service Image and Description -->
                <h1 class="mb-3"><?php echo htmlspecialchars($offer['title']); ?></h1>
                <p class="text-muted">Kategori: <?php echo htmlspecialchars($offer['category_name']); ?></p>

                <div id="carouselExampleIndicators" class="carousel slide">
                    <div class="carousel-inner rounded">
                        <div class="carousel-item active">
                            <img src="../<?php echo htmlspecialchars($offer['thumbnail']); ?>" class="d-block w-100"
                                alt="Main Image">
                        </div>
                        <?php foreach ($gallery_images as $image): ?>
                            <div class="carousel-item">
                                <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" class="d-block w-100"
                                    alt="Gallery Image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <!-- Thumbnails -->
                <div class="carousel-thumbnails">
                    <img src="../<?php echo htmlspecialchars($offer['thumbnail']); ?>" class="thumb active"
                        data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0">
                    <?php foreach ($gallery_images as $key => $image): ?>
                        <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" class="thumb"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $key + 1; ?>">
                    <?php endforeach; ?>
                </div>

                <br>
                <h5>Deskripsi Layanan</h5>
                <p><?php echo nl2br(htmlspecialchars($offer['description'])); ?></p>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <h6>Durasi Pengerjaan</h6>
                        <p><?php echo $offer['duration']; ?> hari</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Revisi</h6>
                        <p><?php echo $offer['revisions']; ?> kali</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Dibuat pada</h6>
                        <p><?php echo date('d M Y', strtotime($offer['created_at'])); ?></p>
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

                <!-- Reviews Section -->
                <?php
                $query_reviews = "SELECT r.*, 
                                         u.full_name AS client_name, 
                                         r.rating AS review_rating, 
                                         r.review_text, 
                                         r.created_at
                                  FROM reviews r
                                  JOIN transactions t ON r.transaction_id = t.id
                                  JOIN users u ON t.client_id = u.id
                                  WHERE t.offer_id = ?
                                  ORDER BY r.created_at DESC";

                $stmt_reviews = $conn->prepare($query_reviews);
                $stmt_reviews->bind_param("i", $offer_id);
                $stmt_reviews->execute();
                $reviews_result = $stmt_reviews->get_result();
                ?>

                <h5 class="mt-4">Reviews</h5>
                <?php if ($reviews_result->num_rows > 0): ?>
                    <?php while ($review = $reviews_result->fetch_assoc()): ?>
                        <div class="border p-3 rounded mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-warning">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?php echo $i <= $review['review_rating'] ? '-fill' : ''; ?>"></i>
                                    <?php endfor; ?>
                                </span>
                                <span class="ms-2">(<?php echo $review['review_rating']; ?>)</span>
                            </div>
                            <h6><?php echo htmlspecialchars($review['client_name']); ?></h6>
                            <p class="text-muted mb-1">
                                <?php echo date('d M Y', strtotime($review['created_at'])); ?>
                            </p>
                            <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="border p-3 rounded mb-3">
                        <p class="mb-0">Belum ada ulasan.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Bagian Kiri -->
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="border p-4 mb-4 rounded">
                    <h4 class="text-center">Order Summary</h4>
                    <h3 class="text-center text-primary">
                        Rp <?php echo number_format($offer['price'], 0, ',', '.'); ?>
                    </h3>
                    <hr>
                    <ul class="list-unstyled">
                        <li>✓ Durasi: <?php echo $offer['duration']; ?> hari</li>
                        <li>✓ Revisi: <?php echo $offer['revisions']; ?> kali</li>
                        <li>✓ <?php echo $offer['total_orders']; ?> Pesanan Selesai</li>
                    </ul>
                    <a href="#" class="btn btn-primary w-100">Pesan Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const thumbnailsContainer = document.querySelector('.carousel-thumbnails');
            const thumbnails = document.querySelectorAll('.thumb');
            const carousel = document.getElementById('carouselExampleIndicators');

            // Function to update thumbnails based on current slide
            function updateThumbnails(currentSlide) {
                thumbnails.forEach((thumb, index) => {
                    // Remove active class from all thumbnails
                    thumb.classList.remove('active');

                    // Add active class to the current slide's thumbnail
                    if (index === currentSlide) {
                        thumb.classList.add('active');

                        // Smooth scroll to center the active thumbnail
                        thumbnailsContainer.scrollTo({
                            left: thumb.offsetLeft - (thumbnailsContainer.offsetWidth / 2) + (thumb.offsetWidth / 2),
                            behavior: 'smooth'
                        });
                    }
                });
            }

            // Handle thumbnail clicks
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function () {
                    const slideIndex = parseInt(this.getAttribute('data-bs-slide-to'));
                    const carouselInstance = bootstrap.Carousel.getInstance(carousel) || new bootstrap.Carousel(carousel);
                    carouselInstance.to(slideIndex);
                });
            });

            // Handle slide change events
            carousel.addEventListener('slide.bs.carousel', function (event) {
                updateThumbnails(event.to);
            });

            // Initialize with the first thumbnail active
            updateThumbnails(0);
        }); 
    </script>
</body>

</html>