<?php
include "db_connect.php";

// Delete all QC entries for a table
if (isset($_GET['all']) && $_GET['all'] == 1 && isset($_GET['table_name'])) {
    $table = $conn->real_escape_string($_GET['table_name']);
    $sql = "DELETE FROM quality_control WHERE table_name='$table'";
    if ($conn->query($sql)) {
        echo "<script>
            alert('All QC records for $table deleted successfully!');
            window.location.href = 'display_wichy_coconut.php?table=$table';
        </script>";
    } else {
        echo "<script>
            alert('Error deleting QC records: " . addslashes($conn->error) . "');
            window.location.href = 'display_wichy_coconut.php?table=$table';
        </script>";
    }
}

// Delete a single QC entry
elseif (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Get the table name before deletion for redirect
    $table_query = "SELECT table_name FROM quality_control WHERE id=$id";
    $table_result = $conn->query($table_query);
    $table_name = '';
    if ($table_result && $table_result->num_rows > 0) {
        $table_row = $table_result->fetch_assoc();
        $table_name = $table_row['table_name'];
    }
    
    $sql = "DELETE FROM quality_control WHERE id=$id";
    if ($conn->query($sql)) {
        echo "<script>
            alert('QC record deleted successfully!');
            window.location.href = 'display_wichy_coconut.php" . ($table_name ? "?table=" . urlencode($table_name) : "") . "';
        </script>";
    } else {
        echo "<script>
            alert('Error deleting QC record: " . addslashes($conn->error) . "');
            history.back();
        </script>";
    }
}

// No valid parameters passed
else {
    echo "<script>
        alert('No action specified.');
        history.back();
    </script>";
}

$conn->close();
?>
