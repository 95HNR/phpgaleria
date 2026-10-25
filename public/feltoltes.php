<?php
const MAX_MERET = 2 * 1024 * 1024; // 2 MB
const ENGEDETT = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
const TARHELY = __DIR__ . '/../tarhely';

// a POST meretenek ellenorzese, hogy ne legyen tul nagy a keres
if (empty($_POST) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    http_response_code(413);
    exit('A keres tul nagy.'); // post_max_size tullepese
}
// a feltoltott fajl feldolgozasa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f = $_FILES['kep'] ?? null;
    // a feltoltott fajl letezik-e es nincs hiba a feltoltes soran
    if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400); 
        exit('Hiba a feltoltes soran.');
    }
    // a feltoltott fajl meretenek ellenorzese
    if ($f['size'] > MAX_MERET) {
        http_response_code(413); 
        exit('A fajl legfeljebb 2 MB lehet.');
    }
    // a MIME tipus meghatarozasa a feltoltott fajl tartalma alapjan, nem a kiterjesztes alapjan, hogy elkeruljuk a MIME spoofingot
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if (!isset(ENGEDETT[$mime])) {
        http_response_code(415);
        exit('Csak JPEG, PNG vagy WebP kep toltheto fel (felismert tipus: ' . $mime . ').');
    }
    // a kep mereteinek ellenorzese, hogy ne legyen tul nagy felbontas
    $meretek = @getimagesize($f['tmp_name']);
    if (!$meretek || $meretek[0] > 6000 || $meretek[1] > 6000) {
        http_response_code(422); 
        exit('Ervenytelen kep vagy tul nagy felbontas.');
    }
    // a feltoltott fajl uj, egyedi nevet kap, hogy elkeruljuk az utkozeseket
    $nev = bin2hex(random_bytes(16)) . '.' . ENGEDETT[$mime];
    $cel = TARHELY . '/' . $nev;
    
    // a feltoltott fajl atmozgatasa a tarhely mappaba
    if (!move_uploaded_file($f['tmp_name'], $cel)) {
        http_response_code(500); 
        exit('A fajl mentese nem sikerult.');
    }
    // a feltoltes soran keszitett kicsinyitett kep mentese
    chmod($cel, 0640);
    thumbnail($cel, TARHELY . '/kicsi_' . $nev, $mime);

    header('Location: /galeria.php', true, 303);
    exit;
}

// a feltoltes soran keszitett kicsinyitett kep mentese
function thumbnail(string $forras, string $cel, string $mime, int $szel = 320): void {
    $kep = match ($mime) {
        'image/jpeg' => imagecreatefromjpeg($forras),
        'image/png'  => imagecreatefrompng($forras),
        'image/webp' => imagecreatefromwebp($forras),
    };
    $kicsi = imagescale($kep, $szel);
    imagewebp($kicsi, $cel, 80);
}