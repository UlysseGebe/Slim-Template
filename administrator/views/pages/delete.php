<?php
include '../app/config.php';
if(isset($_GET['id'])) {
    $suppr_id = htmlspecialchars($_GET['id']);
    $suppr = $pdo->prepare('DELETE FROM expenses WHERE id = ?');
    $suppr->execute(array($suppr_id));
    header('Location: My-Space');
    exit;
}