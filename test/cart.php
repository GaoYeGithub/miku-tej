<?php
/**
 * Cart Class
 * Helper class to manage shopping cart operations
 */
class Cart {
    private $session_key = 'cart';
    
    /**
     * Constructor
     * Initialize session if not already started
     */
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION[$this->session_key])) {
            $_SESSION[$this->session_key] = [];
        }
    }
    
    /**
     * Add item to cart
     * @param array $product Product data
     * @param int $quantity Quantity to add
     * @return bool Success status
     */
    public function addItem($product, $quantity = 1) {
        // Validate inputs
        if (empty($product['id']) || $quantity <= 0) {
            return false;
        }
        
        $found = false;
        
        // Check if product already in cart
        foreach ($_SESSION[$this->session_key] as &$item) {
            if ($item['id'] === $product['id']) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        
        // If not found, add to cart
        if (!$found) {
            $product['quantity'] = $quantity;
            $_SESSION[$this->session_key][] = $product;
        }
        
        return true;
    }
    
    /**
     * Update item quantity
     * @param string $product_id Product ID
     * @param int $quantity New quantity
     * @return bool Success status
     */
    public function updateQuantity($product_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($product_id);
        }
        
        foreach ($_SESSION[$this->session_key] as &$item) {
            if ($item['id'] === $product_id) {
                $item['quantity'] = $quantity;
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Remove item from cart
     * @param string $product_id Product ID
     * @return bool Success status
     */
    public function removeItem($product_id) {
        foreach ($_SESSION[$this->session_key] as $key => $item) {
            if ($item['id'] === $product_id) {
                unset($_SESSION[$this->session_key][$key]);
                $_SESSION[$this->session_key] = array_values($_SESSION[$this->session_key]);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get all items in cart
     * @return array Cart items
     */
    public function getItems() {
        return $_SESSION[$this->session_key];
    }
    
    /**
     * Get item count
     * @param bool $unique Count unique items or total quantity
     * @return int Item count
     */
    public function getCount($unique = false) {
        if ($unique) {
            return count($_SESSION[$this->session_key]);
        }
        
        $count = 0;
        foreach ($_SESSION[$this->session_key] as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }
    
    /**
     * Get cart subtotal
     * @return float Subtotal
     */
    public function getSubtotal() {
        $subtotal = 0;
        foreach ($_SESSION[$this->session_key] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        return $subtotal;
    }
    
    /**
     * Get cart total with tax and shipping
     * @param float $tax_rate Tax rate (e.g., 0.08 for 8%)
     * @param float $shipping Shipping cost
     * @return float Total
     */
    public function getTotal($tax_rate = 0.08, $shipping = 9.99) {
        $subtotal = $this->getSubtotal();
        $tax = $subtotal * $tax_rate;
        
        return $subtotal + $tax + $shipping;
    }
    
    /**
     * Check if cart is empty
     * @return bool True if empty
     */
    public function isEmpty() {
        return empty($_SESSION[$this->session_key]);
    }
    
    /**
     * Clear cart
     * @return void
     */
    public function clear() {
        $_SESSION[$this->session_key] = [];
    }
}
?>