<?php
const TARHELY = __DIR__ . '/../tarhely';

$keres = $_GET['fajlnev'] ?? '';
// alahuzasok, pontok, betuk, szamok engedelyezese a fajlnevben, minden mas karakter eltavolitasa
$tisztitott_nev = preg_replace('/[^a-zA-Z0-9._]/', '', $keres); 

// a fajl teljes eleresi utja
$fajlut = TARHELY . '/' . $tisztitott_nev;

// a fajl letezik-e, es a fajlnev nem ures
if (empty($tisztitott_nev) || !file_exists($fajlut)) {
    http_response_code(404);
    exit('Kep nem talalhato.');
}

// A MIME tipus meghatarozasa a fajl tartalma alapjan, nem a kiterjesztes alapjan, hogy elkeruljuk a MIME spoofingot
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($fajlut);

// biztonsagi fejlecek es kiszolgalas
header('Content-Type: ' . $mime);
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'none'; sandbox");
header('Cache-Control: public, max-age=31536000, immutable');
readfile($fajlut);