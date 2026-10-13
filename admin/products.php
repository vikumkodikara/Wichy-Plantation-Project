<?php
require_once __DIR__ . '/../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $pid = (int)$_GET['delete'];
    $conn->query("DELETE FROM product WHERE product_id=$pid");
    // Also remove from cart and orders for data integrity
    $conn->query("DELETE FROM cart WHERE product_id=$pid");
    $conn->query("DELETE FROM orders WHERE product_id=$pid");
    header('Location: products.php');
    exit;
}

// Handle update
$edit = null;
$updateMessage = '';
$debugMessage = '';

// Debug: Check if edit parameter is received
if (isset($_GET['edit'])) {
    $pid = (int)$_GET['edit'];
    $debugMessage .= "Edit request for product ID: $pid<br>";
    
    $res = $conn->query("SELECT * FROM product WHERE product_id=$pid");
    if ($res && $res->num_rows > 0) {
        $edit = $res->fetch_assoc();
        $debugMessage .= "Product found: " . $edit['product_name'] . "<br>";
    } else {
        $updateMessage = '<div class="alert alert-error">Product not found! ID: ' . $pid . '</div>';
        $debugMessage .= "Product NOT found for ID: $pid<br>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $pid = (int)$_POST['pid'];
    $name = trim($_POST['name']);
    $price = (float)trim($_POST['price']);
    $img = trim($_POST['img']);
    $desc = trim($_POST['desc']);
    
    $debugMessage .= "Update request: PID=$pid, Name=$name, Price=$price<br>";
    
    // Handle file upload if provided
    if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../public/images/';
        $fileName = 'img_' . uniqid() . '.' . pathinfo($_FILES['img_file']['name'], PATHINFO_EXTENSION);
        $uploadPath = $uploadDir . $fileName;
        
        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExtension = strtolower(pathinfo($_FILES['img_file']['name'], PATHINFO_EXTENSION));
        
        if (in_array($fileExtension, $allowedTypes)) {
            if (move_uploaded_file($_FILES['img_file']['tmp_name'], $uploadPath)) {
                $img = 'public/images/' . $fileName; // Store relative path
                $debugMessage .= "File uploaded successfully: $img<br>";
            } else {
                $updateMessage = '<div class="alert alert-error">Error uploading file!</div>';
            }
        } else {
            $updateMessage = '<div class="alert alert-error">Invalid file type! Please upload JPG, PNG, GIF, or WebP images.</div>';
        }
    }
    
    // Validate inputs
    if (empty($name) || $price <= 0 || empty($img)) {
        $updateMessage = '<div class="alert alert-error">Please fill all required fields with valid data!</div>';
    } else {
        $stmt = $conn->prepare('UPDATE product SET product_name=?, product_price=?, product_image=?, description=? WHERE product_id=?');
        $stmt->bind_param('sdssi', $name, $price, $img, $desc, $pid);
        
        if ($stmt->execute()) {
            $updateMessage = '<div class="alert alert-success">Product updated successfully!</div>';
            // Clear the edit form after successful update
            $edit = null;
            // Optional: Redirect to avoid form resubmission
            header('Location: products.php?updated=1');
            exit;
        } else {
            $updateMessage = '<div class="alert alert-error">Error updating product: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Show update success message if redirected
if (isset($_GET['updated'])) {
    $updateMessage = '<div class="alert alert-success">Product updated successfully!</div>';
}

// Optimize query - only select needed columns and limit results for better performance
$res = $conn->query("SELECT product_id, product_name, product_price, product_image, description FROM product ORDER BY product_id DESC LIMIT 100");
$products = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Products</title>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"></noscript>
<style>
/* ---- OPTIMIZED STYLES ---- */
:root {
    --green: #4CAF50;
    --dark-green: #2E7D32;
    --light-green: #E8F5E9;
    --gray: #666;
    --light-gray: #f5f5f5;
}

body {
    font-family: 'Inter', Arial, sans-serif;
    background: #f8f9fa;
    margin: 0;
    padding: 20px;
}

.btn {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    font-weight: 500;
    border: 1px solid;
    cursor: pointer;
}

.btn-primary {
    background: var(--green);
    color: white;
    border-color: var(--green);
}

.btn-light {
    background: var(--light-green);
    color: var(--dark-green);
    border-color: var(--green);
}

.btn-outline {
    background: white;
    color: var(--gray);
    border-color: #ddd;
}

.btn-danger {
    background: #dc2626;
    color: white;
    border-color: #dc2626;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-edit {
    background: #3b82f6;
    color: white;
    padding: 6px 12px;
    font-size: 12px;
}

.btn-delete {
    background: #ef4444;
    color: white;
    padding: 6px 12px;
    font-size: 12px;
}

/* ---- ADMIN SPECIFIC STYLES ---- */
.container { 
    max-width:1200px; 
    margin:auto; 
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    margin-bottom: 20px;
}

.header { 
    display:flex; 
    justify-content:space-between; 
    align-items:center; 
    margin-bottom:25px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--green);
}

.header h1 {
    color: var(--dark-green);
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
}

.admin-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

/* ---- TABLE STYLING ---- */
.products-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.products-table th {
    background: var(--green);
    color: white;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
}

.products-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.products-table tr:hover {
    background: var(--light-green);
}

.product-name {
    font-weight: 600;
    color: var(--dark-green);
}

.product-price {
    font-weight: 700;
    color: var(--green);
}

.product-description {
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 6px;
}

.btn-danger {
    background-color: #dc2626;
    color: white;
    border: 1px solid #dc2626;
}

.btn-danger:hover {
    background-color: #b91c1c;
    border-color: #b91c1c;
}

/* ---- MOBILE STYLES ---- */
@media (max-width: 768px) {
    .container {
        margin: 10px;
        padding: 15px;
    }
    
    .header {
        flex-direction: column;
        gap: 15px;
    }
    
    .products-table th,
    .products-table td {
        padding: 8px;
        font-size: 12px;
    }
    
    .product-description {
        max-width: 120px;
    }
}

/* ---- SUCCESS/ERROR MESSAGES ---- */
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}
</style>
</head>
<body>
<div class="container">
<div class="header">
<h1>🛍️ Manage Products</h1>
<div class="admin-actions">
    <a href="add_product.php" class="btn btn-primary">➕ Add Product</a>
    <a href="products.php" class="btn btn-light">📦 All Products</a>
    <a href="orders.php" class="btn btn-light">📋 View Orders</a>
    <a href="../admin.php" class="btn btn-outline">🏠 Dashboard</a>
</div>
</div>

<?php if (!empty($updateMessage)): ?>
    <?= $updateMessage ?>
<?php endif; ?>

<?php if (!empty($debugMessage)): ?>
    <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
        <strong>Debug Info:</strong><br>
        <?= $debugMessage ?>
    </div>
<?php endif; ?>

<div style="margin-bottom: 20px; padding: 16px; background: var(--color-bg-light-card); border-radius: 8px; border-left: 4px solid var(--color-main-green);">
    <h3 style="margin: 0 0 8px 0; color: var(--color-dark-green-text);">📊 Products Overview</h3>
    <p style="margin: 0; color: var(--color-gray-600);">Total Products: <strong><?= count($products) ?></strong></p>
</div>

<table class="products-table">
<thead>
<tr>
<th>🆔 ID</th>
<th>📦 Product Name</th>
<th>💰 Price</th>
<th> Description</th>
<th>⚡ Actions</th>
</tr>
</thead>
<tbody>
<?php if (!empty($products)): ?>
    <?php foreach($products as $p): ?>
    <tr>
    <td><strong>#<?= $p['product_id'] ?></strong></td>
    <td class="product-name"><?= htmlspecialchars($p['product_name']) ?></td>
    <td class="product-price">Rs <?= number_format($p['product_price'],2) ?></td>
    <td class="product-description" title="<?= htmlspecialchars($p['description']) ?>">
        <?= htmlspecialchars(strlen($p['description']) > 50 ? substr($p['description'], 0, 50) . '...' : $p['description']) ?>
    </td>
    <td>
        <div class="action-buttons">
            <a href="products.php?edit=<?= $p['product_id'] ?>" class="btn btn-light btn-sm">✏️ Edit</a>
            <a href="products.php?delete=<?= $p['product_id'] ?>" 
               class="btn btn-danger btn-sm" 
               onclick="return confirm('Are you sure you want to delete this product?')">🗑️ Delete</a>
        </div>
    </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="5" style="text-align: center; padding: 40px; color: var(--color-gray-600);">
            <div style="font-size: 48px; margin-bottom: 16px;">📦</div>
            <h3 style="margin: 0 0 8px 0;">No Products Found</h3>
            <p style="margin: 0;">Start by adding your first product!</p>
            <a href="add_product.php" class="btn btn-primary" style="margin-top: 16px;">Add First Product</a>
        </td>
    </tr>
<?php endif; ?>
</tbody>
</table>
<?php if ($edit): ?>
<!-- EDIT FORM STARTS HERE - SHOULD BE VERY VISIBLE -->
<div style="margin-top: 30px; padding: 20px; background: #ffeb3b !important; border: 5px solid #ff5722 !important; border-radius: 8px; box-shadow: 0 4px 20px rgba(255,87,34,0.3);">
    <div style="background: #4CAF50; color: white; padding: 15px; margin: -20px -20px 20px -20px; border-radius: 8px 8px 0 0;">
        <h2 style="margin: 0; font-size: 24px;">🔥 EDIT FORM IS ACTIVE 🔥</h2>
        <p style="margin: 5px 0 0 0;">✏️ Editing Product #<?= $edit['product_id'] ?>: <?= htmlspecialchars($edit['product_name']) ?></p>
    </div>
    
    <div style="background: #e8f5e9; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 2px solid #4CAF50;">
        <strong>⚠️ IMPORTANT:</strong> The edit form is displayed below. Scroll down if you don't see it!
    </div>
    
    <form method="post" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; border: 3px solid #4CAF50;">
        <input type="hidden" name="pid" value="<?= $edit['product_id'] ?>">
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2E7D32; font-size: 16px;">Product Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit['product_name']) ?>" required
                   style="width: 100%; padding: 12px; border: 2px solid #4CAF50; border-radius: 6px; font-size: 16px; background: #f9f9f9;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2E7D32; font-size: 16px;">Price (Rs):</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($edit['product_price']) ?>" required
                   style="width: 100%; padding: 12px; border: 2px solid #4CAF50; border-radius: 6px; font-size: 16px; background: #f9f9f9;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2E7D32; font-size: 16px;">Current Image:</label>
            <div style="margin-bottom: 15px;">
                <img src="../<?= htmlspecialchars($edit['product_image']) ?>" alt="Current Image" 
                     style="max-width: 200px; max-height: 200px; border: 2px solid #ddd; border-radius: 6px; object-fit: cover;">
            </div>
        </div>
        
        <div style="margin-bottom: 15px; background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid #dee2e6;">
            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2E7D32; font-size: 16px;">Update Product Image:</label>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #495057;">📁 Option 1: Upload from Device</label>
                <input type="file" name="img_file" accept="image/*" 
                       style="width: 100%; padding: 10px; border: 2px solid #4CAF50; border-radius: 6px; background: white; font-size: 14px;">
                <small style="color: #6c757d; font-size: 12px;">Supported: JPG, PNG, GIF, WebP (Max 5MB)</small>
            </div>
            
            <div style="text-align: center; margin: 10px 0; color: #6c757d; font-weight: 500;">
                ── OR ──
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #495057;">🔗 Option 2: Use Image URL</label>
                <input type="url" name="img" value="<?= htmlspecialchars($edit['product_image']) ?>" 
                       placeholder="https://example.com/image.jpg"
                       style="width: 100%; padding: 12px; border: 2px solid #4CAF50; border-radius: 6px; font-size: 16px; background: white;">
                <small style="color: #6c757d; font-size: 12px;">Note: File upload will take priority over URL if both are provided</small>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2E7D32; font-size: 16px;">Description:</label>
            <textarea name="desc" rows="4" 
                      style="width: 100%; padding: 12px; border: 2px solid #4CAF50; border-radius: 6px; font-size: 16px; resize: vertical; background: #f9f9f9;"><?= htmlspecialchars($edit['description']) ?></textarea>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 20px;">
            <button type="submit" name="update" 
                    style="background: #4CAF50; color: white; border: none; padding: 15px 30px; border-radius: 8px; font-size: 16px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 10px rgba(76,175,80,0.3);">
                ✅ UPDATE PRODUCT NOW
            </button>
            <a href="products.php" 
               style="background: #f44336; color: white; border: none; padding: 15px 30px; border-radius: 8px; font-size: 16px; text-decoration: none; display: inline-block; font-weight: 600; box-shadow: 0 4px 10px rgba(244,67,54,0.3);">
                ❌ CANCEL EDIT
            </a>
        </div>
    </form>
</div>

<script>
// Scroll to edit form and highlight it
setTimeout(function() {
    const editForm = document.querySelector('div[style*="background: #ffeb3b"]');
    if (editForm) {
        editForm.scrollIntoView({behavior: 'smooth', block: 'center'});
        // Flash the form to make it more noticeable
        let flash = 0;
        const flashInterval = setInterval(function() {
            editForm.style.background = flash % 2 ? '#ffeb3b' : '#ff9800';
            flash++;
            if (flash > 6) {
                clearInterval(flashInterval);
                editForm.style.background = '#ffeb3b';
            }
        }, 200);
    }
}, 100);
</script>

<?php else: ?>
    <!-- Debug: Show if no edit form is displayed -->
    <?php if (isset($_GET['edit'])): ?>
        <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-top: 20px; border: 3px solid #f44336;">
            <strong>🚨 DEBUG ERROR:</strong> Edit parameter received but no product data loaded. Check if product exists.
            <br><strong>Product ID requested:</strong> <?= (int)$_GET['edit'] ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
</div>
</body>
</html>
