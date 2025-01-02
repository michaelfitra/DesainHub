<?php
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: masuk.php');
    exit;
}

$transaction_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch transaction details
$query = "SELECT t.*, o.title, o.price, 
          c.full_name as client_name, c.id as client_id,
          f.full_name as freelancer_name, f.id as freelancer_id
          FROM transactions t
          JOIN offers o ON t.offer_id = o.id
          JOIN users c ON t.client_id = c.id
          JOIN users f ON t.freelancer_id = f.id
          WHERE t.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$transaction = $stmt->get_result()->fetch_assoc();

if (!$transaction || ($_SESSION['user_id'] != $transaction['client_id'] && $_SESSION['user_id'] != $transaction['freelancer_id'])) {
    header('Location: orders.php');
    exit;
}

$is_client = $_SESSION['user_id'] == $transaction['client_id'];

// Handle payment proof upload
if (isset($_POST['upload_payment']) && $is_client) {
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
        $error = "Pilih file bukti pembayaran.";
    } else {
        $file = $_FILES['payment_proof'];
        
        if (!in_array($file['type'], $allowed_types)) {
            $error = "Format file harus JPG atau PNG.";
        } elseif ($file['size'] > $max_size) {
            $error = "Ukuran file maksimal 5MB.";
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $target = "../assets/images/payments/" . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $stmt = $conn->prepare("UPDATE transactions SET payment_proof = ?, payment_date = NOW(), status = 'payment_pending' WHERE id = ?");
                $filepath = "assets/images/payments/" . $filename;
                $stmt->bind_param("si", $filepath, $transaction_id);
                
                if ($stmt->execute()) {
                    header("Location: payment.php?id=" . $transaction_id);
                    exit;
                }
            }
            $error = "Gagal mengunggah file.";
        }
    }
}

// Handle freelancer actions
if (isset($_POST['action']) && !$is_client) {
    $action = $_POST['action'];
    $new_status = ($action === 'accept_payment') ? 'payment_accepted' : 'payment_rejected';
    
    $stmt = $conn->prepare("UPDATE transactions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $transaction_id);
    
    if ($stmt->execute()) {
        header("Location: payment.php?id=" . $transaction_id);
        exit;
    }
}

// Handle result submission
if (isset($_POST['submit_result']) && !$is_client) {
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'application/zip'];
    $max_size = 25 * 1024 * 1024; // 25MB
    
    if (!isset($_FILES['result_files']) || $_FILES['result_files']['error'] !== UPLOAD_ERR_OK) {
        $error = "Pilih file hasil pekerjaan.";
    } else {
        $file = $_FILES['result_files'];
        
        if (!in_array($file['type'], $allowed_types)) {
            $error = "Format file harus JPG, PNG, PDF, atau ZIP.";
        } elseif ($file['size'] > $max_size) {
            $error = "Ukuran file maksimal 25MB.";
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $target = "../assets/files/results/" . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $stmt = $conn->prepare("UPDATE transactions SET result_files = ?, result_date = NOW(), status = 'completed' WHERE id = ?");
                $filepath = "assets/files/results/" . $filename;
                $stmt->bind_param("si", $filepath, $transaction_id);
                
                if ($stmt->execute()) {
                    header("Location: payment.php?id=" . $transaction_id);
                    exit;
                }
            }
            $error = "Gagal mengunggah file hasil.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - DesainHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-0">Detail Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <!-- Order Information -->
                        <div class="mb-4">
                            <h5><?= htmlspecialchars($transaction['title']) ?></h5>
                            <p class="text-muted mb-2">
                                <?= $is_client ? "Freelancer: " . htmlspecialchars($transaction['freelancer_name']) 
                                             : "Client: " . htmlspecialchars($transaction['client_name']) ?>
                            </p>
                            <p class="h4 text-primary">Rp <?= number_format($transaction['total_price'], 0, ',', '.') ?></p>
                            <div class="badge bg-<?= match($transaction['status']) {
                                'pending' => 'warning',
                                'accepted' => 'info',
                                'payment_pending' => 'primary',
                                'payment_accepted' => 'success',
                                'payment_rejected' => 'danger',
                                'completed' => 'success',
                                default => 'secondary'
                            } ?>">
                                <?= ucwords(str_replace('_', ' ', $transaction['status'])) ?>
                            </div>
                        </div>

                        <!-- Payment Actions -->
                        <?php if ($transaction['status'] === 'payment_pending' && !$is_client): ?>
                            <!-- Freelancer: Accept/Reject Payment -->
                            <div class="d-flex gap-2">
                                <form method="POST" class="flex-grow-1">
                                    <input type="hidden" name="action" value="accept_payment">
                                    <button type="submit" class="btn btn-success w-100">Terima Pembayaran</button>
                                </form>
                                <form method="POST" class="flex-grow-1">
                                    <input type="hidden" name="action" value="reject_payment">
                                    <button type="submit" class="btn btn-danger w-100">Tolak Pembayaran</button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <?php if ($transaction['status'] === 'accepted' && $is_client): ?>
                            <!-- Client: Upload Payment -->
                            <form method="POST" enctype="multipart/form-data" class="border rounded p-3">
                                <h5 class="mb-3">Upload Bukti Pembayaran</h5>
                                <div class="mb-3">
                                    <label class="form-label">File Bukti Pembayaran (JPG/PNG, max 5MB)</label>
                                    <input type="file" name="payment_proof" class="form-control" accept="image/jpeg,image/png" required>
                                </div>
                                <button type="submit" name="upload_payment" class="btn btn-primary">Upload Bukti Pembayaran</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($transaction['status'] === 'payment_accepted' && !$is_client): ?>
                            <!-- Freelancer: Submit Result -->
                            <form method="POST" enctype="multipart/form-data" class="border rounded p-3">
                                <h5 class="mb-3">Upload Hasil Pekerjaan</h5>
                                <div class="mb-3">
                                    <label class="form-label">File Hasil (JPG/PNG/PDF/ZIP, max 25MB)</label>
                                    <input type="file" name="result_files" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.zip" required>
                                </div>
                                <button type="submit" name="submit_result" class="btn btn-primary">Submit Hasil</button>
                            </form>
                        <?php endif; ?>

                        <!-- Display Payment Proof -->
                        <?php if ($transaction['payment_proof']): ?>
                            <div class="mt-4">
                                <h5>Bukti Pembayaran</h5>
                                <img src="../<?= htmlspecialchars($transaction['payment_proof']) ?>" 
                                     class="img-fluid rounded" 
                                     alt="Bukti Pembayaran"
                                     style="max-height: 300px;">
                            </div>
                        <?php endif; ?>

                        <!-- Display Result Files -->
                        <?php if ($transaction['result_files'] && ($is_client || !$is_client)): ?>
                            <div class="mt-4">
                                <h5>File Hasil</h5>
                                <a href="../<?= htmlspecialchars($transaction['result_files']) ?>" 
                                   class="btn btn-outline-primary" 
                                   target="_blank">
                                    <i class="bi bi-download"></i> Download Hasil
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
