<?php
include '../includes/header.php';

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Base query for counting total records
$count_query = "SELECT COUNT(*) as total FROM transactions t WHERE t.client_id = ?";

// Base query for fetching orders
$base_query = "SELECT 
    t.*, 
    s.title as service_title, 
    s.price,
    s.duration as delivery_time,
    u.username as freelancer_name,
    u.profile_photo as freelancer_photo,
    COALESCE(r.rating, 0) as rating,
    r.review_text
FROM transactions t
JOIN offers s ON t.offer_id = s.id
JOIN users u ON t.freelancer_id = u.id
LEFT JOIN reviews r ON t.id = r.transaction_id
WHERE t.client_id = ?";

// Add status filter if specified
if ($status_filter !== 'all') {
    $count_query .= " AND t.status = ?";
    $base_query .= " AND t.status = ?";
}

// Add sorting
switch ($sort_by) {
    case 'oldest':
        $base_query .= " ORDER BY t.created_at ASC";
        break;
    case 'price_high':
        $base_query .= " ORDER BY t.total_price DESC";
        break;
    case 'price_low':
        $base_query .= " ORDER BY t.total_price ASC";
        break;
    default: // newest
        $base_query .= " ORDER BY t.created_at DESC";
}

$base_query .= " LIMIT ? OFFSET ?";

// Prepare and execute count query
$stmt = $conn->prepare($count_query);
if ($status_filter !== 'all') {
    $stmt->bind_param("is", $_SESSION['user_id'], $status_filter);
} else {
    $stmt->bind_param("i", $_SESSION['user_id']);
}
$stmt->execute();
$total_records = $stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $per_page);

// Prepare and execute main query
$stmt = $conn->prepare($base_query);
if ($status_filter !== 'all') {
    $stmt->bind_param("isii", $_SESSION['user_id'], $status_filter, $per_page, $offset);
} else {
    $stmt->bind_param("iii", $_SESSION['user_id'], $per_page, $offset);
}
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - DesainHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="container mt-4" style="height: 80%">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Pesanan Saya</h2>
            <!-- <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Orders</li>
                </ol>
            </nav> -->
        </div>

        <!-- Filters and Sorting -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>Semua Pesanan</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Menunggu Konfirmasi</option>
                            <option value="accepted" <?php echo $status_filter === 'accepted' ? 'selected' : ''; ?>>Diterima</option>
                            <option value="payment_pending" <?php echo $status_filter === 'payment_pending' ? 'selected' : ''; ?>>Menunggu Verifikasi Pembayaran</option>
                            <option value="payment_accepted" <?php echo $status_filter === 'payment_accepted' ? 'selected' : ''; ?>>Pembayaran Diterima</option>
                            <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Selesai</option>
                            <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
                            <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="newest" <?php echo $sort_by === 'newest' ? 'selected' : ''; ?>>Newest First
                            </option>
                            <option value="oldest" <?php echo $sort_by === 'oldest' ? 'selected' : ''; ?>>Oldest First
                            </option>
                            <option value="price_high" <?php echo $sort_by === 'price_high' ? 'selected' : ''; ?>>Price:
                                High to Low</option>
                            <option value="price_low" <?php echo $sort_by === 'price_low' ? 'selected' : ''; ?>>Price: Low
                                to High</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders List -->
        <?php if ($orders->num_rows > 0): ?>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row">
                            <!-- Service Info -->
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <img src="<?php echo htmlspecialchars($order['freelancer_photo'] ?? 'assets/img/default-avatar.png'); ?>"
                                        class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <h5 class="mb-1"><?php echo htmlspecialchars($order['service_title']); ?></h5>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-person"></i>
                                            <?php echo htmlspecialchars($order['freelancer_name']); ?>
                                        </p>
                                        <p class="mb-2">
                                            <span class="badge bg-<?php
                                            echo match ($order['status']) {
                                                'pending' => 'warning',
                                                'accepted' => 'info',
                                                'payment_pending' => 'primary',
                                                'payment_accepted' => 'success',
                                                'payment_rejected' => 'danger',
                                                'completed' => 'success',
                                                'rejected' => 'danger',
                                                'cancelled' => 'secondary'
                                            };
                                            ?>">
                                                <?php echo match($order['status']) {
                                                    'pending' => 'Menunggu Konfirmasi',
                                                    'accepted' => 'Diterima - Menunggu Pembayaran',
                                                    'payment_pending' => 'Menunggu Verifikasi Pembayaran',
                                                    'payment_accepted' => 'Pembayaran Diterima',
                                                    'payment_rejected' => 'Pembayaran Ditolak',
                                                    'completed' => 'Selesai',
                                                    'rejected' => 'Ditolak',
                                                    'cancelled' => 'Dibatalkan'
                                                }; ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="col-md-4 text-md-end">
                                <h5 class="mb-1">$<?php echo number_format($order['total_price'], 2); ?></h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-clock"></i>
                                    Delivery: <?php echo $order['delivery_time']; ?> days
                                </p>
                                <p class="small text-muted mb-0">
                                    Ordered: <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                </p>
                            </div>
                        </div>

                        <?php if ($order['status'] === 'completed' && $order['rating'] > 0): ?>
                            <!-- Review Section -->
                            <div class="border-top mt-3 pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i
                                                class="bi bi-star-fill <?php echo $i <= $order['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                        <?php endfor; ?>
                                        <?php if ($order['review_text']): ?>
                                            <p class="mt-2 mb-0"><?php echo htmlspecialchars($order['review_text']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="border-top mt-3 pt-3">
                            <div class="d-flex justify-content-end gap-2">
                                <?php if ($order['status'] === 'pending'): ?>
                                    <a href="payment.php?id=<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        Lihat Detail
                                    </a>
                                <?php elseif ($order['status'] === 'accepted'): ?>
                                    <a href="payment-upload.php?id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">
                                        Upload Pembayaran
                                    </a>
                                <?php elseif ($order['status'] === 'payment_pending'): ?>
                                    <a href="payment-verification.php?id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">
                                        Verifikasi Pembayaran
                                    </a>
                                <?php elseif ($order['status'] === 'payment_accepted' || $order['status'] === 'completed'): ?>
                                    <a href="payment.php?id=<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        Lihat Detail
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Orders pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?page=<?php echo $page - 1; ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort_by; ?>">
                                Previous
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort_by; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?page=<?php echo $page + 1; ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort_by; ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <img src="../assets/images/empty-orders.png" alt="No Orders" style="width: 200px; margin-bottom: 20px;">
                <h3>No Orders Found</h3>
                <p class="text-muted">Start exploring services and place your first order!</p>
                <a href="browse-services.php" class="btn btn-primary">Browse Services</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>