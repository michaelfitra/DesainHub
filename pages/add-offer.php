<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Offer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-bottom: 3px solid #ddd;
            color: #aaa;
        }

        .step.active {
            border-color: #007bff;
            color: #007bff;
        }

        .step.completed {
            border-color: #28a745;
            color: #28a745;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5" style="height: 70vh;">
        <h1 class="text-center mb-4">Tambah Offer</h1>

        <div class="col-lg-8 offset-lg-2">
            <div class="step-indicator">
                <div class="step active" id="step-1-indicator">Informasi Dasar</div>
                <div class="step" id="step-2-indicator">Rincian & Harga</div>
                <div class="step" id="step-3-indicator">Galeri & Pratinjau</div>
            </div>
        </div>
        <div class="col-lg-6 offset-lg-3 mt-5">
            <!-- Sesi 1 -->
            <form id="offer-form">
                <div id="step-1" class="step-content">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Offer</label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Judul layanan Anda">
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select" id="category" name="category">
                            <option value="Desain Grafis">Desain Grafis</option>
                            <option value="Penulisan">Penulisan</option>
                            <option value="Pemrograman">Pemrograman</option>
                            <option value="Marketing">Marketing</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Singkat</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                            placeholder="Jelaskan detail layanan Anda"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Lanjut</button>
                </div>

                <!-- Sesi 2 -->
                <div id="step-2" class="step-content hidden">
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" id="price" name="price"
                            placeholder="Harga layanan Anda">
                    </div>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Waktu Pengerjaan (hari)</label>
                        <input type="number" class="form-control" id="duration" name="duration"
                            placeholder="Durasi pengerjaan">
                    </div>
                    <div class="mb-3">
                        <label for="add-ons" class="form-label">Revisi Tambahan</label>
                        <input type="number" class="form-control" id="add-ons" name="add_ons"
                            placeholder="Jumlah revisi tambahan">
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Kembali</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(3)">Lanjut</button>
                </div>

                <!-- Sesi 3 -->
                <div id="step-3" class="step-content hidden">
                    <div class="mb-3">
                        <label for="offer_image" class="form-label">Unggah Gambar</label>
                        <input class="form-control" type="file" id="offer_image" name="offer_image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="offer_video" class="form-label">Unggah Video (Opsional)</label>
                        <input class="form-control" type="file" id="offer_video" name="offer_video" accept="video/*">
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Kembali</button>
                    <button type="submit" class="btn btn-success">Simpan Offer</button>
                </div>
            </form>
        </div>

    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        function nextStep(step) {
            document.querySelectorAll('.step-content').forEach(content => content.classList.add('hidden'));
            document.querySelector(`#step-${step}`).classList.remove('hidden');
            document.querySelectorAll('.step').forEach(indicator => indicator.classList.remove('active', 'completed'));
            document.querySelector(`#step-${step}-indicator`).classList.add('active');
            if (step > 1) document.querySelector(`#step-${step - 1}-indicator`).classList.add('completed');
        }

        function prevStep(step) {
            nextStep(step);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>