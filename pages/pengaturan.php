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

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }

        .section-highlight {
            background-color: #e9ecef;
            border-left: 4px solid #007bff;
        }

        #sidebar-details {
            font-size: 0.9rem;
        }

        #sidebar-details hr {
            margin: 10px 0;
            border-top: 1px solid #dee2e6;
        }

        .scroll-section {
            scroll-margin-top: 80px;
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#profile-sidebar-nav">

    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar Profil -->
            <div class="col-md-3">
                <div class="card profile-sidebar shadow-sm mb-4" id="profile-sidebar-nav">
                    <div class="card-body">
                        <!-- <img src="https://via.placeholder.com/150" alt="Foto Profil" class="profile-img mb-3">
                        <h5 class="card-title">Mikel</h5>
                        <p class="text-muted">@kaellism4real</p>
                        
                        <div id="sidebar-details" class="text-start mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-geo-alt me-2 text-muted"></i>
                                <span>Lokasi: Indonesia</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar me-2 text-muted"></i>
                                <span>Bergabung: November 2024</span>
                            </div>
                            <hr>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-globe me-2 text-muted"></i>
                                <span>Bahasa: Inggris (Native/Bilingual)</span>
                            </div>
                            <hr>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock me-2 text-muted"></i>
                                <span>Jam Kerja Pilihan: 9 AM - 5 PM</span>
                            </div>
                        </div> -->

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

            <!-- Konten Utama Pengaturan -->
            <div class="col-md-9">
                <!-- Informasi Profil -->
                <section id="profile-info" class="scroll-section mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informasi Profil</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" value="Mikel">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" value="kaellism4real">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Singkat</label>
                                    <textarea class="form-control" rows="4"
                                        placeholder="Tulis sedikit tentang dirimu"></textarea>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Lokasi</label>
                                        <input type="text" class="form-control" value="Indonesia">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Bahasa</label>
                                        <select class="form-select">
                                            <option selected>Inggris (Native/Bilingual)</option>
                                            <option>Indonesia</option>
                                            <option>Mandarin</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="mikel@example.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" value="+62 812 3456 7890">
                                </div>
                                <button type="submit" class="btn btn-primary">Perbarui Kontak</button>
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
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Kata Sandi Saat Ini</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kata Sandi Baru</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-danger">Ubah Kata Sandi</button>
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
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                                <label class="form-check-label" for="emailNotif">Notifikasi Email</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="projectNotif" checked>
                                <label class="form-check-label" for="projectNotif">Notifikasi Proyek</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="messageNotif" checked>
                                <label class="form-check-label" for="messageNotif">Notifikasi Pesan</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Preferensi</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

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