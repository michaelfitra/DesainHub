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
    <?php include '../includes/header.php'; ?>

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

                    <form id="offer-form" class="position-relative">
                        <!-- Langkah 1: Informasi Dasar -->
                        <div id="step-1" class="step-content active">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Penawaran</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Deskripsikan layanan Anda secara singkat" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori Layanan</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Desain Grafis">Desain Grafis</option>
                                    <option value="Penulisan">Penulisan & Penerjemahan</option>
                                    <option value="Pemrograman">Pemrograman & Teknologi</option>
                                    <option value="Marketing">Pemasaran Digital</option>
                                    <option value="Video">Produksi Video</option>
                                    <option value="Konsultasi">Konsultasi</option>
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
                                <input type="file" class="form-control" id="gallery" name="gallery" accept="image/*"
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

        document.getElementById('offer-form').addEventListener('submit', function (e) {
            e.preventDefault();
            // Di sini Anda bisa menambahkan logika submit form
            alert('Penawaran berhasil dibuat!');
        });
    </script>
</body>

</html>