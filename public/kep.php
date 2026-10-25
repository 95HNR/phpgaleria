<?php
// Egy valodi alkalmazasban itt lekerdezned a DB-t a $_GET['id'] alapjan:
// $kep = pdo_query('SELECT fajlnev, mime FROM kep WHERE id = ?', $_GET['id']);

// Most egy szimulalt adattal dolgozunk a pelda kedveert:
$keres = $_GET['fajlnev'] ?? '';
// Csak betuk, szamok es a pont engedelyezese a fajlnevben a directory traversal ellen
$tisztitott_nev = preg_replace('/[^a-zA-Z0-9.]/', '', $keres); 

$kep = [
    'fajlnev' => $tisztitott_nev,
    'mime'    => 'image/jpeg' // Valosagban ez is a DB-bol jonne
];

const TARHELY = __DIR__ . '/../tarhely';
$fajlut = TARHELY . '/' . $kep['fajlnev'];

if (empty($tisztitott_nev) || !file_exists($fajlut)) {
    http_response_code(404);
    exit('Kep nem talalhato.');
}

// Biztonsagi fejlecek es kiszolgalas
header('Content-Type: ' . $kep['mime']);
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'none'; sandbox");
header('Cache-Control: public, max-age=31536000, immutable');
readfile($fajlut);