<?php

function getAllVelos(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM velos ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

function getVeloById(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT * FROM velos WHERE id = ?");
    $stmt->execute([$id]);
    $velo = $stmt->fetch();
    return $velo ?: null;
}

function addVelo(PDO $pdo, array $data): bool {
    $sql = "INSERT INTO velos (name, price, quantity, description, image_url)
            VALUES (:name, :price, :quantity, :description, :image_url)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':name' => $data['name'],
        ':price' => $data['price'],
        ':quantity' => $data['quantity'],
        ':description' => $data['description'] ?? null,
        ':image_url' => $data['image_url'] ?? null,
    ]);
}

function updateVelo(PDO $pdo, int $id, array $data): bool {
    $sql = "UPDATE velos
            SET name = :name,
                price = :price,
                quantity = :quantity,
                description = :description,
                image_url = :image_url
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':name' => $data['name'],
        ':price' => $data['price'],
        ':quantity' => $data['quantity'],
        ':description' => $data['description'] ?? null,
        ':image_url' => $data['image_url'] ?? null,
    ]);
}

function deleteVelo(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare("DELETE FROM velos WHERE id = ?");
    return $stmt->execute([$id]);
}
