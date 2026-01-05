<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions_velos.php';

if (isset($_GET['delete'])) {
    deleteVelo($pdo, (int)$_GET['delete']);
    header("Location: velos.php");
    exit;
}

$velos = getAllVelos($pdo);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Admin - Vélos</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h1>Admin - Vélos</h1>

<p>
  <a href="index.php">Dashboard</a> |
  <a href="reservations.php">Réservations</a> |
  <a href="../public/index.php">Retour au site</a>
</p>

<a class="btn" href="velo_form.php">+ Ajouter</a>

<table>
  <tr><th>ID</th><th>Nom</th><th>Prix/j</th><th>Qté</th><th>Actions</th></tr>
  <?php foreach($velos as $v): ?>
    <tr>
      <td><?= (int)$v['id'] ?></td>
      <td><?= htmlspecialchars($v['name']) ?></td>
      <td><?= htmlspecialchars($v['price']) ?>€</td>
      <td><?= (int)$v['quantity'] ?></td>
      <td>
        <a href="velo_form.php?id=<?= (int)$v['id'] ?>">Modifier</a>
        |
       <a href="velos.php?delete=<?= $v['id'] ?>" class="delete-btn">
            Supprimer
        </a>

      </td>
    </tr>
  <?php endforeach; ?>
</table>
</body>
</html>
