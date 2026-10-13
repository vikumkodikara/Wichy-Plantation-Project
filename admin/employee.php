<?php
require_once __DIR__ . '/../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$success = $error = '';

// Handle Delete (via GET)
if (isset($_GET['delete'])) {
    $eid = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM employees WHERE Emp_ID = ?");
    $stmt->bind_param("i", $eid);
    if ($stmt->execute()) {
        // redirect with a flag so we can show a message
        header('Location: employee.php?deleted=1');
        exit;
    } else {
        $error = 'Delete failed: ' . $conn->error;
    }
}

if (isset($_GET['deleted'])) {
    $success = 'Employee deleted successfully.';
}

// Handle Edit - Get employee data for editing
$edit = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM employees WHERE Emp_ID = ?");
    $stmt->bind_param("i", $eid);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit = $result->fetch_assoc();
    
    // Get phone numbers for this employee
    if ($edit) {
        $stmt = $conn->prepare("SELECT Phone FROM employee_phones WHERE Emp_ID = ?");
        $stmt->bind_param("i", $eid);
        $stmt->execute();
        $phoneResult = $stmt->get_result();
        $edit['phone_numbers'] = [];
        while ($phone = $phoneResult->fetch_assoc()) {
            $edit['phone_numbers'][] = $phone['Phone'];
        }
    }
}

// Handle Update (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $eid = (int)$_POST['emp_id'];
    $name = trim($_POST['name'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phones = $_POST['phones'] ?? [];

    // Server-side validation for required fields
    if (!$name || !$role || !$address) {
        $error = 'Please fill name, role and address.';
    } else {
        // Update employee basic info
        $stmt = $conn->prepare("UPDATE employees SET Name = ?, Role = ?, Address = ? WHERE Emp_ID = ?");
        $stmt->bind_param("sssi", $name, $role, $address, $eid);
        
        if ($stmt->execute()) {
            // Delete existing phone numbers
            $stmt = $conn->prepare("DELETE FROM employee_phones WHERE Emp_ID = ?");
            $stmt->bind_param("i", $eid);
            $stmt->execute();
            
            // Add updated phone numbers
            $validPhones = [];
            foreach ($phones as $rawPhone) {
                $rawPhone = trim($rawPhone);
                if ($rawPhone === '') continue;
                
                $normalized = preg_replace('/[^\d+]/', '', $rawPhone);
                
                if (preg_match('/^(?:\+94|0)?[1-9]\d{8}$/', $normalized)) {
                    if (strpos($normalized, '+94') === 0) {
                        // Already has +94 prefix, keep as is
                        $validPhones[] = $normalized;
                    } elseif (strpos($normalized, '0') === 0) {
                        $normalized = '+94' . substr($normalized, 1);
                        $validPhones[] = $normalized;
                    } else {
                        $normalized = '+94' . $normalized;
                        $validPhones[] = $normalized;
                    }
                }
            }
            
            // Insert valid phone numbers
            foreach ($validPhones as $phone) {
                $stmt = $conn->prepare("INSERT INTO employee_phones (Emp_ID, Phone) VALUES (?, ?)");
                $stmt->bind_param("is", $eid, $phone);
                $stmt->execute();
            }
            
            header('Location: employee.php?updated=1');
            exit;
        } else {
            $error = 'Update failed: ' . $conn->error;
        }
    }
}

if (isset($_GET['updated'])) {
    $success = 'Employee updated successfully.';
}

// Handle Add (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phones = $_POST['phones'] ?? []; // may be an array of strings

    // Server-side validation for required fields
    if (!$name || !$role || !$address) {
        $error = 'Please fill name, role and address.';
    } else {
        // Validate and normalize phone numbers
        $validPhones = [];
        foreach ($phones as $rawPhone) {
            $rawPhone = trim($rawPhone);
            if ($rawPhone === '') continue; // ignore empty inputs

            // remove spaces, dashes and other characters except leading plus
            $normalized = preg_replace('/[^\d+]/', '', $rawPhone);

            // Accept Sri Lanka formats:
            //  - Local: 0 + 9 digits  (e.g. 0771234567)
            //  - International: +94 + 9 digits (e.g. +94771234567)
            if (preg_match('/^(?:0\d{9}|\+94\d{9})$/', $normalized)) {
                $validPhones[] = $normalized;
            } else {
                $error = "Invalid phone number format: {$rawPhone}. Use 0771234567 or +94771234567.";
                break;
            }
        }

        if (empty($validPhones) && !$error) {
            $error = "Please provide at least one valid phone number.";
        }
    }

    // If no validation errors, insert employee and phones inside a transaction
    if (!$error) {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO employees (Role, Name, Address) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $role, $name, $address);
            if (!$stmt->execute()) {
                throw new Exception("Insert employee failed: " . $stmt->error);
            }
            $emp_id = $stmt->insert_id;

            $phone_stmt = $conn->prepare("INSERT INTO employee_phones (Emp_ID, Phone) VALUES (?, ?)");
            foreach ($validPhones as $ph) {
                $phone_stmt->bind_param("is", $emp_id, $ph);
                if (!$phone_stmt->execute()) {
                    throw new Exception("Insert phone failed: " . $phone_stmt->error);
                }
            }

            $conn->commit();
            $success = "Employee added successfully!";
        } catch (Exception $ex) {
            $conn->rollback();
            $error = $ex->getMessage();
        }
    }
}

// Fetch employees with phones (GROUP_CONCAT)
$res = $conn->query("
    SELECT e.Emp_ID, e.Name, e.Role, e.Address,
           GROUP_CONCAT(p.Phone SEPARATOR ', ') AS Phones
    FROM employees e
    LEFT JOIN employee_phones p ON e.Emp_ID = p.Emp_ID
    GROUP BY e.Emp_ID
    ORDER BY e.Emp_ID DESC
");
$employee = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Employees</title>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap"></noscript>
<style>
/* ---- EXPERT DESIGN SYSTEM ---- */
:root {
    --green: #4CAF50;
    --dark-green: #2E7D32;
    --light-green: #E8F5E9;
    --gray: #666;
    --light-gray: #f5f5f5;
    --bg: #f8fffe;
    --accent: #00c853;
    --shadow: rgba(0, 0, 0, 0.1);
    --danger: #e74c3c;
    --success: #27ae60;
}

body {
    font-family: 'Inter', Arial, sans-serif;
    background: linear-gradient(135deg, var(--bg) 0%, #e8f5e9 100%);
    margin: 0;
    padding: 20px;
    color: #2c3e50;
    line-height: 1.6;
}

/* ---- MODERN CONTAINER ---- */
.container {
    max-width: 1400px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 
        0 20px 40px var(--shadow),
        0 0 0 1px rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.container h1 {
    color: var(--dark-green);
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 30px 0;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.container h1::before {
    content: '👥';
    font-size: 2.2rem;
}

.container h3 {
    color: var(--dark-green);
    font-size: 1.6rem;
    font-weight: 600;
    margin: 40px 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 3px solid transparent;
    background: linear-gradient(90deg, var(--green), var(--accent)) bottom/100% 3px no-repeat;
}

.btn {
    padding: 0.375rem 1.25rem;
    border-radius: 9999px;
    text-decoration: none;
    transition: background-color 0.3s, color 0.3s;
    display: inline-block;
    text-align: center;
    border: none;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
}

.btn-outline {
    border: 1px solid var(--color-gray-700);
    color: var(--color-gray-800);
}

.btn-outline:hover {
    background-color: var(--color-gray-700);
    color: white;
}

.btn-solid {
    background-color: var(--color-gray-800);
    color: white;
}

.btn-solid:hover {
    background-color: var(--color-gray-700);
}

.btn-primary {
    background-color: var(--color-dark-green-text);
    color: white;
    border-color: var(--color-dark-green-text);
}

.btn-primary:hover {
    background-color: var(--color-medium-green-text);
}

.btn-light {
    background-color: var(--color-bg-light-card);
    color: var(--color-dark-green-text);
    border: 1px solid var(--color-main-green);
}

.btn-light:hover {
    background-color: var(--color-main-green);
    color: var(--color-dark-green-text);
}

/* ---- MODERN BUTTON SYSTEM ---- */
.btn {
    padding: 12px 24px;
    border-radius: 12px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
    font-size: 14px;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    margin: 4px 8px 4px 0;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, var(--green) 0%, var(--dark-green) 100%);
    color: white;
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(76, 175, 80, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger) 0%, #c0392b 100%);
    color: white;
    box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
}

.btn-danger:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(231, 76, 60, 0.4);
}

/* ---- PREMIUM FORM STYLING ---- */
.form-section {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
    backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 25px var(--shadow);
    margin-bottom: 30px;
}

label {
    display: block;
    font-weight: 600;
    color: var(--dark-green);
    margin-bottom: 8px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

input[type="text"] {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    font-size: 16px;
    font-family: 'Inter', sans-serif;
    background: rgba(255, 255, 255, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-sizing: border-box;
    margin-bottom: 16px;
}

input[type="text"]:focus {
    outline: none;
    border-color: var(--green);
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.15);
}

/* ---- PREMIUM TABLE DESIGN ---- */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px var(--shadow);
    margin-top: 30px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

th {
    background: linear-gradient(135deg, var(--dark-green) 0%, var(--green) 100%);
    color: white;
    padding: 20px 16px;
    text-align: left;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    border: none;
}

th::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: rgba(255, 255, 255, 0.3);
}

td {
    padding: 18px 16px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
    color: #2c3e50;
    transition: all 0.3s ease;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover td {
    background: linear-gradient(135deg, var(--light-green) 0%, rgba(232, 245, 233, 0.7) 100%);
    transform: scale(1.01);
}

/* ---- ALERT MESSAGES ---- */
.notice {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
}

.notice.success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 1px solid #34d399;
}

.notice.success::before {
    content: '✅';
    font-size: 18px;
}

.notice.error {
    background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%);
    color: #991b1b;
    border: 1px solid #f87171;
}

.notice.error::before {
    content: '❌';
    font-size: 18px;
}

/* ---- RESPONSIVE DESIGN ---- */
@media (max-width: 768px) {
    .container {
        padding: 24px;
        margin: 10px;
    }
    
    .container h1 {
        font-size: 2rem;
    }
    
    .form-section {
        padding: 20px;
    }
    
    table {
        font-size: 12px;
    }
    
    th, td {
        padding: 12px 8px;
    }
    
    .btn {
        padding: 10px 16px;
        font-size: 12px;
    }
}
</style>

<script>
// Add new phone input with modern styling
function addPhoneField() {
    const div = document.createElement('div');
    div.className = 'phone-field';
    div.innerHTML = '<input type="text" name="phones[]" placeholder="Phone Number (0771234567 or +94771234567)" required>';
    document.getElementById('phone-fields').appendChild(div);
}

// Validate phone numbers before submit
function validatePhones(e) {
    const inputs = document.querySelectorAll('input[name="phones[]"]');
    const re = /^(?:0\d{9}|\+94\d{9})$/;
    for (let i = 0; i < inputs.length; i++) {
        const raw = inputs[i].value.trim();
        if (!raw) {
            e.preventDefault();
            alert('Please fill all phone inputs or remove empty ones.');
            return false;
        }
        const normalized = raw.replace(/[^\d+]/g, '');
        if (!re.test(normalized)) {
            e.preventDefault();
            alert('Invalid phone: ' + raw + '\\nUse Sri Lanka format: 0771234567 or +94771234567');
            return false;
        }
        // normalize value before sending
        inputs[i].value = normalized;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('add-employee-form');
    form.addEventListener('submit', validatePhones);
});
</script>
</head>
<body>
<div class="container">
    <!-- Header with Back Button -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid transparent; background: linear-gradient(90deg, var(--green), var(--accent)) bottom/100% 3px no-repeat;">
        <h1 style="margin: 0;">Employee Management</h1>
        <a href="../admin.php" class="btn" style="background: rgba(255, 255, 255, 0.9); color: var(--dark-green); border-color: var(--green); backdrop-filter: blur(10px);">
            ⬅️ Back to Dashboard
        </a>
    </div>

    <?php if ($success): ?>
        <div class="notice success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="notice error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Add Employee Form -->
    <div class="form-section">
        <h3>➕ Add New Employee</h3>
        <form method="post" id="add-employee-form" novalidate>
            <label for="name">👤 Employee Name</label>
            <input type="text" id="name" name="name" required placeholder="Enter full name">

            <label for="role">💼 Job Role</label>
            <input type="text" id="role" name="role" required placeholder="e.g., Manager, Cashier, Sales Associate">

            <label for="address">🏠 Address</label>
            <input type="text" id="address" name="address" required placeholder="Enter full address">

            <label>📱 Phone Numbers</label>
            <div id="phone-fields">
                <div class="phone-field">
                    <input type="text" name="phones[]" placeholder="Phone Number (0771234567 or +94771234567)" required>
                </div>
            </div>
            <button type="button" onclick="addPhoneField()" class="btn" style="background: var(--light-green); color: var(--dark-green); margin-bottom: 20px;">
                ➕ Add More Phones
            </button>
            <br>
            <button type="submit" name="add" class="btn btn-primary">
                ✨ Add Employee
            </button>
        </form>
    </div>

    <!-- Edit Employee Form -->
    <?php if (isset($edit) && $edit): ?>
    <div style="background: #fff3e0; border: 3px solid #ff9800; border-radius: 12px; padding: 25px; margin: 30px 0; box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);">
        <div style="background: #ff9800; color: white; padding: 15px; margin: -25px -25px 20px -25px; border-radius: 9px 9px 0 0;">
            <h3 style="margin: 0; font-size: 20px;">✏️ Edit Employee: <?= htmlspecialchars($edit['Name']) ?></h3>
        </div>
        
        <form method="POST" style="background: white; padding: 20px; border-radius: 8px; border: 2px solid #ff9800;">
            <input type="hidden" name="emp_id" value="<?= $edit['Emp_ID'] ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #e65100;">👤 Employee Name:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($edit['Name']) ?>" required
                           style="width: 100%; padding: 12px; border: 2px solid #ff9800; border-radius: 6px; font-size: 16px;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #e65100;">💼 Role/Position:</label>
                    <input type="text" name="role" value="<?= htmlspecialchars($edit['Role']) ?>" required
                           style="width: 100%; padding: 12px; border: 2px solid #ff9800; border-radius: 6px; font-size: 16px;">
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #e65100;">🏠 Address:</label>
                <textarea name="address" rows="3" required
                          style="width: 100%; padding: 12px; border: 2px solid #ff9800; border-radius: 6px; font-size: 16px; resize: vertical;"><?= htmlspecialchars($edit['Address']) ?></textarea>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #e65100;">📱 Phone Numbers:</label>
                <div id="edit-phone-container">
                    <?php 
                    $existingPhones = $edit['phone_numbers'] ?? [];
                    for ($i = 0; $i < max(3, count($existingPhones)); $i++): 
                    ?>
                    <div style="margin-bottom: 8px;">
                        <input type="tel" name="phones[]" 
                               value="<?= isset($existingPhones[$i]) ? htmlspecialchars($existingPhones[$i]) : '' ?>"
                               placeholder="+94XXXXXXXXX or 0XXXXXXXXX"
                               style="width: 100%; padding: 10px; border: 2px solid #ff9800; border-radius: 6px; font-size: 14px;">
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 25px;">
                <button type="submit" name="update" 
                        style="background: #4caf50; color: white; border: none; padding: 15px 30px; border-radius: 8px; font-size: 16px; cursor: pointer; font-weight: 600;">
                    💾 Update Employee
                </button>
                <a href="employee.php" 
                   style="background: #f44336; color: white; padding: 15px 30px; border-radius: 8px; font-size: 16px; text-decoration: none; font-weight: 600;">
                    ❌ Cancel
                </a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Employee Table -->
    <h3>👥 Employee Directory</h3>
    <?php if (!empty($employee)): ?>
    <table>
        <thead>
            <tr>
                <th>🆔 ID</th>
                <th>👤 Name</th>
                <th>💼 Role</th>
                <th>🏠 Address</th>
                <th>📱 Phone Numbers</th>
                <th>⚡ Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employee as $e): ?>
            <tr>
                <td><strong>#<?= (int)$e['Emp_ID'] ?></strong></td>
                <td style="font-weight: 600; color: var(--dark-green);"><?= htmlspecialchars($e['Name']) ?></td>
                <td>
                    <span style="background: var(--light-green); color: var(--dark-green); padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                        <?= htmlspecialchars($e['Role']) ?>
                    </span>
                </td>
                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= htmlspecialchars($e['Address']) ?>">
                    <?= htmlspecialchars(strlen($e['Address']) > 40 ? substr($e['Address'], 0, 40) . '...' : $e['Address']) ?>
                </td>
                <td style="font-family: monospace; font-size: 13px; color: var(--gray);">
                    <?= htmlspecialchars($e['Phones'] ?? 'N/A') ?>
                </td>
                <td>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <a href="employee.php?edit=<?= (int)$e['Emp_ID'] ?>"
                           class="btn btn-light btn-sm"
                           style="background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb;">
                            ✏️ Edit
                        </a>
                        <a href="employee.php?delete=<?= (int)$e['Emp_ID'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('⚠️ Are you sure you want to delete this employee?\\n\\nEmployee: <?= htmlspecialchars($e['Name']) ?>\\nRole: <?= htmlspecialchars($e['Role']) ?>')">
                            🗑️ Delete
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.7); border-radius: 16px; margin-top: 30px;">
        <div style="font-size: 4rem; margin-bottom: 20px;">👥</div>
        <h3 style="color: var(--dark-green); margin: 0 0 10px 0;">No Employees Found</h3>
        <p style="color: var(--gray); margin: 0;">Add your first employee using the form above.</p>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
