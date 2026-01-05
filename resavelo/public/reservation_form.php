<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions_velos.php';
require_once __DIR__ . '/../includes/functions_reservation.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$velo = getVeloById($pdo, $id);
if (!$velo) die("Vélo introuvable");

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_email = $_POST['client_email'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    $result = createReservation($pdo, $id, $client_email, $start_date, $end_date);
    $message = $result['message'];
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Réserver - <?= htmlspecialchars($velo['name']) ?></title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<a href="index.php">← Retour</a>
<h1>Réserver : <?= htmlspecialchars($velo['name']) ?></h1>

<?php if ($message): ?>
  <p class="notice"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
  <label>Ton email (pour retrouver tes réservations)</label>
  <input type="email" name="client_email" required>

  <label>Date début</label>
  <input type="date" name="start_date" required>

  <label>Date fin</label>
  <input type="date" name="end_date" required>

  <button type="submit">Envoyer la demande</button>
</form>
</body>
</html>
