<?php
session_start();
require 'db.php';

// Oturum kontrolü: Eğer admin giriş yapmamışsa, giriş sayfasına yönlendir.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// SQL sorgusu ile kiralanan arabaları çekme
// INNER JOIN kullanarak cars, rentals ve admins tablolarını birleştiriyoruz
try {
    $stmt = $pdo->prepare("
        SELECT 
            c.ad, 
            c.model, 
            c.plaka,
            r.baslangic_tarihi,
            r.bitis_tarihi,
            u.ad AS rented_by
        FROM rentals r
        INNER JOIN cars c ON r.car_id = c.car_id
        ORDER BY r.start_date DESC
    ");
    $stmt->execute();
    $rented_cars = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Sorgu hatası: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Paneli</title>
</head>
<body>
    <h2>Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p><a href="logout.php">Çıkış Yap</a></p>

    <h3>Kiralanan Arabalar</h3>
    
    <?php if (!empty($rented_cars)): ?>
    <table border="1">
        <thead>
            <tr>
                <th>ad</th>
                <th>Model</th>
                <th>plaka</th>
                <th>yakit</th>
                <th>gunluk_fiyat</th>
                <th>musaitlik</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rented_cars as $car): ?>
            <tr>
                <td><?php echo htmlspecialchars($car['ad']); ?></td>
                <td><?php echo htmlspecialchars($car['model']); ?></td>
                <td><?php echo htmlspecialchars($car['plaka']); ?></td>
                <td><?php echo htmlspecialchars($car['yakit']); ?></td>
                <td><?php echo htmlspecialchars($car['gunluk_fiyat']); ?></td>
                <td><?php echo htmlspecialchars($car['musait_mi']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>Henüz kiralanmış araba bulunmamaktadır.</p>
    <?php endif; ?>

</body>
</html>