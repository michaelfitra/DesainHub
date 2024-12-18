<?php
ob_start();
// Mulai sesi dan masukkan file konfigurasi
// session_start();
// require_once '../config/config.php';
include '../includes/header.php';

// Fungsi untuk membersihkan input
function sanitize_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = "";

// Periksa apakah pengguna sudah menjadi freelancer
try {
    $check_freelancer = $conn->prepare("SELECT is_freelancer FROM users WHERE id = ?");
    $check_freelancer->bind_param("i", $user_id);
    $check_freelancer->execute();
    $result = $check_freelancer->get_result();

    if ($result->num_rows > 0 && $result->fetch_assoc()['is_freelancer'] == 1) {
        header("Location: freelancers-profile.php"); // Jika sudah freelancer, redirect ke dashboard
        exit();
    }
    $check_freelancer->close();
} catch (Exception $e) {
    $error_message = "Kesalahan sistem: " . $e->getMessage();
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan validasi input
    $description = sanitize_input($_POST['description']);
    $location = sanitize_input($_POST['location']);
    $occupation = sanitize_input($_POST['occupation']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = sanitize_input($_POST['phone']);
    $profile_photo = null;

    // Validasi dan unggah foto profil (jika ada)
    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == 0) {
        $upload_dir = '../assets/images/profile/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $file_extension = strtolower(pathinfo($_FILES['profilePhoto']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $max_file_size = 5 * 1024 * 1024; // Maksimum 5MB

        if (in_array($file_extension, $allowed_extensions) && $_FILES['profilePhoto']['size'] <= $max_file_size) {
            $filename = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $upload_path)) {
                $profile_photo = ASSETS_PATH_IMG . "profile/" . $filename;
            } else {
                $error_message = "Gagal mengunggah foto profil.";
            }
        } else {
            $error_message = "File foto tidak valid. Gunakan JPG, JPEG, PNG, atau WebP max 5MB.";
        }
    }

    // Ambil input array dan gabungkan menjadi string
    $languages = isset($_POST['languages']) ? implode(',', array_map('sanitize_input', $_POST['languages'])) : '';
    $skills = isset($_POST['skills']) ? implode(',', array_map('sanitize_input', $_POST['skills'])) : '';
    $education = isset($_POST['education']) ? implode(',', array_map('sanitize_input', $_POST['education'])) : '';
    $certifications = isset($_POST['certifications']) ? implode(',', array_map('sanitize_input', $_POST['certifications'])) : '';

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    }

    // Jika tidak ada error, lakukan update ke database
    if (empty($error_message)) {
        try {
            if ($profile_photo) {
                // Jika foto profil diunggah
                $stmt = $conn->prepare("UPDATE users SET 
                    description = ?, 
                    location = ?, 
                    occupation = ?, 
                    profile_photo = ?, 
                    languages = ?, 
                    skills = ?, 
                    education = ?, 
                    certifications = ?, 
                    is_freelancer = 1, 
                    email = ?, 
                    phone = ? 
                    WHERE id = ?");
                $stmt->bind_param(
                    "ssssssssssi",
                    $description,
                    $location,
                    $occupation,
                    $profile_photo,
                    $languages,
                    $skills,
                    $education,
                    $certifications,
                    $email,
                    $phone,
                    $user_id
                );
            } else {
                // Jika foto profil tidak diunggah
                $stmt = $conn->prepare("UPDATE users SET 
                    description = ?, 
                    location = ?, 
                    occupation = ?, 
                    languages = ?, 
                    skills = ?, 
                    education = ?, 
                    certifications = ?, 
                    is_freelancer = 1, 
                    email = ?, 
                    phone = ? 
                    WHERE id = ?");
                $stmt->bind_param(
                    "sssssssssi",
                    $description,
                    $location,
                    $occupation,
                    $languages,
                    $skills,
                    $education,
                    $certifications,
                    $email,
                    $phone,
                    $user_id
                );
            }

            if ($stmt->execute()) {
                header("Location: dashboard.php"); // Redirect ke dashboard jika berhasil
                exit();
            } else {
                $error_message = "Pendaftaran gagal. Silakan coba lagi.";
            }

            $stmt->close();
        } catch (Exception $e) {
            $error_message = "Kesalahan database: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Freelancer Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f5f7;
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

        .added-item {
            background-color: #e9ecef;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            margin: 5px 5px 0 0;
            color: #007bff;
        }

        @media (max-width: 768px) {
            .step-indicator .step {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="step-container">
                    <h2 class="text-center mb-4">Daftar Sebagai Freelancer</h2>

                    <div class="step-indicator">
                        <div class="step active" data-step="1">
                            <i class="bi bi-person me-2"></i>Informasi Pribadi
                        </div>
                        <div class="step" data-step="2">
                            <i class="bi bi-briefcase me-2"></i>Informasi Profesional
                        </div>
                        <div class="step" data-step="3">
                            <i class="bi bi-shield-lock me-2"></i>Keamanan Akun
                        </div>
                    </div>

                    <form id="freelancer-form" class="position-relative" method="POST" enctype="multipart/form-data">
                        <!-- Langkah 1: Informasi Pribadi -->
                        <div id="step-1" class="step-content active">
                            <div class="mb-3">
                                <label for="profilePhoto" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control" id="profilePhoto" name="profilePhoto"
                                    accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Singkat</label>
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    placeholder="Ceritakan sedikit tentang diri Anda" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    placeholder="Contoh: Jakarta, Indonesia" required>
                            </div>
                            <div class="mb-3">
                                <label for="languages" class="form-label">Bahasa</label>
                                <div id="languages-list" class="mb-2"></div>
                                <input type="text" class="form-control" id="languages"
                                    placeholder="Masukkan bahasa yang Anda kuasai">
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    onclick="addItem('languages')">
                                    <i class="bi bi-plus-circle me-2"></i>Tambah Bahasa
                                </button>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary" onclick="changeStep(2)">
                                    Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modify other steps to include proper name attributes -->
                        <div id="step-2" class="step-content">
                            <div class="mb-3">
                                <label for="occupation" class="form-label">Pekerjaan/Keahlian Utama</label>
                                <input type="text" class="form-control" id="occupation" name="occupation"
                                    placeholder="Contoh: Desainer Grafis, Penulis Konten" required>
                            </div>
                            <div class="mb-3">
                                <label for="skills" class="form-label">Keterampilan</label>
                                <div id="skills-list" class="mb-2"></div>
                                <input type="text" class="form-control" id="skills"
                                    placeholder="Masukkan keterampilan Anda">
                                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addItem('skills')">
                                    <i class="bi bi-plus-circle me-2"></i>Tambah Keterampilan
                                </button>
                            </div>
                            <div class="mb-3">
                                <label for="education" class="form-label">Pendidikan</label>
                                <div id="education-list" class="mb-2"></div>
                                <input type="text" class="form-control" id="education"
                                    placeholder="Contoh: Sarjana Desain Grafis">
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    onclick="addItem('education')">
                                    <i class="bi bi-plus-circle me-2"></i>Tambah Pendidikan
                                </button>
                            </div>
                            <div class="mb-3">
                                <label for="certifications" class="form-label">Sertifikasi</label>
                                <div id="certifications-list" class="mb-2"></div>
                                <input type="text" class="form-control" id="certifications"
                                    placeholder="Contoh: Sertifikat Desain">
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    onclick="addItem('certifications')">
                                    <i class="bi bi-plus-circle me-2"></i>Tambah Sertifikasi
                                </button>
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

                        <!-- Langkah 3: Keamanan Akun -->
                        <div id="step-3" class="step-content">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Masukkan email Anda" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    placeholder="Contoh: +62 812 3456 7890" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="changeStep(2)">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-2"></i>Selesaikan Pendaftaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to change registration steps with validation
        function changeStep(targetStep) {
            // Validasi form sebelum pindah
            const currentStep = document.querySelector('.step-content.active');
            const inputs = currentStep.querySelectorAll('input, select, textarea');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) return;

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

        // Function to add dynamic items to lists
        function addItem(type) {
            const input = document.getElementById(type);
            const value = input.value.trim();
            if (value) {
                const list = document.getElementById(`${type}-list`);
                const item = document.createElement('span');
                item.className = 'added-item';
                item.innerHTML = `${value} <i class="bi bi-x-circle ms-2" onclick="removeItem(this)"></i>`;
                list.appendChild(item);
                input.value = ''; // Mengosongkan input setelah menambahkan item
            }
        }

        // Function to remove dynamic items from lists
        function removeItem(closeBtn) {
            closeBtn.parentElement.remove();
        }

        // Form submission handler
        document.getElementById('freelancer-form').addEventListener('submit', function (e) {
            // Validasi form sebelum submit
            const inputs = document.querySelectorAll('#freelancer-form input, #freelancer-form textarea');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                return;
            }

            // Collect dynamic items for submission
            function collectItems(type) {
                const items = document.querySelectorAll(`#${type}-list .added-item`);
                return Array.from(items).map(item =>
                    item.textContent.trim().replace('×', '').replace(/\s*×\s*$/, '')
                );
            }

            // Add hidden inputs for dynamic items
            const hiddenInputs = [
                { type: 'languages', field: 'languages[]' },
                { type: 'skills', field: 'skills[]' },
                { type: 'education', field: 'education[]' },
                { type: 'certifications', field: 'certifications[]' }
            ];

            hiddenInputs.forEach(({ type, field }) => {
                const items = collectItems(type);
                items.forEach(item => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = field;
                    hiddenInput.value = item;
                    this.appendChild(hiddenInput);
                });
            });

            // Tambahkan konfirmasi SweetAlert sebelum submit
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                text: 'Apakah Anda yakin ingin mendaftar sebagai freelancer?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Daftar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form secara tradisional
                    this.submit();
                }
            });
        });

        // Optional: Add event listeners for input validation
        document.addEventListener('DOMContentLoaded', () => {
            const requiredInputs = document.querySelectorAll('#freelancer-form input[required], #freelancer-form textarea[required]');

            requiredInputs.forEach(input => {
                input.addEventListener('input', function () {
                    if (this.value.trim() !== '') {
                        this.classList.remove('is-invalid');
                    } else {
                        this.classList.add('is-invalid');
                    }
                });
            });
        });
    </script>
</body>

</html>
