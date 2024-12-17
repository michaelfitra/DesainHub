<?php
// Pastikan buffer output diaktifkan di awal file
ob_start();

// Sertakan konfigurasi sebelum apa pun
// require_once '../config/config.php';

// Cegah multiple session_start()
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

include '../includes/header.php';

// Cek jika user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: masuk.php');
    exit;
}

// Fungsi untuk membersihkan input
function sanitizeInput($input, $type = 'string') {
    if ($input === null) return null;
    
    switch ($type) {
        case 'email':
            return filter_var($input, FILTER_VALIDATE_EMAIL);
        case 'phone':
            return preg_replace("/[^0-9+]/", "", $input);
        case 'string':
        default:
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

// Ambil data user dari database
try {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Hapus sesi jika user tidak ditemukan
        session_destroy();
        header('Location: masuk.php');
        exit;
    }
    
    $user = $result->fetch_assoc();
} catch (Exception $e) {
    // Catat error
    error_log("Kesalahan pengambilan data pengguna: " . $e->getMessage());
    $_SESSION['error_message'] = "Terjadi kesalahan sistem. Silakan coba lagi.";
}

// Proses formulir
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Update Profil
        if (isset($_POST['update_profile'])) {
            $full_name = sanitizeInput($_POST['full_name']) ?? $user['full_name'];
            $username = sanitizeInput($_POST['username']) ?? $user['username'];
            $location = sanitizeInput($_POST['location'] ?? '');
            $description = sanitizeInput($_POST['description'] ?? '');
            $language = in_array($_POST['language'], ['id', 'en']) ? $_POST['language'] : $user['language'];

            // Periksa panjang username
            if (strlen($username) < 3) {
                throw new Exception("Username minimal 3 karakter!");
            }

            $stmt = $conn->prepare("UPDATE users SET full_name = ?, username = ?, description = ?, location = ?, language = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $full_name, $username, $description, $location, $language, $user_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal memperbarui profil: " . $stmt->error);
            }
            
            $_SESSION['success_message'] = "Profil berhasil diperbarui!";
        }

        // Update Kontak
        if (isset($_POST['update_contact'])) {
            $email = sanitizeInput($_POST['email'], 'email');
            $phone = sanitizeInput($_POST['phone'], 'phone');

            if (!$email) {
                throw new Exception("Format email tidak valid!");
            }

            $stmt = $conn->prepare("UPDATE users SET email = ?, phone = ? WHERE id = ?");
            $stmt->bind_param("ssi", $email, $phone, $user_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal memperbarui informasi kontak: " . $stmt->error);
            }
            
            $_SESSION['success_message'] = "Informasi kontak berhasil diperbarui!";
        }

        // Update Keamanan
        if (isset($_POST['update_security'])) {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (!password_verify($current_password, $user['password'])) {
                throw new Exception("Password saat ini salah!");
            }

            if ($new_password !== $confirm_password) {
                throw new Exception("Password baru tidak cocok!");
            }

            // Validasi kekuatan password
            if (strlen($new_password) < 8) {
                throw new Exception("Password minimal 8 karakter!");
            }

            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal memperbarui password: " . $stmt->error);
            }
            
            $_SESSION['success_message'] = "Password berhasil diperbarui!";
        }

        // Update Notifikasi
        if (isset($_POST['update_notifications'])) {
            $email_notif = isset($_POST['emailNotif']) ? 1 : 0;
            $project_notif = isset($_POST['projectNotif']) ? 1 : 0;
            $message_notif = isset($_POST['messageNotif']) ? 1 : 0;

            $stmt = $conn->prepare("UPDATE users SET email_notifications = ?, project_notifications = ?, message_notifications = ? WHERE id = ?");
            $stmt->bind_param("iiii", $email_notif, $project_notif, $message_notif, $user_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal menyimpan pengaturan notifikasi: " . $stmt->error);
            }
            
            $_SESSION['success_message'] = "Pengaturan notifikasi berhasil disimpan!";
        }

        // Redirect untuk mencegah pengiriman ulang
        header("Location: " . $_SERVER['PHP_SELF']);
        ob_end_flush(); // Hapus buffer output
        exit;

    } catch (Exception $e) {
        // Simpan pesan kesalahan di sesi
        $_SESSION['error_message'] = $e->getMessage();
        error_log("Kesalahan pembaruan profil: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil - DesainHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f4f5f7;
        }

        .nav-pills .nav-link {
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .profile-sidebar {
            position: sticky;
            top: 80px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }

        .profile-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .profile-sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .profile-sidebar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .scroll-section {
            scroll-margin-top: 80px;
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#profile-sidebar-nav">
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar Section -->
            <div class="col-md-3">
                <div class="card profile-sidebar shadow-sm mb-4" id="profile-sidebar-nav">
                    <div class="card-body">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link active" href="#profile-info" role="tab">Informasi Profil</a>
                            <a class="nav-link" href="#contact-info" role="tab">Informasi Kontak</a>
                            <a class="nav-link" href="#security-settings" role="tab">Keamanan</a>
                            <a class="nav-link" href="#notification-settings" role="tab">Notifikasi</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="col-md-9">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php
                        echo htmlspecialchars($_SESSION['success_message']);
                        unset($_SESSION['success_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php
                        echo htmlspecialchars($_SESSION['error_message']);
                        unset($_SESSION['error_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Informasi Profil -->
                <section id="profile-info" class="scroll-section mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informasi Profil</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="full_name"
                                            value="<?php echo htmlspecialchars($user['full_name']); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" name="username"
                                            value="<?php echo htmlspecialchars($user['username']); ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Singkat</label>
                                    <textarea class="form-control" name="description"
                                        rows="4"><?php echo htmlspecialchars($user['description'] ?? ''); ?></textarea>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Lokasi</label>
                                        <input type="text" class="form-control" name="location"
                                            value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Bahasa</label>
                                        <select class="form-select" name="language">
                                            <option value="id" <?php echo ($user['language'] ?? '') === 'id' ? 'selected' : ''; ?>>Indonesia</option>
                                            <option value="en" <?php echo ($user['language'] ?? '') === 'en' ? 'selected' : ''; ?>>English</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-primary">Simpan
                                    Perubahan</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Informasi Kontak -->
                <section id="contact-info" class="scroll-section mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informasi Kontak</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email"
                                        value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" name="phone"
                                        value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                        pattern="[+]?[0-9]{10,14}">
                                </div>
                                <button type="submit" name="update_contact" class="btn btn-primary">Perbarui
                                    Kontak</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Keamanan -->
                <section id="security-settings" class="scroll-section mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Keamanan Akun</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Kata Sandi Saat Ini</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kata Sandi Baru</label>
                                    <input type="password" class="form-control" name="new_password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                                <button type="submit" name="update_security" class="btn btn-danger">Ubah Kata
                                    Sandi</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Notifikasi -->
                <section id="notification-settings" class="scroll-section mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pengaturan Notifikasi</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="emailNotif" name="emailNotif"
                                        <?php echo ($user['email_notifications'] ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="emailNotif">Notifikasi Email</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="projectNotif"
                                        name="projectNotif" <?php echo ($user['project_notifications'] ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="projectNotif">Notifikasi Proyek</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="messageNotif"
                                        name="messageNotif" <?php echo ($user['message_notifications'] ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="messageNotif">Notifikasi Pesan</label>
                                </div>
                                <button type="submit" name="update_notifications" class="btn btn-primary">Simpan
                                    Preferensi</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk mengatur highlight pada sidebar
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarNav = document.getElementById('profile-sidebar-nav');
            const navLinks = sidebarNav.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('.scroll-section');

            // Fungsi untuk mengatur active state pada sidebar
            function setActiveNavLink(sectionId) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${sectionId}`) {
                        link.classList.add('active');
                    }
                });
            }

            // Tambahkan event listener untuk navigasi sidebar
            navLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);

                    // Perbarui active state sidebar
                    setActiveNavLink(targetId);

                    // Scroll ke section yang dipilih
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            });

            // Gunakan Intersection Observer untuk update sidebar
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Perbarui active state sidebar
                        setActiveNavLink(entry.target.id);
                    }
                });
            }, {
                threshold: 0.3,
                rootMargin: '-80px 0px 0px 0px'
            });

            // Amati semua section
            sections.forEach(section => {
                observer.observe(section);
            });
        });
    </script>
</body>

</html>