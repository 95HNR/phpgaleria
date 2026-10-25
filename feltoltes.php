<?php
const MAX_MERET = 2 * 1024 * 1024; // 2 MB

if (empty($_POST) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    http_response_code(413);
    exit('A keres tul nagy.'); // post_max_size tullepese
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f = $_FILES['kep'] ?? null;
    
    if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400); 
        exit('Hiba a feltoltes soran.');
    }
    if ($f['size'] > MAX_MERET) {
        http_response_code(413); 
        exit('A fajl legfeljebb 2 MB lehet.');
    }
    const ENGEDETT = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if (!isset(ENGEDETT[$mime])) {
        http_response_code(415);
        exit('Csak JPEG, PNG vagy WebP kep toltheto fel (felismert tipus: ' . $mime . ').');
    }

    $meretek = @getimagesize($f['tmp_name']);
    if (!$meretek || $meretek[0] > 6000 || $meretek[1] > 6000) {
        http_response_code(422); 
        exit('Ervenytelen kep vagy tul nagy felbontas.');
    }
}