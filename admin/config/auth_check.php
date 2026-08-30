<?php
/**
 * Central Authentication & Access Control Guard
 * Ensures only authenticated administrators can execute administrative actions.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['sukseslogin']) || $_SESSION['sukseslogin'] !== true) {
    http_response_code(403);
    echo '<script>
            alert("Unauthorized access! Please log in first.");
            window.location.href = "../index.php";
          </script>';
    exit;
}
