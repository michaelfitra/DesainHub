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
    <?php include '../includes/header.php'; ?>
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

                    <form id="freelancer-form" class="position-relative">
                        <!-- Langkah 1: Informasi Pribadi -->
                        <div id="step-1" class="step-content active">
                            <div class="mb-3">
                                <label for="profilePhoto" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control" id="profilePhoto" name="profilePhoto"
                                    accept="image/*" required>
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

                        <!-- Langkah 2: Informasi Profesional -->
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

        function removeItem(closeBtn) {
            closeBtn.parentElement.remove();
        }

        document.getElementById('freelancer-form').addEventListener('submit', function (e) {
            e.preventDefault();

            // Validasi form sebelum menampilkan Sweet Alert
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

            if (!isValid) return;

            // Tampilkan Sweet Alert jika form valid
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Berhasil!',
                text: 'Anda telah berhasil mendaftar sebagai freelancer.',
                confirmButtonText: 'OK'
            });
        });
    </script>
</body>

</html>