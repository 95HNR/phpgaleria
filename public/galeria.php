<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Fotópályázat 2026 - Galéria</title>
    <style>
        /* root css valtozok a szinpaletta es a tema beallitasahoz */
        :root {
            --primary: #4f46e5;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-color: #1f2937;
        }
        /* alapveto stilusok a testreszabott megjeleneshez */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        /*a fejlec es a cimek stilusai*/
        h1, h2 {
            color: #111827;
        }
        /*a feltoltesi urlap es a gombok stilusai*/
        .urlap {
            background: var(--card-bg);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
        }
        /*a form elemek es a gombok stilusai*/
        .urlap input[type="text"], .urlap input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-sizing: border-box;
        }
        /*a gomb stilusai es az interakcios hatasok*/
        .urlap button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        /*a gomb hover hatasa*/
        .urlap button:hover {
            background-color: #4338ca;
        }
        /*a galeria es a kepkartya stilusai*/
        .galeria {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.5rem;
        }
        /*a kepkartya es a kep kontener stilusai, beleertve a hover hatast is*/
        .kep-kartya {
            background: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0.75rem;
        }
        /*a kepkartya hover hatasa*/
        .kep-kartya:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        /*a kep kontener es a kep stilusai, beleertve az object-fit beallitasat is*/
        .kep-kontener {
            width: 100%;
            height: 180px;
            overflow: hidden;
            border-radius: 6px;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /*a kep stilusai, beleertve az object-fit beallitasat is*/
        .kep-kontener img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        /*a kepkartya lab reszenek stilusai, beleertve a link stilusait is*/
        .kartya-lab {
            margin-top: 0.75rem;
            text-align: center;
        }
        /*a link stilusai a kepkartya lab reszen belul*/
        .kartya-lab a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }
        /*a link hover hatasa a kepkartya lab reszen belul*/
        .kartya-lab a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Fotópályázat 2026</h1>
    <p>Biztonságos képfeltöltő galéria rendszer.</p>

    <div class="urlap">
        <h2>Új kép feltöltése</h2>
        <form action="/feltoltes.php" method="POST" enctype="multipart/form-data">
            <label><strong>Cím:</strong></label>
            <input type="text" name="cim" required placeholder="pl. Napfelkelte a kampusz felett">
            
            <label><strong>Kép (JPEG, PNG, WebP; max. 2 MB):</strong></label>
            <input type="file" name="kep" accept=".jpg,.jpeg,.png,.webp" required>
            
            <button type="submit">Feltöltés indítása</button>
        </form>
    </div>

    <h2>Feltöltött képek</h2>
    <div class="galeria">
        <?php
        // a tarhely mappa eleresi utja
        $tarhely = __DIR__ . '/../tarhely';
        if (is_dir($tarhely)) {
            $fajlok = scandir($tarhely);
            $talalat = false;
            foreach ($fajlok as $fajl) {
                if (str_starts_with($fajl, 'kicsi_')) {
                    $talalat = true;
                    $eredeti = str_replace('kicsi_', '', $fajl);
                    echo '<div class="kep-kartya">';
                    echo '<div class="kep-kontener">';
                    echo '<img src="/kep.php?fajlnev=' . urlencode($fajl) . '" alt="Bélyegkép">';
                    echo '</div>';
                    echo '<div class="kartya-lab">';
                    echo '<a href="/kep.php?fajlnev=' . urlencode($eredeti) . '" target="_blank">Eredeti megtekintése</a>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            // ha nincs feltoltott kep, akkor egy uzenetet jelenitunk meg a galeria reszen
            if (!$talalat) {
                echo '<p style="color: #6b7280; grid-column: 1 / -1;">Még nincsenek feltöltött képek.</p>';
            }
        }
        ?>
    </div>
</body>
</html>