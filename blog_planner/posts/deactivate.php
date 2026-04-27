<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../auth/login.php'); exit(); }
require_once '../config/db.php';
$postId=(int)($_GET['id']??0);
if($postId>0){
    $conn=getConnection();$stmt=$conn->prepare("UPDATE blog_posts SET status='deactivated' WHERE id=? AND user_id=?");
    $userId=(int)$_SESSION['user_id'];$stmt->bind_param('ii',$postId,$userId);$stmt->execute();
}
header('Location: read.php');exit();
