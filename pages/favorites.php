<?php
include '../includes/header.php';

// Get filter and sort parameters
$category_filter = isset($_GET['category']) ? (int) $_GET['category'] : 0;
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Base query for counting total records
$count_query = "SELECT COUNT(*) as total FROM favorites f 
                JOIN offers s ON f.offer_id = s.id 
                WHERE f.user_id = ?";

// Base query for fetching favorites
$base_query = "SELECT 
    f.id as favorite_id,
    f.created_at as favorited_at,
    s.*,
    u.username as freelancer_name,
    u.profile_photo as freelancer_photo,
    c.name as category_name,
    COALESCE(AVG(r.rating), 0) as avg_rating,
    COUNT(DISTINCT r.id) as review_count
FROM favorites f
JOIN offers s ON f.offer_id = s.id
JOIN users u ON s.user_id = u.id
JOIN categories c ON s.category_id = c.id
LEFT JOIN reviews r ON s.id = r.transaction_id
WHERE f.user_id = ?";

// Add category filter if specified
if ($category_filter > 0) {
    $count_query .= " AND s.category_id = ?";
    $base_query .= " AND s.category_id = ?";
}

$base_query .= " GROUP BY f.id";

// Add sorting
switch ($sort_by) {
    case 'price_high':
        $base_query .= " ORDER BY s.price DESC";
        break;
    case 'price_low':
        $base_query .= " ORDER BY s.price ASC";
        break;
    case 'rating':
        $base_query .= " ORDER BY avg_rating DESC";
        break;
    case 'oldest':
        $base_query .= " ORDER BY f.created_at ASC";
        break;
    default: // newest
        $base_query .= " ORDER BY f.created_at DESC";
}

$base_query .= " LIMIT ? OFFSET ?";

// Prepare and execute count query
$stmt = $conn->prepare($count_query);
if ($category_filter > 0) {
    $stmt->bind_param("ii", $_SESSION['user_id'], $category_filter);
} else {
    $stmt->bind_param("i", $_SESSION['user_id']);
}
$stmt->execute();
$total_records = $stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $per_page);

// Fetch categories for filter dropdown
$categories_query = "SELECT DISTINCT c.* FROM categories c 
                    JOIN offers s ON c.id = s.category_id 
                    JOIN favorites f ON s.id = f.offer_id 
                    WHERE f.user_id = ?";
$stmt = $conn->prepare($categories_query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$categories = $stmt->get_result();

// Prepare and execute main query
$stmt = $conn->prepare($base_query);
if ($category_filter > 0) {
    $stmt->bind_param("iiii", $_SESSION['user_id'], $category_filter, $per_page, $offset);
} else {
    $stmt->bind_param("iii", $_SESSION['user_id'], $per_page, $offset);
}
$stmt->execute();
$favorites = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Services - DesainHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container mt-4" style="height: 80%">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Favorite Services</h2>
            <!-- <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Favorites</li>
                </ol>
            </nav> -->
        </div>

        <!-- Filters and Sorting -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="0">All Categories</option>
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $category_filter === $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="newest" <?php echo $sort_by === 'newest' ? 'selected' : ''; ?>>Recently Added
                            </option>
                            <option value="oldest" <?php echo $sort_by === 'oldest' ? 'selected' : ''; ?>>Oldest First
                            </option>
                            <option value="price_high" <?php echo $sort_by === 'price_high' ? 'selected' : ''; ?>>Price:
                                High to Low</option>
                            <option value="price_low" <?php echo $sort_by === 'price_low' ? 'selected' : ''; ?>>Price: Low
                                to High</option>
                            <option value="rating" <?php echo $sort_by === 'rating' ? 'selected' : ''; ?>>Highest Rated
                            </option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Favorites Grid -->
        <?php if ($favorites->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php while ($favorite = $favorites->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <!-- Service Image -->
                            <img src="<?php echo htmlspecialchars($favorite['image'] ?? 'assets/img/default-service.png'); ?>"
                                class="card-img-top" alt="Service Image" style="height: 200px; object-fit: cover;">

                            <!-- Favorite Button -->
                            <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle p-2 favorite-btn"
                                data-service-id="<?php echo $favorite['offer_id']; ?>">
                                <i class="bi bi-heart-fill text-danger"></i>
                            </button>

                            <div class="card-body">
                                <!-- Service Title and Category -->
                                <h5 class="card-title mb-1">
                                    <a href="service-details.php?id=<?php echo $favorite['id']; ?>"
                                        class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($favorite['title']); ?>
                                    </a>
                                </h5>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-tag"></i>
                                    <?php echo htmlspecialchars($favorite['category_name']); ?>
                                </p>

                                <!-- Freelancer Info -->
                                <div class="d-flex align-items-center mb-3">
                                    <img src="<?php echo htmlspecialchars($favorite['freelancer_photo'] ?? 'assets/img/default-avatar.png'); ?>"
                                        class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                    <span class="text-muted small">
                                        <?php echo htmlspecialchars($favorite['freelancer_name']); ?>
                                    </span>
                                </div>

                                <!-- Rating -->
                                <div class="mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i
                                            class="bi bi-star-fill <?php echo $i <= $favorite['avg_rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                    <?php endfor; ?>
                                    <span class="text-muted small ms-1">
                                        (<?php echo $favorite['review_count']; ?> reviews)
                                    </span>
                                </div>

                                <!-- Price and Action -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">$<?php echo number_format($favorite['price'], 2); ?></h5>
                                    <a href="service-details.php?id=<?php echo $favorite['id']; ?>"
                                        class="btn btn-primary btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Favorites pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?page=<?php echo $page - 1; ?>&category=<?php echo $category_filter; ?>&sort=<?php echo $sort_by; ?>">
                                Previous
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="?page=<?php echo $i; ?>&category=<?php echo $category_filter; ?>&sort=<?php echo $sort_by; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?page=<?php echo $page + 1; ?>&category=<?php echo $category_filter; ?>&sort=<?php echo $sort_by; ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <img src="../assets/images/broken-heart.svg" alt="No Favorites" style="width: 200px; margin-bottom: 20px;">
                <h3>No Favorite Services Yet</h3>
                <p class="text-muted">Start exploring and save services you're interested in!</p>
                <a href="browse-services.php" class="btn btn-primary">Browse Services</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Favorite Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const favoriteButtons = document.querySelectorAll('.favorite-btn');

            favoriteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const serviceId = this.dataset.serviceId;

                    fetch('toggle-favorite.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            service_id: serviceId // service_id is the offer_id
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the card from the grid
                                this.closest('.col').remove();

                                // If no more favorites, show empty state
                                const remainingCards = document.querySelectorAll('.col');
                                if (remainingCards.length === 0) {
                                    location.reload();
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });
    </script>
</body>

</html>