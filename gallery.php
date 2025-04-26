<?php
$page_title = "Gallery";
$current_page = 'gallery';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'includes/config.php';

$current = isset($_GET['img']) ? (int)$_GET['img'] : 0;

// check current index
if ($current < 0 || $current >= count($gallery_images)) {
    $current = 0;
}

$size = isset($_GET['size']) ? $_GET['size'] : 'medium';

// size parameter
if (!in_array($size, ['small', 'medium', 'large'])) {
    $size = 'medium';
}

// Set width and height
switch ($size) {
    case 'small':
        $width = 400;
        $height = 300;
        break;
    case 'large':
        $width = 800;
        $height = 600;
        break;
    default: // medium
        $width = 600;
        $height = 450;
}

// image data
$current_image = $gallery_images[$current];

$prev = ($current > 0) ? $current - 1 : count($gallery_images) - 1;
$next = ($current < count($gallery_images) - 1) ? $current + 1 : 0;
?>

<style>
.image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    margin: 20px auto;
    transition: transform 0.3s ease;
    max-width: <?php echo $width; ?>px;
}
</style>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="page-heading mb-3 text-center">Hatsune Miku Gallery</h1>
            <p class="page-subheading text-center mb-5">Browse through a stunning collection of Miku images from various artists and events.</p>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="gallery-container text-center">
                <h3 class="gallery-title"><?php echo $current_image['title']; ?></h3>
                <div class="image-wrapper">
                    <img src="images/gallery/<?php echo $current_image['file']; ?>" 
                         alt="<?php echo $current_image['title']; ?>" 
                         class="gallery-image" 
                         width="<?php echo $width; ?>" 
                         height="<?php echo $height; ?>">
                </div>
                
                <div class="image-description">
                    <p class="lead mb-0"><?php echo $current_image['description']; ?></p>
                </div>
                
                <!-- Size Selector -->
                <div class="size-selector">
                    <p class="fw-bold mb-2">Select image size:</p>
                    <a href="gallery.php?img=<?php echo $current; ?>&size=small" 
                       class="size-btn <?php echo ($size === 'small') ? 'active' : ''; ?>">Small</a>
                    <a href="gallery.php?img=<?php echo $current; ?>&size=medium" 
                       class="size-btn <?php echo ($size === 'medium') ? 'active' : ''; ?>">Medium</a>
                    <a href="gallery.php?img=<?php echo $current; ?>&size=large" 
                       class="size-btn <?php echo ($size === 'large') ? 'active' : ''; ?>">Large</a>
                </div>
                
                <!-- Nav -->
                <div class="gallery-nav">
                    <a href="gallery.php?img=<?php echo $prev; ?>&size=<?php echo $size; ?>" class="prev-btn">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                    <span class="image-counter"><?php echo ($current + 1) . ' of ' . count($gallery_images); ?></span>
                    <a href="gallery.php?img=<?php echo $next; ?>&size=<?php echo $size; ?>" class="next-btn">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                
                <div class="thumbnails-container">
                    <?php foreach ($gallery_images as $index => $image): ?>
                        <div class="thumbnail-item">
                            <a href="gallery.php?img=<?php echo $index; ?>&size=<?php echo $size; ?>">
                                <img src="images/gallery/<?php echo $image['file']; ?>" 
                                     alt="<?php echo $image['title']; ?>" 
                                     class="img-thumbnail <?php echo ($index === $current) ? 'active' : ''; ?>" 
                                     width="100" height="75">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (isset($_SESSION['login'])): ?>
        <div class="row mt-4">
            <div class="col-md-10 mx-auto">
                <div class="favorites-card">
                    <div class="favorites-header">
                        <i class="fas fa-heart me-2"></i> Your Gallery Favorites
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['favorite_images']) && !empty($_SESSION['favorite_images'])): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($_SESSION['favorite_images'] as $fav_img): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo $fav_img; ?>
                                        <a href="remove_favorite.php?image=<?php echo urlencode($fav_img); ?>" 
                                           class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-center my-3">You haven't saved any favorite images yet.</p>
                        <?php endif; ?>
                        <form action="add_favorite.php" method="post" class="mt-3 text-center">
                            <input type="hidden" name="image_title" value="<?php echo $current_image['title']; ?>">
                            <button type="submit" class="btn btn-outline-info add-favorite-btn">
                                <i class="fas fa-heart me-2"></i> Add Current Image to Favorites
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>