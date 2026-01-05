<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions_reservation.php';

if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === 'accept') updateReservationStatus($pdo, $id, 'accepted');
    if ($action === 'refuse') updateReservationStatus($pdo, $id, 'refused');
    if ($action === 'cancel') updateReservationStatus($pdo, $id, 'cancelled');

    header("Location: reservations.php");
    exit;
}

$reservations = getAllReservations($pdo);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Admin - Réservations</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h1>Admin - Réservations</h1>

<p>
  <a href="index.php">Dashboard</a> |
  <a href="velos.php">Vélos</a> |
  <a href="../public/index.php">Retour au site</a>
</p>

<table>
  <tr>
    <th>ID</th><th>Email</th><th>Vélo</th><th>Début</th><th>Fin</th><th>Total</th><th>Status</th><th>Actions</th>
  </tr>
  <?php foreach($reservations as $r): ?>
    <tr>
      <td><?= (int)$r['id'] ?></td>
      <td><?= htmlspecialchars($r['client_email']) ?></td>
      <td><?= htmlspecialchars($r['velo_name']) ?></td>
      <td><?= htmlspecialchars($r['start_date']) ?></td>
      <td><?= htmlspecialchars($r['end_date']) ?></td>
      <td><?= htmlspecialchars($r['total_price']) ?>€</td>
      <td><?= htmlspecialchars($r['status']) ?></td>
      <td>
        <a href="?action=accept&id=<?= (int)$r['id'] ?>">Accepter</a> |
        <a href="?action=refuse&id=<?= (int)$r['id'] ?>">Refuser</a> |
        <a href="?action=cancel&id=<?= (int)$r['id'] ?>">Annuler</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
</body>
</html>
