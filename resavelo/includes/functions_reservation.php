<?php
require_once __DIR__ . '/functions_calculation.php';

function checkAvailability(PDO $pdo, int $velo_id, string $start_date, string $end_date): bool {
    $stmt = $pdo->prepare("SELECT quantity FROM velos WHERE id = ?");
    $stmt->execute([$velo_id]);
    $velo = $stmt->fetch();
    if (!$velo) return false;

    $quantity = (int)$velo['quantity'];

    $sql = "SELECT COUNT(*) as nb
            FROM reservations
            WHERE velo_id = :velo_id
              AND status = 'accepted'
              AND start_date < :end_date
              AND end_date > :start_date";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':velo_id' => $velo_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date,
    ]);

    $nb = (int)$stmt->fetch()['nb'];

    return $nb < $quantity;
}

function createReservation(PDO $pdo, int $velo_id, string $client_email, string $start_date, string $end_date): array {
    $client_email = trim($client_email);

    if (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'message' => "Email invalide"];
    }
    if (empty($start_date) || empty($end_date)) {
        return ['ok' => false, 'message' => "Dates obligatoires"];
    }
    if ($start_date >= $end_date) {
        return ['ok' => false, 'message' => "La date de fin doit être après la date de début"];
    }

    if (!checkAvailability($pdo, $velo_id, $start_date, $end_date)) {
        return ['ok' => false, 'message' => "Désolé, ce vélo n'est pas disponible sur ces dates"];
    }

    $stmt = $pdo->prepare("SELECT price FROM velos WHERE id = ?");
    $stmt->execute([$velo_id]);
    $velo = $stmt->fetch();
    if (!$velo) return ['ok' => false, 'message' => "Vélo introuvable"];

    $total = calculatePrice((float)$velo['price'], $start_date, $end_date);
    if ($total <= 0) return ['ok' => false, 'message' => "Durée invalide (0 jour)"];

    $sql = "INSERT INTO reservations (velo_id, client_email, start_date, end_date, total_price, status)
            VALUES (:velo_id, :client_email, :start_date, :end_date, :total_price, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':velo_id' => $velo_id,
        ':client_email' => $client_email,
        ':start_date' => $start_date,
        ':end_date' => $end_date,
        ':total_price' => $total,
    ]);

    return ['ok' => true, 'message' => "Réservation créée (en attente de validation).", 'total' => $total];
}

function getAllReservations(PDO $pdo): array {
    $sql = "SELECT r.*, v.name AS velo_name
            FROM reservations r
            JOIN velos v ON v.id = r.velo_id
            ORDER BY r.created_at DESC";
    return $pdo->query($sql)->fetchAll();
}

function getReservationsByEmail(PDO $pdo, string $email): array {
    $sql = "SELECT r.*, v.name AS velo_name
            FROM reservations r
            JOIN velos v ON v.id = r.velo_id
            WHERE r.client_email = ?
            ORDER BY r.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetchAll();
}

function updateReservationStatus(PDO $pdo, int $id, string $status): bool {
    $allowed = ['pending','accepted','refused','cancelled'];
    if (!in_array($status, $allowed, true)) return false;

    $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
}

function cancelReservation(PDO $pdo, int $id): bool {
    return updateReservationStatus($pdo, $id, 'cancelled');
}
