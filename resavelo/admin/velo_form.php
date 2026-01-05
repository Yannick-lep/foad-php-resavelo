<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions_velos.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $id > 0;

$velo = [
    'name' => '',
    'price' => '',
    'quantity' => '',
    'description' => '',
    'image_url' => '',
];

if ($editing) {
    $found = getVeloById($pdo, $id);
    if (!$found) die("Vélo introuvable");
    $velo = $found;
}

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'price' => (float)($_POST['price'] ?? 0),
        'quantity' => (int)($_POST['quantity'] ?? 0),
        'description' => trim($_POST['description'] ?? ''),
        'image_url' => trim($_POST['image_url'] ?? ''),
    ];

    if ($data['name'] === '' || $data['price'] <= 0 || $data['quantity'] < 0) {
        $message = "Nom obligatoire, prix > 0, quantité >= 0";
    } else {
        if ($editing) {
            updateVelo($pdo, $id, $data);
            header("Location: velos.php");
            exit;
        } else {
            addVelo($pdo, $data);
            header("Location: velos.php");
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title><?= $editing ? "Modifier" : "Ajouter" ?> un vélo</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<a href="velos.php">← Retour liste vélos</a>
<h1><?= $editing ? "Modifier" : "Ajouter" ?> un vélo</h1>

<?php if ($message): ?>
  <p class="notice"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
  <label>Nom</label>
  <input type="text" name="name" required value="<?= htmlspecialchars($velo['name'] ?? '') ?>">

  <label>Prix / jour (€)</label>
  <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars($velo['price'] ?? '') ?>">

  <label>Quantité disponible</label>
  <input type="number" name="quantity" required value="<?= htmlspecialchars($velo['quantity'] ?? '') ?>">

  <label>Description</label>
  <textarea name="description" rows="4"><?= htmlspecialchars($velo['description'] ?? '') ?></textarea>

  <label>URL image (optionnel)</label>
  <input type="text" name="image_url" value="<?= htmlspecialchars($velo['image_url'] ?? '') ?>">

  <button type="submit"><?= $editing ? "Enregistrer" : "Ajouter" ?></button>
</form>
</body>
</html>
