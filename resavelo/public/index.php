<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions_velos.php';
require_once __DIR__ . '/../includes/functions_reservation.php';

$maxPrice = isset($_GET['maxPrice']) && $_GET['maxPrice'] !== '' ? (float)$_GET['maxPrice'] : null;
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$velos = getAllVelos($pdo);

// Filtre prix
if ($maxPrice !== null) {
    $velos = array_filter($velos, fn($v) => (float)$v['price'] <= $maxPrice);
}

// Filtre disponibilité si dates valides
$useAvailabilityFilter = ($start_date !== '' && $end_date !== '' && $start_date < $end_date);
if ($useAvailabilityFilter) {
    $velos = array_filter($velos, function($v) use ($pdo, $start_date, $end_date) {
        return checkAvailability($pdo, (int)$v['id'], $start_date, $end_date);
    });
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>RESAVELO - Catalogue</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h1>Catalogue des vélos</h1>

<p>
  <a href="mes_reservations.php">Mes réservations</a>
  &nbsp;|&nbsp;
  <a href="../admin/index.php">Admin</a>
</p>

<form method="GET">
  <label>Prix max :</label>
  <input type="number" step="0.01" name="maxPrice" value="<?= htmlspecialchars($_GET['maxPrice'] ?? '') ?>">

  <label>Date début :</label>
  <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">

  <label>Date fin :</label>
  <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">

  <button type="submit">Filtrer</button>
  <a href="index.php">Reset</a>
</form>

<?php if (($start_date !== '' || $end_date !== '') && !$useAvailabilityFilter): ?>
  <p class="notice">Pour filtrer la disponibilité, remplis deux dates valides (début < fin).</p>
<?php endif; ?>

<div class="grid">
  <?php foreach ($velos as $velo): ?>
    <div class="card">
      <h2><?= htmlspecialchars($velo['name']) ?></h2>

      <?php if (!empty($velo['image_url'])): ?>
        <img src="<?= htmlspecialchars($velo['image_url']) ?>" alt="Vélo" class="card-img">
      <?php endif; ?>

      <p><?= htmlspecialchars($velo['description'] ?? '') ?></p>
      <p><b><?= htmlspecialchars($velo['price']) ?>€</b> / jour</p>
      <p>Quantité totale: <?= (int)$velo['quantity'] ?></p>

      <a class="btn" href="reservation_form.php?id=<?= (int)$velo['id'] ?>">Réserver</a>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
