<?php
$page_title = "Shop";
$current_page = 'shop';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'includes/config.php';

// Get category filter from URL or show all
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';

// Get cart from session if exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart action
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $found = false;
    
    // Check if product already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $product_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }
    
    // If not found, add to cart
    if (!$found) {
        // Find product in products array
        foreach ($products as $product) {
            if ($product['id'] === $product_id) {
                $product['quantity'] = 1;
                $_SESSION['cart'][] = $product;
                break;
            }
        }
    }
    
    // Redirect to avoid form resubmission
    header("Location: shop.php?added=true" . ($category_filter !== 'all' ? "&category=$category_filter" : ""));
    exit();
}

// Handle cart updates
if (isset($_POST['update_cart'])) {
    if (isset($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            // Remove item if quantity is 0
            if ($quantity <= 0) {
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['id'] === $product_id) {
                        unset($_SESSION['cart'][$key]);
                        break;
                    }
                }
            } else {
                // Update quantity
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] === $product_id) {
                        $item['quantity'] = $quantity;
                        break;
                    }
                }
            }
        }
    }
    
    // Re-index array after potential removals
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    
    // Redirect to avoid form resubmission
    header("Location: shop.php?updated=true" . ($category_filter !== 'all' ? "&category=$category_filter" : ""));
    exit();
}

// Handle item removal
if (isset($_GET['remove']) && isset($_GET['id'])) {
    $remove_id = $_GET['id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] === $remove_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    
    // Re-index array after removal
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    
    // Redirect
    header("Location: shop.php?removed=true" . ($category_filter !== 'all' ? "&category=$category_filter" : ""));
    exit();
}

// Filter products by category if needed
$filtered_products = ($category_filter !== 'all') ? 
    array_filter($products, function($product) use ($category_filter) {
        return $product['category'] === $category_filter;
    }) : 
    $products;
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 text-center">Miku Merchandise Shop</h1>
            <p class="text-center mb-5">Browse our collection of official and fan-made Hatsune Miku merchandise.</p>
        </div>
    </div>
    
    <?php if (isset($_GET['added']) && $_GET['added'] === 'true'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Item added to your cart!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['updated']) && $_GET['updated'] === 'true'): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        Your cart has been updated!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['removed']) && $_GET['removed'] === 'true'): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        Item removed from your cart!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <!-- Category filter buttons -->
            <div class="category-filter mb-4">
                <a href="shop.php" class="btn <?php echo $category_filter === 'all' ? 'btn-info' : 'btn-outline-info'; ?> m-1">All Items</a>
                <?php foreach ($categories as $cat_key => $cat_name): ?>
                <a href="shop.php?category=<?php echo $cat_key; ?>" class="btn <?php echo $category_filter === $cat_key ? 'btn-info' : 'btn-outline-info'; ?> m-1"><?php echo $cat_name; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <!-- Cart preview -->
            <div class="cart-preview">
                <a href="#" class="btn btn-outline-info" data-toggle="modal" data-target="#cartModal">
                    <i class="fas fa-shopping-cart"></i> Cart 
                    <span class="badge badge-pill badge-info">
                        <?php 
                        $total_items = 0;
                        foreach ($_SESSION['cart'] as $item) {
                            $total_items += $item['quantity'];
                        }
                        echo $total_items; 
                        ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Product Grid -->
    <div class="row">
        <?php if (empty($filtered_products)): ?>
            <div class="col-12 text-center">
                <p class="lead">No products found in this category.</p>
            </div>
        <?php else: ?>
            <?php foreach ($filtered_products as $product): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 product-card">
                        <div class="card-img-top-container">
                            <img src="images/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <p class="card-text product-price"><?php echo '$' . number_format($product['price'], 2); ?></p>
                            <p class="card-text"><?php echo $product['description']; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="stock-info <?php echo $product['stock'] < 10 ? 'low-stock' : ''; ?>">
                                    <?php echo $product['stock'] > 0 ? 'In Stock: ' . $product['stock'] : 'Out of Stock'; ?>
                                </span>
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-info btn-sm" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="cartModalLabel">Your Shopping Cart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (empty($_SESSION['cart'])): ?>
                    <p class="text-center">Your cart is empty.</p>
                <?php else: ?>
                    <form method="post">
                        <input type="hidden" name="update_cart" value="1">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Actions</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $cart_total = 0; ?>
                                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                        <tr>
                                            <td><?php echo $item['name']; ?></td>
                                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                                            <td>
                                                <input type="number" name="quantity[<?php echo $item['id']; ?>]" 
                                                    value="<?php echo $item['quantity']; ?>" min="0" max="<?php echo $item['stock']; ?>" 
                                                    class="form-control form-control-sm" style="width: 70px;">
                                            </td>
                                            <td>
                                                <a href="shop.php?remove=1&id=<?php echo $item['id']; ?>" 
                                                   class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Remove
                                                </a>
                                            </td>
                                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        </tr>
                                        <?php $cart_total += $item['price'] * $item['quantity']; ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total:</th>
                                        <th>$<?php echo number_format($cart_total, 2); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-secondary">Update Cart</button>
                            <a href="checkout.php" class="btn btn-info">Proceed to Checkout</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Continue Shopping</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom stylesheet for shop -->
<style>
    .product-card {
        transition: transform 0.3s;
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .card-img-top-container {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    
    .card-img-top {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    
    .product-price {
        font-size: 1.25rem;
        font-weight: bold;
        color: #17a2b8;
    }
    
    .low-stock {
        color: #dc3545;
    }
    
    .category-filter .btn {
        margin-right: 5px;
    }
</style>

<?php require_once 'includes/footer.php'; ?>