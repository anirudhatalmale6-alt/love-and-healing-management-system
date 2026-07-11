<?php
require_once __DIR__ . '/config.php';
$db = getDB();
$users = $db->query("SELECT id, email, name, role, status FROM users")->fetchAll();
$members = $db->query("SELECT id, first_name, last_name, email, status FROM members LIMIT 10")->fetchAll();
echo json_encode(['users' => $users, 'members' => $members, 'user_count' => count($users), 'member_count' => count($members)]);
