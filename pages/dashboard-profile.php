<?php
include '../includes/header.php'; // session_start() dan config.php sudah ada dalam header
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Profile Dashboard</title>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar Section -->
            <?php
            // Fetch user data
            $user_query = "SELECT u.*,
                COUNT(DISTINCT t.id) as completed_projects,
                COALESCE(AVG(r.rating), 0) as avg_rating
                FROM users u
                LEFT JOIN transactions t ON u.id = t.client_id AND t.status = 'completed'
                LEFT JOIN reviews r ON t.id = r.transaction_id
                WHERE u.id = ?
                GROUP BY u.id";

            $stmt = $conn->prepare($user_query);
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $user_data = $stmt->get_result()->fetch_assoc();

            // Calculate join date
            $join_date = date('F Y', strtotime($user_data['created_at']));

            // Parse languages from JSON if stored as JSON string
            $languages = !empty($user_data['languages']) ? json_decode($user_data['languages'], true) : [];

            // Get user's preferred language display name
            $language_names = [
                'en' => 'English',
                'id' => 'Indonesian',
                // Add more language mappings as needed
            ];
            $preferred_language = $language_names[$user_data['language']] ?? $user_data['language'];
            ?>
            <div class="col-sm-12 col-md-4 col-xl-3">
                <div class="card text-center shadow-sm mb-3">
                    <div class="card-body">
                        <!-- Profile Image -->
                        <img src="<?php echo !empty($user_data['profile_photo']) ?
                            htmlspecialchars($user_data['profile_photo']) :
                            ASSETS_PATH_IMG . 'profile/default-avatar.png'; ?>" alt="Profile Image"
                            class="img-fluid rounded-circle mb-3"
                            style="width: 200px; height: 200px; object-fit: cover;">

                        <!-- User Name and Username -->
                        <h5 class="card-title"><?php echo htmlspecialchars($user_data['full_name']); ?></h5>
                        <p class="text-muted">@<?php echo htmlspecialchars($user_data['username']); ?></p>

                        <!-- User Stats and Info -->
                        <ul class="list-unstyled text-start px-4">

                            <!-- Description -->
                            <?php if (!empty($user_data['description'])): ?>
                                <li>
                                    <i class="bi bi-person-lines-fill me-1"></i>
                                    <small><?php echo nl2br(htmlspecialchars($user_data['description'])); ?></small>
                                </li>
                            <?php endif; ?>

                            <li>
                                <hr class="bg-danger border-2 border-top">
                            </li>

                            <?php if (!empty($user_data['location'])): ?>
                                <li>
                                    <i class="bi bi-geo-alt me-1"></i>
                                    <?php echo htmlspecialchars($user_data['location']); ?>
                                </li>
                            <?php endif; ?>

                            <li>
                                <i class="bi bi-calendar me-1"></i>
                                Joined in <?php echo $join_date; ?>
                            </li>

                            <li>
                                <hr class="bg-danger border-2 border-top">
                            </li>

                            <!-- Phone Number -->
                            <?php if (!empty($user_data['phone'])): ?>
                                <li>
                                    <i class="bi bi-telephone me-1"></i>
                                    <?php echo htmlspecialchars($user_data['phone']); ?>
                                </li>
                            <?php endif; ?>

                            <!-- Email -->
                            <li>
                                <i class="bi bi-envelope me-1"></i>
                                <?php echo htmlspecialchars($user_data['email']); ?>
                            </li>

                            <!-- Preferred Language -->
                            <li>
                                <i class="bi bi-globe me-1"></i>
                                <?php echo htmlspecialchars($preferred_language); ?>
                            </li>

                            <!-- Additional Languages -->
                            <?php if (!empty($languages)): ?>
                                <li>
                                    <i class="bi bi-translate me-1"></i>
                                    Speaks: <?php echo htmlspecialchars(implode(', ', $languages)); ?>
                                </li>
                            <?php endif; ?>

                        </ul>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="public-profile.php" class="btn btn-outline-primary btn-sm">
                                View Public Profile
                            </a>
                            <?php if ($user_data['is_freelancer'] == 0): ?>
                                <a href="become-freelancer.php" class="btn btn-primary btn-sm">
                                    Become a Freelancer
                                </a>
                            <?php else: ?>
                                <a href="freelancers-profile.php" class="btn btn-primary btn-sm">
                                    Switch to Freelancer Dashboard
                                </a>
                            <?php endif; ?>
                            <a href="pengaturan.php" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-gear"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="col-sm-12 col-md-8 col-xl-9">
                <?php
                // Get user ID from session
                $user_id = $_SESSION['user_id'];

                // Fetch user stats
                $stats_query = "SELECT 
                    COUNT(DISTINCT t.id) as total_projects,
                    SUM(t.total_price) as total_spent,
                    AVG(r.rating) as avg_rating
                    FROM users u
                    LEFT JOIN transactions t ON u.id = t.client_id
                    LEFT JOIN reviews r ON t.id = r.transaction_id
                    WHERE u.id = ?";

                $stmt = $conn->prepare($stats_query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stats_result = $stmt->get_result()->fetch_assoc();
                ?>

                <!-- Welcome Alert -->
                <div class="alert alert-primary alert-dismissible fade show shadow-sm" role="alert">
                    <strong>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</strong>
                    Check your ongoing projects and latest reviews.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <!-- Profile Overview -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title">Profile Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h4><?php echo number_format($stats_result['total_projects']); ?></h4>
                                <p class="text-muted">Completed Projects</p>
                            </div>
                            <div class="col-md-4">
                                <h4><?php echo number_format($stats_result['avg_rating'], 1); ?></h4>
                                <p class="text-muted">Average Rating</p>
                            </div>
                            <div class="col-md-4">
                                <h4>$<?php echo number_format($stats_result['total_spent'], 2); ?></h4>
                                <p class="text-muted">Total Spent</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Orders -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title">Active Orders</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $active_orders_query = "SELECT t.*, s.title as service_title, u.username as freelancer_name
                            FROM transactions t
                            JOIN services s ON t.service_id = s.id
                            JOIN users u ON t.freelancer_id = u.id
                            WHERE t.client_id = ? AND t.status = 'in_progress'
                            ORDER BY t.created_at DESC LIMIT 3";

                        $stmt = $conn->prepare($active_orders_query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $active_orders = $stmt->get_result();
                        ?>

                        <?php if ($active_orders->num_rows > 0): ?>
                            <div class="list-group">
                                <?php while ($order = $active_orders->fetch_assoc()): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($order['service_title']); ?></h6>
                                            <small class="text-primary">In Progress</small>
                                        </div>
                                        <p class="mb-1">Freelancer: <?php echo htmlspecialchars($order['freelancer_name']); ?>
                                        </p>
                                        <small class="text-muted">Started
                                            <?php echo date('M d, Y', strtotime($order['created_at'])); ?></small>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No active orders at the moment.</p>
                        <?php endif; ?>
                        <a href="orders.php" class="btn btn-outline-primary">View All Orders</a>
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title">Recent Reviews from Freelancers</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $reviews_query = "SELECT fr.*, u.username as freelancer_name, u.profile_photo
                            FROM freelancer_reviews fr
                            JOIN users u ON fr.freelancer_id = u.id
                            WHERE fr.client_id = ?
                            ORDER BY fr.created_at DESC LIMIT 3";

                        $stmt = $conn->prepare($reviews_query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $reviews = $stmt->get_result();
                        ?>

                        <?php if ($reviews->num_rows > 0): ?>
                            <?php while ($review = $reviews->fetch_assoc()): ?>
                                <div class="d-flex mb-3">
                                    <img src="<?php echo htmlspecialchars($review['profile_photo'] ?? 'assets/img/default-avatar.png'); ?>"
                                        class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($review['freelancer_name']); ?></h6>
                                        <div class="mb-1">
                                            <?php for ($i = 0; $i < 5; $i++): ?>
                                                <i
                                                    class="bi bi-star-fill <?php echo $i < $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="mb-1"><?php echo htmlspecialchars($review['review_text']); ?></p>
                                        <small
                                            class="text-muted"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted m-0">No reviews yet.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Favorite Services -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title">Favorite Services</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $favorites_query = "SELECT s.*, u.username as freelancer_name
                            FROM favorites f
                            JOIN services s ON f.service_id = s.id
                            JOIN users u ON s.user_id = u.id
                            WHERE f.user_id = ?
                            ORDER BY f.created_at DESC LIMIT 3";

                        $stmt = $conn->prepare($favorites_query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $favorites = $stmt->get_result();
                        ?>

                        <?php if ($favorites->num_rows > 0): ?>
                            <div class="row">
                                <?php while ($favorite = $favorites->fetch_assoc()): ?>
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <img src="<?php echo htmlspecialchars($favorite['image'] ?? 'assets/img/default-service.png'); ?>"
                                                class="card-img-top" alt="Service Image"
                                                style="height: 150px; object-fit: cover;">
                                            <div class="card-body">
                                                <h6 class="card-title"><?php echo htmlspecialchars($favorite['title']); ?></h6>
                                                <p class="card-text text-muted">By
                                                    <?php echo htmlspecialchars($favorite['freelancer_name']); ?>
                                                </p>
                                                <p class="card-text">
                                                    <strong>$<?php echo number_format($favorite['price'], 2); ?></strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No favorite services yet.</p>
                        <?php endif; ?>
                        <a href="favorites.php" class="btn btn-outline-primary">View All Favorites</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.js"></script>

</body>

</html>