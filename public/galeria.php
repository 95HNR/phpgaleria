<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Fotopalyazat 2026</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 2rem auto; }
        .urlap { border: 2px dashed #ccc; padding: 1rem; margin-bottom: 2rem; }
        .galeria { display: flex; gap: 1rem; flex-wrap: wrap; }
        .kep-kartya { border: 1px solid #eee; padding: 0.5rem; text-align: center; }
        img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
    <h1>Fotopalyazat 2026 - Biztonsagos galeria</h1>

    <div class="urlap">
        <form action="/feltoltes.php" method="POST" enctype="multipart/form-data">
            <p>
                <label>Cim:</label><br>
                <input type="text" name="cim" required placeholder="pl. Napfelkelte a kampusz felett">
            </p>
            <p>
                <label>Kep (JPEG, PNG, WebP; max. 2 MB):</label><br>
                <input type="file" name="kep" accept=".jpg,.jpeg,.png,.webp" required>
            </p>
            <button type="submit">Feltoltes</button>
        </form>
    </div>

    <h2>Feltoltott kepek</h2>
    <div class="galeria">
        <?php
        $tarhely = __DIR__ . '/../tarhely';
        if (is_dir($tarhely)) {
            $fajlok = scandir($tarhely);
            foreach ($fajlok as $fajl) {
                // Csak a generalt belyegkepeket listazzuk
                if (str_starts_with($fajl, 'kicsi_')) {
                    $eredeti = str_replace('kicsi_', '', $fajl);
                    echo '<div class="kep-kartya">';
                    // A kepeket a kep.php scripten keresztul toltjuk be a biztonsagos fejlecekkel
                    echo '<img src="/kep.php?fajlnev=' . urlencode($fajl) . '" alt="Belyegkep"><br>';
                    echo '<a href="/kep.php?fajlnev=' . urlencode($eredeti) . '" target="_blank">Eredeti</a>';
                    echo '</div>';
                }
            }
        }
        ?>
    </div>
</body>
</html>