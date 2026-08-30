<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../assets/config-data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo '<script>
            alert("Method Not Allowed.");
            window.location.href = "../dashboard.php";
          </script>';
    exit;
}

if (empty($_POST['changepass']) || strlen(trim($_POST['changepass'])) < 6) {
    echo '<script>
            alert("Password must be at least 6 characters long.");
            window.location.href = "../dashboard.php";
          </script>';
    exit;
}

$newpass = $_POST['changepass'];
$pass = password_hash($newpass, PASSWORD_DEFAULT);
$userId = (int)$_SESSION['id'];

if ($stmt = $data->prepare("UPDATE lomba_credential SET password = ? WHERE id = ?")) {
    $stmt->bind_param("si", $pass, $userId);
    $stmt->execute();

    if ($stmt->affected_rows >= 0) {
        echo '<script>
                alert("Password Successfully Changed!");
                window.location.href = "../dashboard.php";
              </script>';
    } else {
        echo '<script>
                alert("Error updating password.");
                window.location.href = "../dashboard.php";
              </script>';
    }
    $stmt->close();
} else {
    echo '<script>
            alert("Database Error.");
            window.location.href = "../dashboard.php";
          </script>';
}
?>