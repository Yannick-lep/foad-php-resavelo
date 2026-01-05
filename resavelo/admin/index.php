<?php
require_once __DIR__ . '/../config/db_connect.php';

// 1) total vélos
$nbVelos = (int)$pdo->query("SELECT COUNT(*) FROM velos")->fetchColumn();

// 2) total réservations
$nbResa = (int)$pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();

// 3) répartition par statut
$statsStatus = $pdo->query("
    SELECT status, COUNT(*) as nb
    FROM reservations
    GROUP BY status
")->fetchAll();

// 4) chiffre d'affaires (accepted)
$ca = (float)$pdo->query("
    SELECT COALESCE(SUM(total_price), 0)
    FROM reservations
    WHERE status = 'accepted'
")->fetchColumn();

// 5) top vélos (les plus réservés)
$top = $pdo->query("
    SELECT v.name, COUNT(*) as nb
    FROM reservations r
    JOIN velos v ON v.id = r.velo_id
    GROUP BY r.velo_id
    ORDER BY nb DESC
    LIMIT 5
")->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Admin - Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h1>Dashboard RESAVELO</h1>

<p><a href="velos.php">Gérer les vélos</a> | <a href="reservations.php">Gérer les réservations</a></p>

<div class="grid">
  <div class="card">
    <h2>Vélos</h2>
    <p>Total : <b><?= $nbVelos ?></b></p>
  </div>

  <div class="card">
    <h2>Réservations</h2>
    <p>Total : <b><?= $nbResa ?></b></p>
  </div>

  <div class="card">
    <h2>Chiffre d’affaires</h2>
    <p>Accepted : <b><?= number_format($ca, 2) ?>€</b></p>
  </div>

  <div class="card">
    <h2>Par statut</h2>
    <?php if (!$statsStatus): ?>
      <p>Aucune donnée</p>
    <?php else: ?>
      <ul>
        <?php foreach ($statsStatus as $s): ?>
          <li><?= htmlspecialchars($s['status']) ?> : <b><?= (int)$s['nb'] ?></b></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>

<h2>Top 5 vélos les plus réservés</h2>
<table>
  <tr><th>Vélo</th><th>Nb réservations</th></tr>
  <?php foreach ($top as $t): ?>
    <tr>
      <td><?= htmlspecialchars($t['name']) ?></td>
      <td><?= (int)$t['nb'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<script src="../assets/js/app.js"></script>

</body>
</html>
