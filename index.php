<?php
// Connexion PDO à MySQL
try {
    $host = 'mysql';
    $dbname = 'tdR606';
    $user = 'root';
    $password = 'rootpassword';

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
    <img src="monkey-tornado.gif" id="gif">
    <!-- <img src="france.jpg" id="france"> -->
    <img src="gaga.webp" id="gaga">
</body>

</html>