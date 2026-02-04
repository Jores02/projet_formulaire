<?php
// Connexion PDO à MySQL
try {
    $host = getenv('MYSQL_HOST') ?: 'mysql';
    $dbname = getenv('MYSQL_DATABASE') ?: 'tdR606';
    $user = getenv('MYSQL_USER') ?: 'root';
    $password = getenv('MYSQL_PASSWORD') ?: 'rootpassword';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Si le formulaire est soumis
$randomValue = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère tous les champs qui commencent par "field"
    $fields = [];

    for ($i = 1; $i <= 10; $i++) {
        $name = "field$i";
        $value = isset($_POST[$name]) ? trim($_POST[$name]) : '';
        $fields[$name] = $value;
    }

    // On ne garde que les champs non vides
    $nonEmpty = array_filter($fields, fn($v) => $v !== '');

    if (!empty($nonEmpty)) {
        // On choisit une clé au hasard
        $randomKey = array_rand($nonEmpty);
        $randomValue = $nonEmpty[$randomKey];

        // Enregistrer le tirage dans la base de données
        try {
            $stmt = $pdo->prepare("INSERT INTO tirages (valeur_tiree, date_tirage) VALUES (?, NOW())");
            $stmt->execute([$randomValue]);
        } catch (PDOException $e) {
            echo "Erreur d'enregistrement : " . $e->getMessage();
        }
    } else {
        $randomValue = "Tous les champs sont vides.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Formulaire 10 champs</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Formulaire avec 10 champs texte</h1>
    <p>
        Liste des developpeur :
    <ol>
        <li>Amen AHOUANDOGBO</li>
        <li>Romain DURAND</li>
        <li>Y manque pas qqun ?</li>
        <li>Tom HUBERT - Test (à moitié)</li>
        <li>ethanhugerot</li>
        <li>Mathieu DUCROT (celui qui gère)</li>
    </ol>

    </p>

    <form method="post">
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <div>
                <label for="field<?= $i ?>">Champ <?= $i ?> :</label>
                <input
                    type="text"
                    id="field<?= $i ?>"
                    name="field<?= $i ?>"
                    value="<?= isset($_POST["field$i"]) ? htmlspecialchars($_POST["field$i"]) : '' ?>">
            </div>
        <?php endfor; ?>

        <button type="submit">Envoyer</button>
    </form>

    <?php if ($randomValue !== null): ?>
        <h2>Valeur choisie au hasard :</h2>
        <p><strong><?= htmlspecialchars($randomValue) ?></strong></p>
    <?php endif; ?>

    <div id="polo-overlay" class="hidden">
        <img src="car-dreak.gif" id="polo-gif" alt="Polo Cabrec">
    </div>

    <audio id="polo-audio" src="salut-tout-le-monde-c'est-polo.mp3" preload="auto"></audio>

    <img src="monkey-tornado.gif" id="gif">
    <img src="gaga.webp" id="gaga">

    <script>
        const overlay = document.getElementById('polo-overlay');
        const audio = document.getElementById('polo-audio');
        const poloGif = document.getElementById('polo-gif');
        let audioEnabled = false;

        function enableAudio() {
            if (!audioEnabled) {
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                    audioEnabled = true;
                    scheduleRandomPolo();
                }).catch(err => {
                    console.log('Erreur audio:', err);
                });
            }
        }

        document.querySelectorAll('input, button').forEach(element => {
            element.addEventListener('focus', enableAudio, {
                once: true
            });
            element.addEventListener('click', enableAudio, {
                once: true
            });
        });

        function playPoloAnimation() {
            if (!audioEnabled) return;

            overlay.style.display = 'flex';
            overlay.classList.remove('hidden');
            overlay.classList.add('show');

            setTimeout(() => {
                poloGif.classList.add('zoom-in');
                audio.currentTime = 0;
                audio.play().catch(err => console.log('Audio play error:', err));
            }, 50);

            audio.addEventListener('ended', function() {
                resetAnimation();
            }, {
                once: true
            });
        }

        function resetAnimation() {
            poloGif.style.opacity = '0';
            poloGif.style.transform = 'scale(0.1)';

            setTimeout(() => {
                overlay.style.display = 'none';
                overlay.classList.remove('show');
                overlay.classList.add('hidden');
                poloGif.classList.remove('zoom-in');
            }, 300);
        }

        function scheduleRandomPolo() {
            const randomDelay = Math.random() * (20000 - 5000) + 5000;
            setTimeout(() => {
                playPoloAnimation();
                const audioDuration = audio.duration || 3;
                setTimeout(scheduleRandomPolo, (audioDuration * 1000) + Math.random() * (30000 - 10000) + 10000);
            }, randomDelay);
        }
    </script>
</body>

</html>