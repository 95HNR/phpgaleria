<?php
const TARHELY = __DIR__ . '/../tarhely';

$keres = $_GET['fajlnev'] ?? '';
// Az aláhúzás engedélyezése a reguláris kifejezésben a 'kicsi_' prefix miatt
$tisztitott_nev = preg_replace('/[^a-zA-Z0-9._]/', '', $keres); 

$fajlut = TARHELY . '/' . $tisztitott_nev;

if (empty($tisztitott_nev) || !file_exists($fajlut)) {
    http_response_code(404);
    exit('Kep nem talalhato.');
}

// A MIME-típus dinamikus meghatározása a fájl tartalmából (így a WebP és JPEG is helyesen lesz kiszolgálva)
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($fajlut);

// Biztonsagi fejlecek es kiszolgalas
header('Content-Type: ' . $mime);
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'none'; sandbox");
header('Cache-Control: public, max-age=31536000, immutable');
readfile($fajlut);