<?php
// Emergency admin password reset.
// SECURITY: this used to be a public URL that reset the admin password to a fixed
// value — anyone who knew the URL could take over the account. It now requires an
// authenticated admin/pastor. If you are locked out, use the "Forgot Password"
// page (email link or security question) on the sign-in screen instead.
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json');

$user = authenticate();
if (!in_array($user['role'] ?? '', ['admin', 'pastor'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Admins only.']);
    exit;
}

$db = getDB();
$newPass = 'Admin123!';
$hash = password_hash($newPass, PASSWORD_BCRYPT);
$stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = 1");
$stmt->execute([$hash]);
echo json_encode(['success' => true, 'message' => 'Admin (user #1) password reset to Admin123!. Change it right away under Settings > My Login & Recovery.', 'rows' => $stmt->rowCount()]);
