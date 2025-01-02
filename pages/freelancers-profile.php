<?php
include '../includes/header.php';

// Check login & freelancer status
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$freelancer_id = $_SESSION['user_id'];

// Verify user is freelancer
$check_query = "SELECT is_freelancer FROM users WHERE id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result || !$result['is_freelancer']) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar Section -->
            <?php
            // Get freelancer profile data
            $profile_query = "SELECT u.*, 
                COUNT(DISTINCT t.id) as total_orders,
                COALESCE(AVG(fr.rating), 0) as avg_rating
                FROM users u
                LEFT JOIN transactions t ON u.id = t.freelancer_id
                LEFT JOIN freelancer_reviews fr ON t.id = fr.transaction_id
                WHERE u.id = ? AND u.is_freelancer = 1
                GROUP BY u.id";

            $stmt = $conn->prepare($profile_query);
            $stmt->bind_param("i", $freelancer_id);
            $stmt->execute();
            $profile = $stmt->get_result()->fetch_assoc();
            ?>
            <div class="col-sm-12 col-md-4 col-xl-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="position-relative mb-3">
                            <img src="<?= htmlspecialchars($profile['profile_photo']) ?>" alt="Profile Photo"
                                class="rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        </div>

                        <h5 class="mb-1"><?= htmlspecialchars($profile['full_name']) ?></h5>
                        <p class="text-muted mb-2"><?= htmlspecialchars($profile['occupation']) ?></p>

                        <ul class="list-unstyled text-center mt-3 mb-4">
                            <li>
                                <div class="d-flex justify-content-center gap-2 mb-3">
                                    <span class="badge bg-primary">
                                        <i class="bi bi-star-fill"></i> <?= number_format($profile['avg_rating'], 1) ?>
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-briefcase-fill"></i> <?= $profile['total_orders'] ?> Orders
                                    </span>
                                </div>
                            </li>
                            <li>
                                <hr class="bg-danger border-2 border-top">
                            </li>
                            <li>
                                <?php if (!empty($profile['description'])): ?>
                                    <p class="small mb-3"><?= htmlspecialchars($profile['description']) ?></p>
                                <?php endif; ?>
                            </li>
                            <li>
                                <hr class="bg-danger border-2 border-top">
                            </li>
                            <li>
                                <?php
                                // Convert comma-separated strings to arrays
                                $skills = !empty($profile['skills']) ? explode(',', $profile['skills']) : [];
                                $languages = !empty($profile['languages']) ? explode(',', $profile['languages']) : [];
                                ?>

                                <?php if (!empty($skills)): ?>
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">Skills</h6>
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <?php foreach ($skills as $skill): ?>
                                                <span
                                                    class="badge bg-light text-dark"><?= htmlspecialchars(trim($skill)) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </li>
                            <li>
                                <hr class="bg-danger border-2 border-top">
                            </li>
                            <li>
                                <?php if (!empty($languages)): ?>
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">Languages</h6>
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <?php foreach ($languages as $language): ?>
                                                <span
                                                    class="badge bg-light text-dark"><?= htmlspecialchars(trim($language)) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </li>
                        </ul>

                        <div class="d-grid gap-2">
                            <a href="pengaturan.php" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <!-- <div class="list-group mt-4">
                    <a href="freelancer-dashboard.php" class="list-group-item list-group-item-action active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="freelancer-orders.php" class="list-group-item list-group-item-action">
                        <i class="bi bi-list-check"></i> Orders
                    </a>
                    <a href="earnings.php" class="list-group-item list-group-item-action">
                        <i class="bi bi-wallet2"></i> Earnings
                    </a>
                    <a href="withdrawals.php" class="list-group-item list-group-item-action">
                        <i class="bi bi-cash-stack"></i> Withdrawals
                    </a>
                    <a href="edit-profile.php" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </div> -->
            </div>

            <!-- Main Content Section -->
            <div class="col-sm-12 col-md-8 col-xl-9">
                <h2 class="mb-4">Freelancer Dashboard</h2>
                <?php
                // Get freelancer ID from session
                $freelancer_id = $_SESSION['user_id'];

                // Get overview statistics
                $stats_query = "SELECT 
                    COUNT(t.id) as total_orders,
                    COUNT(CASE WHEN t.status = 'completed' THEN 1 END) as completed_orders,
                    COUNT(CASE WHEN t.status = 'in_progress' THEN 1 END) as active_orders,
                    COALESCE(SUM(CASE WHEN t.status = 'completed' THEN t.total_price END), 0) as total_earnings,
                    COALESCE(AVG(fr.rating), 0) as avg_rating
                    FROM transactions t
                    LEFT JOIN freelancer_reviews fr ON t.id = fr.transaction_id
                    WHERE t.freelancer_id = ?";

                $stmt = $conn->prepare($stats_query);
                $stmt->bind_param("i", $freelancer_id);
                $stmt->execute();
                $stats = $stmt->get_result()->fetch_assoc();
                ?>

                <!-- Overview Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3 class="text-primary">$<?= number_format($stats['total_earnings'], 2) ?></h3>
                                <p class="text-muted mb-0">Total Earnings</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3><?= $stats['completed_orders'] ?></h3>
                                <p class="text-muted mb-0">Completed Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3><?= $stats['active_orders'] ?></h3>
                                <p class="text-muted mb-0">Active Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3><?= number_format($stats['avg_rating'], 1) ?> ⭐</h3>
                                <p class="text-muted mb-0">Average Rating</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Offers/Gigs -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Active Offers</h5>
                        <a href="add-offer.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Create New Offer
                        </a>
                    </div>
                    <div class="card-body">
                        <?php
                        // Improved query to include thumbnail
                        $offers_query = "SELECT s.*, 
                            s.thumbnail as image,
                            c.name as category_name,
                            COUNT(DISTINCT t.id) as order_count,
                            COALESCE(AVG(r.rating), 0) as avg_rating
                            FROM offers s
                            LEFT JOIN categories c ON s.category_id = c.id 
                            LEFT JOIN transactions t ON s.id = t.offer_id
                            LEFT JOIN reviews r ON t.id = r.transaction_id
                            WHERE s.user_id = ?
                            GROUP BY s.id
                            ORDER BY order_count DESC";

                        $stmt = $conn->prepare($offers_query);
                        $stmt->bind_param("i", $freelancer_id);
                        $stmt->execute();
                        $offers = $stmt->get_result();
                        ?>

                        <?php if ($offers && $offers->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Offers</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Orders</th>
                                            <th>Rating</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($offer = $offers->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="../<?= htmlspecialchars($offer['image'] ?? 'default-offer.jpg') ?>" 
                                                             class="rounded me-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                        <div><?= htmlspecialchars($offer['title']) ?></div>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($offer['category_name']) ?></td>
                                                <td>Rp <?= number_format($offer['price'], 0, ',', '.') ?></td>
                                                <td><?= $offer['order_count'] ?></td>
                                                <td><?= $offer['avg_rating'] > 0 ? number_format($offer['avg_rating'], 1) . ' ⭐' : 'No ratings' ?></td>
                                                <td>
                                                    <a href="edit-offer.php?id=<?= $offer['id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary">Edit</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No offers found.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Active Orders -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Active Orders</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        // Update query to include correct status filters and join with offers
                        $orders_query = "SELECT t.*, o.title as service_title, o.price,
                            u.full_name as client_name, u.profile_photo as client_photo
                            FROM transactions t
                            JOIN offers o ON t.offer_id = o.id
                            JOIN users u ON t.client_id = u.id
                            WHERE t.freelancer_id = ? 
                            AND t.status IN ('pending', 'accepted', 'payment_pending', 'payment_accepted')
                            ORDER BY t.created_at DESC";

                        $stmt = $conn->prepare($orders_query);
                        $stmt->bind_param("i", $freelancer_id);
                        $stmt->execute();
                        $active_orders = $stmt->get_result();
                        ?>

                        <?php if ($active_orders->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Service</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($order = $active_orders->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= htmlspecialchars($order['client_photo']) ?>"
                                                            class="rounded-circle me-2"
                                                            style="width: 32px; height: 32px; object-fit: cover;">
                                                        <div><?= htmlspecialchars($order['client_name']) ?></div>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($order['service_title']) ?></td>
                                                <td>Rp <?= number_format($order['price'], 0, ',', '.') ?></td>
                                                <td>
                                                    <span class="badge bg-<?= match($order['status']) {
                                                        'pending' => 'warning',
                                                        'accepted' => 'info',
                                                        'payment_pending' => 'primary',
                                                        'payment_accepted' => 'success',
                                                        default => 'secondary'
                                                    } ?>">
                                                        <?= ucwords(str_replace('_', ' ', $order['status'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="payment.php?id=<?= $order['id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted m-0">No active orders at the moment.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Reviews</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $reviews_query = "SELECT fr.*, t.offer_id, s.title as service_title,
                            u.full_name as client_name, u.profile_photo as client_photo
                            FROM freelancer_reviews fr
                            JOIN transactions t ON fr.transaction_id = t.id
                            JOIN offers s ON t.offer_id = s.id
                            JOIN users u ON fr.client_id = u.id
                            WHERE fr.freelancer_id = ?
                            ORDER BY fr.created_at DESC
                            LIMIT 5";

                        $stmt = $conn->prepare($reviews_query);
                        $stmt->bind_param("i", $freelancer_id);
                        $stmt->execute();
                        $reviews = $stmt->get_result();
                        ?>

                        <?php if ($reviews->num_rows > 0): ?>
                            <?php while ($review = $reviews->fetch_assoc()): ?>
                                <div class="border-bottom mb-3 pb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="<?= htmlspecialchars($review['client_photo']) ?>" class="rounded-circle me-2"
                                            style="width: 32px; height: 32px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0"><?= htmlspecialchars($review['client_name']) ?></h6>
                                            <small class="text-muted"><?= htmlspecialchars($review['service_title']) ?></small>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="text-warning"><?= str_repeat('⭐', $review['rating']) ?></span>
                                        </div>
                                    </div>
                                    <p class="mb-0"><?= htmlspecialchars($review['review_text']) ?></p>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted m-0">No reviews yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="height: 11.5vh"></div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.js"></script>

</body>

</html>