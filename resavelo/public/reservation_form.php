<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions_velos.php';
require_once __DIR__ . '/../includes/functions_reservation.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$velo = getVeloById($pdo, $id);
if (!$velo) die("vélo introuvable");

$message = null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_email = $_POST['client_email'] ?? '';
    $start_date = $_POST['client_email'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    $result = createReservation($pdo, $id, $client_email, $start_date, $end_date);
    $message = $result['message'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver - <?= htmlspecialchars($velo['name']) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <a href="index.php">Retour</a>
    <h1>Réserver : <?= htmlspecialchars($message) ?></h1>

    <?php if ($message): ?>
        <p class="notice"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label> Ton email (pour retrouver tes réservations)</label>
        <input type="email" name="email" required>

        <label>Date début</label>
        <input type="date" name="date début" required>

        <label>Date fin</label>
        <input type="date" name="date fin" required>

        <button type="submit">Envoyer la demande</button>
    </form>
</body>
</html>