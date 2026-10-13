<?php
// admin/comments.php - Admin page to view customer comments
require_once '../config.php';

// Handle delete request
if (isset($_POST['delete_comment']) && isset($_POST['comment_id'])) {
    $comment_id = (int)$_POST['comment_id'];
    $delete_sql = "DELETE FROM comments WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $comment_id);
    
    if ($stmt->execute()) {
        $success_message = "Comment deleted successfully.";
    } else {
        $error_message = "Error deleting comment: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all comments
$sql = "SELECT id, user_name, comment, created_at FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Customer Comments</title>
    <link rel="stylesheet" href="../global.css">
    <style>
        body { background: #f4f6f8; font-family: 'Segoe UI', Arial, sans-serif; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px #0001; padding: 32px; }
        h1 { color: #2d3a4b; margin-bottom: 24px; }
        .back-btn { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:8px; border:1px solid #e0e6ed; background:#f7fafc; color:#2d3a4b; text-decoration:none; font-weight:600; box-shadow:0 1px 4px rgba(0,0,0,0.05); }
        .back-btn:hover { background:#eef4fb; }
        .header-row { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:16px; }
        .dash-link { color:#1a73e8; text-decoration:none; font-weight:600; }
        .dash-link:hover { text-decoration:underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #e0e6ed; }
        th { background: #f0f4f8; color: #2d3a4b; font-weight: 600; }
        tr:last-child td { border-bottom: none; }
        .user { font-weight: 500; color: #1a73e8; }
        .comment { color: #333; }
        .date { color: #888; font-size: 0.97em; }
        .no-comments { text-align: center; color: #888; padding: 40px 0; }
        .delete-btn { background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85em; font-weight: 500; transition: background 0.2s; }
        .delete-btn:hover { background: #c82333; }
        .success-message { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #c3e6cb; }
        .error-message { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #f5c6cb; }
        .actions-col { width: 80px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-row">
            <a href="#" class="back-btn" onclick="if (history.length > 1) { history.back(); } else { window.location.href='admin.php'; } return false;" aria-label="Go back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                Back
            </a>
            
        </div>
        <h1>Customer Comments</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th class="actions-col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td class="user"><?= htmlspecialchars($row['user_name']) ?></td>
                    <td class="comment"><?= nl2br(htmlspecialchars($row['comment'])) ?></td>
                    <td class="date"><?= $row['created_at'] ?></td>
                    <td class="actions-col">
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                            <input type="hidden" name="comment_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete_comment" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="no-comments">No comments found.</div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
if ($result) $result->free();
$conn->close();
?>
