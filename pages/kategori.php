<?php
include '../includes/header.php';

// Get category ID from URL if provided
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$current_category = null;

// Fetch current category details if ID provided
if ($category_id) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_category = $result->fetch_assoc();
}

// Fetch offers based on category
$query = "SELECT o.*, c.name as category_name, u.full_name as freelancer_name, u.profile_photo,
          COUNT(DISTINCT t.id) as total_orders,
          COALESCE(AVG(r.rating), 0) as avg_rating
          FROM offers o
          JOIN users u ON o.user_id = u.id
          JOIN categories c ON o.category_id = c.id
          LEFT JOIN transactions t ON o.id = t.offer_id
          LEFT JOIN reviews r ON t.id = r.transaction_id";

if ($category_id) {
    $query .= " WHERE o.category_id = ?";
}

$query .= " GROUP BY o.id ORDER BY o.created_at DESC";

$stmt = $conn->prepare($query);
if ($category_id) {
    $stmt->bind_param("i", $category_id);
}
$stmt->execute();
$offers = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $current_category ? htmlspecialchars($current_category['name']) : 'All Categories'; ?> | DesainHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <!-- Header -->
    <header class="bg-dark text-white text-center py-4">
        <h1><?php echo $current_category ? htmlspecialchars($current_category['name']) : 'All Categories'; ?></h1>
        <p class="lead"><?php echo $current_category ? htmlspecialchars($current_category['description']) : 'Find the best services for your needs'; ?></p>
    </header>

    <!-- Category Navigation -->
    <div class="bg-light py-3">
        <div class="container">
            <div class="d-flex flex-nowrap overflow-auto">
                <a href="kategori.php" class="btn <?php echo !$category_id ? 'btn-primary' : 'btn-outline-primary'; ?> me-2">All</a>
                <?php
                // Fetch all categories
                $cat_stmt = $conn->prepare("SELECT * FROM categories");
                $cat_stmt->execute();
                $categories = $cat_stmt->get_result();
                
                while ($cat = $categories->fetch_assoc()): ?>
                    <a href="?id=<?php echo $cat['id']; ?>" 
                       class="btn <?php echo $category_id == $cat['id'] ? 'btn-primary' : 'btn-outline-primary'; ?> me-2">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Service Cards -->
    <div class="container my-4">
        <div class="row">
            <?php if ($offers->num_rows > 0): ?>
                <?php while ($offer = $offers->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card h-100 border-0 shadow">
                            <img src="../<?php echo htmlspecialchars($offer['thumbnail']); ?>" 
                                 class="card-img-top card-img-bottom" alt="<?php echo htmlspecialchars($offer['title']); ?>" 
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($offer['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($offer['description'], 0, 100)) . '...'; ?></p>
                                
                                <!-- Rating -->
                                <div class="mb-2">
                                    <span class="text-warning">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo $i <= round($offer['avg_rating']) ? '-fill' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                    <small class="text-muted">(<?php echo $offer['total_orders']; ?> orders)</small>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="user-info">
                                        <img src="<?php echo htmlspecialchars($offer['profile_photo']); ?>" 
                                             class="rounded-circle" width="30" height="30">
                                        <small class="ms-2"><?php echo htmlspecialchars($offer['freelancer_name']); ?></small>
                                    </div>
                                    <strong class="text-primary">Rp <?php echo number_format($offer['price'], 0, ',', '.'); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        No offers found in this category.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>