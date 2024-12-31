<?php
include '../includes/header.php';

// Ambil kata kunci & tipe pencarian
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : 'services';

// Query mencari Layanan (Offers)
$offer_query = "SELECT o.*, u.full_name as freelancer_name, u.profile_photo, c.name as category_name,
                COALESCE(AVG(r.rating), 0) as avg_rating,
                COUNT(DISTINCT t.id) as total_orders
                FROM offers o
                JOIN users u ON o.user_id = u.id
                JOIN categories c ON o.category_id = c.id
                LEFT JOIN transactions t ON o.id = t.offer_id
                LEFT JOIN reviews r ON t.id = r.transaction_id
                WHERE (o.title LIKE CONCAT('%', ?, '%')
                   OR o.description LIKE CONCAT('%', ?, '%'))
                GROUP BY o.id
                ORDER BY o.created_at DESC";

// Query mencari Freelancer (Users)
$user_query = "SELECT u.*, 
               (SELECT COUNT(o.id) FROM offers o WHERE o.user_id = u.id) AS total_offers
               FROM users u
               WHERE (u.full_name LIKE CONCAT('%', ?, '%')
                  OR u.description LIKE CONCAT('%', ?, '%'))
               ORDER BY u.created_at DESC";

// Siapkan statement sesuai jenis pencarian
if ($search_type === 'users') {
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param('ss', $keyword, $keyword);
    $stmt->execute();
    $user_results = $stmt->get_result();
    $offer_results = null;
} elseif ($search_type === 'all') {
    // Cari keduanya
    $stmt1 = $conn->prepare($offer_query);
    $stmt1->bind_param('ss', $keyword, $keyword);
    $stmt1->execute();
    $offer_results = $stmt1->get_result();

    $stmt2 = $conn->prepare($user_query);
    $stmt2->bind_param('ss', $keyword, $keyword);
    $stmt2->execute();
    $user_results = $stmt2->get_result();
} else {
    // Default: Cari Layanan (services)
    $stmt = $conn->prepare($offer_query);
    $stmt->bind_param('ss', $keyword, $keyword);
    $stmt->execute();
    $offer_results = $stmt->get_result();
    $user_results = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hasil Pencarian | DesainHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="bg-dark ">
        <div class="container d-flex justify-content-center">
            <header class="text-white text-center py-4">
                <!-- Form Filter & Pencarian Ulang -->
                <form action="" method="get" class="row g-2 mb-3">
                    <div class="col-auto">
                        <input type="text" name="q" class="form-control" placeholder="Cari layanan atau freelancer..."
                            value="<?php echo htmlspecialchars($keyword); ?>">
                    </div>
                    <div class="col-auto">
                        <select name="search_type" class="form-select">
                            <option value="services" <?php if ($search_type === 'services')
                                echo 'selected'; ?>>Layanan
                            </option>
                            <option value="users" <?php if ($search_type === 'users')
                                echo 'selected'; ?>>Freelancer
                            </option>
                            <option value="all" <?php if ($search_type === 'all')
                                echo 'selected'; ?>>Semua</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>
                <h2>Hasil Pencarian: "<?php echo htmlspecialchars($keyword); ?>"</h2>
            </header>
        </div>
    </div>
    <div class="container my-4">

        <!-- Hasil Layanan -->
        <?php if ($offer_results): ?>
            <h4 class="mt-3 mb-3">Layanan</h4>
            <div class="row">
                <?php if ($offer_results->num_rows > 0): ?>
                    <?php while ($offer = $offer_results->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                            <a href="offers-detail.php?offer_id=<?php echo $offer['id']; ?>" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow offer-card">
                                    <img src="../<?php echo htmlspecialchars($offer['thumbnail']); ?>" class="card-img-top"
                                        alt="<?php echo htmlspecialchars($offer['title']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title text-dark"><?php echo htmlspecialchars($offer['title']); ?></h5>
                                        <p class="card-text text-muted">
                                            <?php echo htmlspecialchars(substr($offer['description'], 0, 60)) . '...'; ?>
                                        </p>
                                        <div class="mb-2">
                                            <span class="text-warning">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i
                                                        class="bi bi-star<?php echo $i <= round($offer['avg_rating']) ? '-fill' : ''; ?>"></i>
                                                <?php endfor; ?>
                                            </span>
                                            <small class="text-muted">
                                                (<?php echo $offer['total_orders']; ?> orders)
                                            </small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="user-info">
                                                <img src="<?php echo htmlspecialchars($offer['profile_photo']); ?>"
                                                    class="rounded-circle" width="30" height="30">
                                                <small class="ms-2 text-dark">
                                                    <?php echo htmlspecialchars($offer['freelancer_name']); ?>
                                                </small>
                                            </div>
                                            <strong class="text-primary">
                                                Rp <?php echo number_format($offer['price'], 0, ',', '.'); ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">Tidak ada layanan yang cocok.</div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Hasil Freelancer -->
        <?php if ($user_results): ?>
            <hr>
            <h4 class="mt-3 mb-3">Freelancer</h4>
            <div class="row">
                <?php if ($user_results->num_rows > 0): ?>
                    <?php while ($user = $user_results->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="d-flex justify-content-center mt-3">
                                    <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>"
                                        alt="<?php echo htmlspecialchars($user['full_name']); ?>" class="rounded-circle"
                                        style="width:80px;height:80px;object-fit:cover;">
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                                    <p class="card-text text-muted">
                                        <?php echo htmlspecialchars(substr($user['description'], 0, 60)) . '...'; ?>
                                    </p>
                                    <p class="card-text">
                                        Total Layanan: <strong><?php echo $user['total_offers']; ?></strong>
                                    </p>
                                    <a href="../pages/public-profile.php?user_id=<?php echo $user['id']; ?>"
                                        class="btn btn-outline-success">Lihat Profil</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">Tidak ada freelancer yang cocok.</div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="container" style="height: 45vh"></div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>