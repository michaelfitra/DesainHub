<?php
ob_start();
include '../includes/header.php'; // start session dan koneksi database sudah ada pada header

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    ob_end_clean();
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadDir = '../assets/images/offers/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        die('Gagal membuat direktori upload');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate input
        $title = trim($_POST['title']);
        $category = trim($_POST['category_id']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $duration = intval($_POST['duration']);
        $revisions = intval($_POST['revisions']);

        // Validate required fields
        if (empty($title) || empty($category) || empty($description) || $price <= 0 || $duration <= 0) {
            throw new Exception('Semua field wajib diisi dengan benar');
        }

        // Process thumbnail
        if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Gambar sampul wajib diunggah');
        }

        // Validate thumbnail
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['thumbnail']['type'], $allowedTypes)) {
            throw new Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF');
        }

        if ($_FILES['thumbnail']['size'] > $maxSize) {
            throw new Exception('Ukuran file terlalu besar. Maksimal 5MB');
        }

        // Generate unique filename for thumbnail
        $thumbnailName = uniqid() . '_' . basename($_FILES['thumbnail']['name']);
        $thumbnailPath = $uploadDir . $thumbnailName;

        if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailPath)) {
            throw new Exception('Gagal mengunggah gambar sampul');
        }

        // Insert offer data
        $query = "INSERT INTO offers (user_id, category_id, title, description, price, duration, revisions, thumbnail) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception('Gagal mempersiapkan query: ' . $conn->error);
        }

        $thumbnailDb = 'assets/images/offers/' . $thumbnailName;
        $stmt->bind_param(
            "iissdiis",
            $_SESSION['user_id'],
            $_POST['category_id'], // Changed from category to category_id
            $title,
            $description,
            $price,
            $duration,
            $revisions,
            $thumbnailDb
        );

        if (!$stmt->execute()) {
            throw new Exception('Gagal menyimpan data penawaran: ' . $stmt->error);
        }

        $offerId = $conn->insert_id;

        // Process gallery images if any
        if (isset($_FILES['gallery']['name']) && is_array($_FILES['gallery']['name'])) {
            $totalGallery = count($_FILES['gallery']['name']);
            if ($totalGallery > 5) {
                throw new Exception('Maksimal 5 gambar tambahan');
            }

            $galleryStmt = $conn->prepare("INSERT INTO offer_gallery (offer_id, image_path) VALUES (?, ?)");
            if (!$galleryStmt) {
                throw new Exception('Gagal mempersiapkan query gallery: ' . $conn->error);
            }

            for ($i = 0; $i < $totalGallery; $i++) {
                if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                    if (!in_array($_FILES['gallery']['type'][$i], $allowedTypes)) {
                        continue;
                    }

                    if ($_FILES['gallery']['size'][$i] > $maxSize) {
                        continue;
                    }

                    $galleryName = uniqid() . '_' . basename($_FILES['gallery']['name'][$i]);
                    $galleryPath = $uploadDir . $galleryName;

                    if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $galleryPath)) {
                        $galleryImagePath = 'assets/images/offers/' . $galleryName;
                        $galleryStmt->bind_param("is", $offerId, $galleryImagePath);
                        if (!$galleryStmt->execute()) {
                            throw new Exception('Gagal menyimpan gambar gallery: ' . $galleryStmt->error);
                        }
                    }
                }
            }
            $galleryStmt->close();
        }

        $stmt->close();

        // Redirect dengan pesan sukses
        $_SESSION['swal_message'] = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Penawaran berhasil dibuat'
        ];

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;

    } catch (Exception $e) {
        $_SESSION['swal_message'] = [
            'icon' => 'error',
            'title' => 'Oops...',
            'text' => $e->getMessage()
        ];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penawaran Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f4f5f7;
            height: 100%;
        }

        .step-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            position: relative;
            color: #6c757d;
            font-weight: 500;
        }

        .step::before {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 4px;
            background-color: #e9ecef;
            transition: background-color 0.3s ease;
        }

        .step.active::before {
            background-color: #007bff;
        }

        .step.completed::before {
            background-color: #28a745;
        }

        .step-content {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            visibility: hidden;
            position: absolute;
            width: 100%;
        }

        .step-content.active {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
            position: relative;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        @media (max-width: 768px) {
            .step-indicator .step {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <?php
    // Check for SweetAlert message
    if (isset($_SESSION['swal_message'])){
        $message = $_SESSION['swal_message'];
        unset($_SESSION['swal_message']); // Clear the message
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '<?php echo $message['icon']; ?>',
                    title: '<?php echo addslashes($message['title']); ?>',
                    text: '<?php echo addslashes($message['text']); ?>',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    <?php if ($message['icon'] === 'success'): ?>
                        window.location.href = 'freelancers-profile.php';
                    <?php endif; ?>
                });
            });
        </script>
        <?php
    }
    ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="step-container">
                    <h2 class="text-center mb-4">Buat Penawaran Baru</h2>

                    <div class="step-indicator">
                        <div class="step active" data-step="1">
                            <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                        </div>
                        <div class="step" data-step="2">
                            <i class="bi bi-list-task me-2"></i>Rincian & Harga
                        </div>
                        <div class="step" data-step="3">
                            <i class="bi bi-image me-2"></i>Galeri & Pratinjau
                        </div>
                    </div>

                    <form id="offer-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"
                        enctype="multipart/form-data" class="position-relative">
                        <!-- Langkah 1: Informasi Dasar -->
                        <div id="step-1" class="step-content active">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Penawaran</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Deskripsikan layanan Anda secara singkat" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori Layanan</label>
                                <select class="form-select" id="category" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php
                                    $cat_query = "SELECT id, name FROM categories ORDER BY name";
                                    $cat_result = $conn->query($cat_query);
                                    while ($category = $cat_result->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Layanan</label>
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    placeholder="Jelaskan detail dan keunggulan layanan Anda" required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary" onclick="changeStep(2)">
                                    Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Langkah 2: Rincian & Harga -->
                        <div id="step-2" class="step-content">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Layanan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="price" name="price"
                                        placeholder="Tentukan harga layanan Anda" required min="0">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="duration" class="form-label">Durasi Pengerjaan</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="duration" name="duration"
                                            placeholder="Berapa hari" required min="1">
                                        <span class="input-group-text">hari</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="revisions" class="form-label">Revisi Gratis</label>
                                    <input type="number" class="form-control" id="revisions" name="revisions"
                                        placeholder="Jumlah revisi" required min="0">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="changeStep(1)">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </button>
                                <button type="button" class="btn btn-primary" onclick="changeStep(3)">
                                    Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Langkah 3: Galeri & Pratinjau -->
                        <div id="step-3" class="step-content">
                            <div class="mb-3">
                                <label for="thumbnail" class="form-label">Gambar Sampul</label>
                                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*"
                                    required>
                                <small class="form-text text-muted">Unggah gambar yang menarik dan representatif</small>
                            </div>
                            <div class="mb-3">
                                <label for="gallery" class="form-label">Galeri Tambahan (Opsional)</label>
                                <input type="file" class="form-control" id="gallery" name="gallery[]" accept="image/*"
                                    multiple>
                                <small class="form-text text-muted">Maksimal 5 gambar tambahan</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="changeStep(2)">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-2"></i>Simpan Penawaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function changeStep(targetStep) {
            // Validasi form sebelum pindah
            const currentStep = document.querySelector('.step-content.active');
            const inputs = currentStep.querySelectorAll('input:not([type="file"]), select, textarea');
            let isValid = true;

            inputs.forEach(input => {
                if (input.required && !input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Mohon lengkapi semua field yang wajib diisi'
                });
                return;
            }

            // Update step indicators
            const stepIndicators = document.querySelectorAll('.step');
            stepIndicators.forEach(indicator => {
                indicator.classList.remove('active', 'completed');
                if (parseInt(indicator.dataset.step) < targetStep) {
                    indicator.classList.add('completed');
                }
                if (parseInt(indicator.dataset.step) === targetStep) {
                    indicator.classList.add('active');
                }
            });

            // Update step content
            const stepContents = document.querySelectorAll('.step-content');
            stepContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === `step-${targetStep}`) {
                    content.classList.add('active');
                }
            });
        }

        // Validasi file
        const allowedTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        ];

        // Event listener untuk thumbnail
        document.getElementById('thumbnail').addEventListener('change', function (e) {
            if (this.files && this.files[0]) {
                validateFile(this.files[0], 5, allowedTypes);
            }
        });

        // Event listener untuk gallery
        document.getElementById('gallery').addEventListener('change', function (e) {
            if (this.files.length > 5) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Maksimal 5 gambar tambahan'
                });
                this.value = '';
                return;
            }

            Array.from(this.files).forEach(file => {
                if (!validateFile(file, 5, allowedTypes)) {
                    this.value = '';
                    return false;
                }
            });
        });

        // Fungsi validasi file
        function validateFile(file, maxSizeMB, allowedTypes) {
            const maxSize = maxSizeMB * 1024 * 1024; // Convert to bytes

            if (!allowedTypes.includes(file.type.toLowerCase())) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format file tidak didukung',
                    text: 'Gunakan format JPG, PNG, atau GIF'
                });
                return false;
            }

            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File terlalu besar',
                    text: `Maksimal ukuran file adalah ${maxSizeMB}MB`
                });
                return false;
            }

            return true;
        }

        // Form submission handling
        document.getElementById('offer-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            const submitButton = this.querySelector('button[type="submit"]');
            const thumbnail = document.getElementById('thumbnail');

            if (!thumbnail.files || !thumbnail.files[0]) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gambar sampul wajib diunggah'
                });
                return;
            }

            // Disable submit button to prevent double submission
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

            // Submit the form
            this.submit();
        });
    </script>
</body>

</html>