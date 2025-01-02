<?php
ob_start(); // Add this at the very beginning
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: masuk.php');
    exit;
}

$offer_id = isset($_GET['offer_id']) ? (int)$_GET['offer_id'] : 0;

// Move all database operations and form processing here, before any HTML output
$query = "SELECT o.*, u.full_name as freelancer_name, u.id as freelancer_id 
          FROM offers o 
          JOIN users u ON o.user_id = u.id 
          WHERE o.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $offer_id);
$stmt->execute();
$offer = $stmt->get_result()->fetch_assoc();

if (!$offer) {
    header('Location: kategori.php');
    exit;
}

// Handle order submission
if (isset($_POST['place_order'])) {
    $client_id = $_SESSION['user_id'];
    $freelancer_id = $offer['freelancer_id'];
    $total_price = $offer['price'];
    
    $stmt = $conn->prepare("INSERT INTO transactions (offer_id, client_id, freelancer_id, total_price, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iiid", $offer_id, $client_id, $freelancer_id, $total_price);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Order berhasil dibuat!";
        header("Location: orders.php");
        exit;
    } else {
        $error = "Gagal membuat order.";
    }
}

// Handle freelancer actions
if (isset($_POST['action']) && $_SESSION['user_id'] == $offer['freelancer_id']) {
    $transaction_id = $_POST['transaction_id'];
    $action = $_POST['action'];
    $new_status = ($action === 'accept') ? 'payment_pending' : 'rejected';
    
    $stmt = $conn->prepare("UPDATE transactions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $transaction_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Order telah " . ($action === 'accept' ? 'diterima' : 'ditolak');
        header("Location: orders.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - DesainHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container my-4">
        <div class="row">
            <div class="col-md-8">
                <h2>Detail Pesanan</h2>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($offer['title']) ?></h5>
                        <p class="text-muted">Penyedia Jasa: <?= htmlspecialchars($offer['freelancer_name']) ?></p>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Durasi Pengerjaan:</strong> <?= $offer['duration'] ?> hari</p>
                                <p><strong>Revisi:</strong> <?= $offer['revisions'] ?> kali</p>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-primary">Rp <?= number_format($offer['price'], 0, ',', '.') ?></h4>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h6>Deskripsi Layanan</h6>
                            <p><?= nl2br(htmlspecialchars($offer['description'])) ?></p>
                        </div>
                    </div>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <!-- Order Form -->
                <form method="POST" class="card shadow-sm">
                    <div class="card-body">
                        <h5>Konfirmasi Pesanan</h5>
                        <p class="text-muted">Pastikan detail pesanan sudah sesuai sebelum melanjutkan.</p>
                        <div class="d-grid">
                            <button type="submit" name="place_order" class="btn btn-primary">
                                Buat Pesanan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
