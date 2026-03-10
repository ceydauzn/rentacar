<?php
session_start();
require 'db_naglanti.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $error = '';

    if (empty($username) || empty($password)) {
        $error = "Lütfen tüm alanları doldurun.";
    } else {
        // SQL sorgusunu hazırlıyoruz. PDO, parametreleri kullanarak güvenli sorgu sağlar.
        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        // Adminin var olup olmadığını ve şifrenin eşleşip eşleşmediğini kontrol ediyoruz.
        // Gerçek projede password_hash() ve password_verify() kullanmalısınız.
        if ($admin && $password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['loggedin'] = true;
            header('Location: dashboard.php'); // Başarılı giriş sonrası yönlendirme
            exit;
        } else {
            $error = "Hatalı kullanıcı adı veya şifre.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Girişi</title>
</head>
<body>
    <h2>Admin Paneli Girişi</h2>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        Kullanıcı Adı: <input type="text" name="username"><br>
        Şifre: <input type="password" name="password"><br><br>
        <button type="submit">Giriş Yap</button>
    </form>
</body>
</html>