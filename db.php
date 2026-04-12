<?php
// 本地 XAMPP 默认配置
$host = 'localhost';          // 本地主机
$dbname = 'expense_tracker';   // 你在本地 phpMyAdmin 创建的数据库名
$user = 'root';               // XAMPP 默认用户名
$pass = '';                   // XAMPP 默认密码为空

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("数据库连接失败: " . $e->getMessage());
}
?>