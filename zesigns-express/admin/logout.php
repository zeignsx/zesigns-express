<?php
session_start();
require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    $db = db();
    
    // Log activity
    $log = $db->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address) 
        VALUES (:user_id, 'logout', 'User logged out', :ip)");
    $log->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $log->bindValue(':ip', $_SERVER['REMOTE_ADDR'], SQLITE3_TEXT);
    $log->execute();
    
    // Clear remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        $stmt = $db->prepare("DELETE FROM sessions WHERE session_token = :token");
        $stmt->bindValue(':token', $_COOKIE['remember_token'], SQLITE3_TEXT);
        $stmt->execute();
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

// Destroy session
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirect to login with message
header('Location: login.php?message=logged_out');
exit();
?>