<?php

function calculatePrice(float $price_per_day, string $start_date, string $end_date): float {
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);

    $days = (int)$start->diff($end)->days;
    if ($days <= 0) return 0;

    return round($days * $price_per_day, 2);
}
