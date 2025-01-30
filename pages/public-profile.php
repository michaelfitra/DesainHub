<?php
include '../includes/header.php';

// Get user ID from URL parameter
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// Fetch user profile data
$profile_query = "SELECT u.*, 
    COUNT(DISTINCT t.id) as total_orders,
    COALESCE(AVG(fr.rating), 0) as avg_rating,
    COUNT(DISTINCT fr.id) as total_reviews
    FROM users u
    LEFT JOIN transactions t ON u.id = t.freelancer_id
    LEFT JOIN freelancer_reviews fr ON t.id = fr.transaction_id
    WHERE u.id = ? AND u.is_freelancer = 1
    GROUP BY u.id";

$stmt = $conn->prepare($profile_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();

if (!$profile) {
    header('Location: kategori.php');
    exit;
}

// Fetch user's active offers
$offers_query = "SELECT o.*, c.name as category_name
    FROM offers o
    JOIN categories c ON o.category_id = c.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC";

$stmt = $conn->prepare($offers_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$offers = $stmt->get_result();

// Fetch recent reviews
$reviews_query = "SELECT fr.*, t.id as transaction_id, 
    u.full_name as client_name, u.profile_photo as client_photo,
    o.title as service_title
    FROM freelancer_reviews fr
    JOIN transactions t ON fr.transaction_id = t.id
    JOIN users u ON fr.client_id = u.id
    JOIN offers o ON t.offer_id = o.id
    WHERE fr.freelancer_id = ?
    ORDER BY fr.created_at DESC
    LIMIT 5";

$stmt = $conn->prepare($reviews_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reviews = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($profile['full_name']) ?> | DesainHub</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <div class="container mt-4">
        <!-- Profile Section -->
        <div class="row">
            <div class="col-sm-12 col-md-4 col-xl-3 text-center">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <img src="<?= htmlspecialchars($profile['profile_photo']) ?>" 
                             alt="<?= htmlspecialchars($profile['full_name']) ?>"
                             class="img-fluid rounded-circle mb-3"
                             style="width: 200px; height: 200px; object-fit: cover;">
                        <h3><?= htmlspecialchars($profile['full_name']) ?></h3>
                        <p class="text-muted"><?= htmlspecialchars($profile['occupation']) ?></p>
                        <div class="rating">
                            <div class="d-flex justify-content-center gap-2">
                                <span class="text-warning">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?= $i <= round($profile['avg_rating']) ? '-fill' : '' ?>"></i>
                                    <?php endfor; ?>
                                </span>
                                <span>(<?= $profile['total_reviews'] ?> reviews)</span>
                            </div>
                        </div>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $user_id): ?>
                            <a href="chat.php?user_id=<?= $user_id ?>" class="btn btn-primary btn-sm mt-3">
                                <i class="bi bi-chat-dots"></i> Contact
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Skills & Languages -->
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <?php if (!empty($profile['skills'])): ?>
                            <h5>Skills</h5>
                            <div class="mb-3">
                                <?php foreach(explode(',', $profile['skills']) as $skill): ?>
                                    <span class="badge bg-primary m-1"><?= htmlspecialchars(trim($skill)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($profile['languages'])): ?>
                            <h5>Languages</h5>
                            <div>
                                <?php foreach(explode(',', $profile['languages']) as $language): ?>
                                    <span class="badge bg-secondary m-1"><?= htmlspecialchars(trim($language)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="col-sm-12 col-md-8 col-xl-9">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4>About</h4>
                        <p><?= nl2br(htmlspecialchars($profile['description'])) ?></p>
                        
                        <?php if (!empty($profile['education'])): ?>
                            <hr>
                            <h4>Education</h4>
                            <ul>
                                <?php foreach(explode(',', $profile['education']) as $edu): ?>
                                    <li><?= htmlspecialchars(trim($edu)) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (!empty($profile['certifications'])): ?>
                            <hr>
                            <h4>Certifications</h4>
                            <ul>
                                <?php foreach(explode(',', $profile['certifications']) as $cert): ?>
                                    <li><?= htmlspecialchars(trim($cert)) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Active Offers Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Active Offers</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if ($offers->num_rows > 0): ?>
                                <?php while($offer = $offers->fetch_assoc()): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <img src="../<?= htmlspecialchars($offer['thumbnail']) ?>" 
                                                 class="card-img-top" 
                                                 alt="<?= htmlspecialchars($offer['title']) ?>"
                                                 style="height: 200px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($offer['title']) ?></h5>
                                                <p class="card-text text-muted"><?= htmlspecialchars(substr($offer['description'], 0, 100)) ?>...</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-primary"><?= htmlspecialchars($offer['category_name']) ?></span>
                                                    <h6 class="text-primary mb-0">Rp <?= number_format($offer['price'], 0, ',', '.') ?></h6>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="offers-detail.php?offer_id=<?= $offer['id'] ?>" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <p class="text-muted text-center mb-0">No active offers available.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Reviews</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($reviews->num_rows > 0): ?>
                            <?php while($review = $reviews->fetch_assoc()): ?>
                                <div class="border-bottom mb-3 pb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="<?= htmlspecialchars($review['client_photo']) ?>" 
                                             class="rounded-circle me-2"
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0"><?= htmlspecialchars($review['client_name']) ?></h6>
                                            <small class="text-muted"><?= htmlspecialchars($review['service_title']) ?></small>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="text-warning">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill' : '' ?>"></i>
                                                <?php endfor; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                                    <small class="text-muted"><?= date('d M Y', strtotime($review['created_at'])) ?></small>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted text-center mb-0">No reviews yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?> <!-- Footer file -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.js"></script>

</body>

</html>