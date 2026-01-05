<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions_reservation.php';

$email = $_GET['email'] ?? '';
$reservations = [];

if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $reservations = getReservationsByEmail($pdo, $email);
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Mes réservations</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<a href="index.php">← Retour catalogue</a>
<h1>Mes réservations</h1>

<form method="GET">
  <label>Entre ton email</label>
  <input type="email" name="email" required value="<?= htmlspecialchars($email) ?>">
  <button type="submit">Voir</button>
</form>

<?php if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)): ?>
  <p class="notice">Email invalide.</p>
<?php endif; ?>

<?php if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)): ?>
  <h2>Résultats pour : <?= htmlspecialchars($email) ?></h2>

  <?php if (!$reservations): ?>
    <p>Aucune réservation trouvée.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>ID</th><th>Vélo</th><th>Début</th><th>Fin</th><th>Total</th><th>Status</th><th>Créée le</th>
      </tr>
      <?php foreach ($reservations as $r): ?>
        <tr>
          <td><?= (int)$r['id'] ?></td>
          <td><?= htmlspecialchars($r['velo_name']) ?></td>
          <td><?= htmlspecialchars($r['start_date']) ?></td>
          <td><?= htmlspecialchars($r['end_date']) ?></td>
          <td><?= htmlspecialchars($r['total_price']) ?>€</td>
          <td><?= htmlspecialchars($r['status']) ?></td>
          <td><?= htmlspecialchars($r['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
<?php endif; ?>
</body>
</html>
