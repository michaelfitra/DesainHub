<?php
require '../config/config.php';

// Initialize error array
$errors = [];

// Handle form submission
if (isset($_POST['register'])) {
    // Sanitize and validate input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate required fields
    if (empty($name))
        $errors['name'] = 'Nama lengkap harus diisi';
    if (empty($email))
        $errors['email'] = 'Email harus diisi';
    if (empty($username))
        $errors['username'] = 'Username harus diisi';
    if (empty($password))
        $errors['password'] = 'Password harus diisi';

    // Validate password match
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Password tidak cocok';
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        try {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Default profile photo
            $profile_photo = ASSETS_PATH_IMG . "profile/profile-pic.webp";

            // Prepare and execute query
            $sql = "INSERT INTO users (full_name, email, username, password, profile_photo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $username, $hashed_password, $profile_photo);

            if ($stmt->execute()) {
                // Set success flag
                $registration_success = true;
            } else {
                $errors['db'] = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
            }
            $stmt->close();
        } catch (Exception $e) {
            $errors['db'] = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Your Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="col-md-6 col-lg-5 p-4 shadow rounded">
            <h3 class="text-center mb-4">Buat Akun</h3>

            <?php if (!empty($errors['db'])): ?>
                <div class="alert alert-danger"><?php echo $errors['db']; ?></div>
            <?php endif; ?>

            <form id="registerForm" method="POST" novalidate>
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name"
                        class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
                        value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    <div id="emailError" class="invalid-feedback">
                        <?php echo $errors['email'] ?? ''; ?>
                    </div>
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username"
                        class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                    <div id="usernameError" class="invalid-feedback">
                        <?php echo $errors['username'] ?? ''; ?>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password"
                        class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" required>
                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
                        required>
                    <div id="passwordError" class="invalid-feedback">
                        <?php echo $errors['confirm_password'] ?? ''; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" name="register" class="btn btn-primary">Register</button>
                </div>

                <!-- Login Redirect -->
                <p class="text-center mt-3">Sudah punya akun? <a href="masuk.php">Login</a>.</p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($registration_success) && $registration_success): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Berhasil!',
                text: 'Anda akan diarahkan ke halaman login...',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(function () {
                window.location.href = 'masuk.php';
            });
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registerForm');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const email = document.getElementById('email');
            const username = document.getElementById('username');

            function debounce(func, wait) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            async function checkAvailability(type, value) {
                try {
                    const response = await fetch('../config/check_availability.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `type=${type}&value=${value}`
                    });
                    return await response.json();
                } catch (error) {
                    console.error('Error checking availability:', error);
                    return { exists: false };
                }
            }

            const validateEmail = debounce(async function () {
                if (email.value) {
                    const result = await checkAvailability('email', email.value);
                    email.classList.toggle('is-invalid', result.exists);
                    email.classList.toggle('is-valid', !result.exists);
                    const errorDiv = email.nextElementSibling;
                    errorDiv.textContent = result.exists ? 'Email sudah terdaftar!' : '';
                }
            }, 500);

            const validateUsername = debounce(async function () {
                if (username.value) {
                    const result = await checkAvailability('username', username.value);
                    username.classList.toggle('is-invalid', result.exists);
                    username.classList.toggle('is-valid', !result.exists);
                    const errorDiv = username.nextElementSibling;
                    errorDiv.textContent = result.exists ? 'Username sudah digunakan!' : '';
                }
            }, 500);

            function validatePassword() {
                const isValid = password.value && confirmPassword.value === password.value;
                confirmPassword.classList.toggle('is-invalid', !isValid);
                confirmPassword.classList.toggle('is-valid', isValid);
                const errorDiv = confirmPassword.nextElementSibling;
                errorDiv.textContent = !isValid ? 'Password tidak cocok!' : '';
            }

            email.addEventListener('input', validateEmail);
            username.addEventListener('input', validateUsername);
            confirmPassword.addEventListener('input', validatePassword);
            password.addEventListener('input', validatePassword);
        });
    </script>
</body>

</html>