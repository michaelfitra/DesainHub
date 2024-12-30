<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/DesainHub/config/config.php';
?>

<div class="card border-0">
    <a href="<?php echo ASSETS_PATH_PAGES; ?>service-detail.php">
        <img src="<?php echo ASSETS_PATH_IMG; ?>CoverImage.jpg" class="card-img-top card-img-bottom" alt="Service Photo" style="height: 200px; object-fit: cover;">
    </a>
    <div class="card-body px-0">
        <h5 class="card-title fw-bold">Service Title</h5>
        <p class="card-text text-muted">A short, engaging description of the service offered. Highlights
            the main features or benefits.</p>
        <!-- Rating -->
        <div class="d-flex align-items-center mb-3">
            <span class="text-warning">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
            </span>
            <span class="ms-2 text-muted">(4.5) 120 Reviews</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted fw-bold">Starting at $50</span>
            <a href="<?php echo ASSETS_PATH_PAGES; ?>service-detail.php" class="btn btn-primary btn-sm">Order Now</a>
        </div>
    </div>
</div>