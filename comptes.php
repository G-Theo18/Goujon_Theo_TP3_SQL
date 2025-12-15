<?php
// Paramètres de connexion
$host = "localhost";
$dbname = "banque";
$user = "root";
$pass = "";

// Connexion PDO
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des comptes
$stmt = $pdo->query("SELECT id, solde FROM compte ORDER BY id");
$comptes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Somme des soldes
$stmtTotal = $pdo->query("SELECT SUM(solde) AS total FROM compte");
$total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Comptes bancaires</title>
</head>
<body>

<h2>Liste des comptes bancaires</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>Compte</th>
        <th>Solde (€)</th>
    </tr>

    <?php foreach ($comptes as $compte): ?>
        <tr>
            <td><?= htmlspecialchars($compte['id']) ?></td>
            <td><?= number_format($compte['solde'], 2, ',', ' ') ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p>
    <strong>Somme totale des soldes :</strong>
    <?= number_format($total, 2, ',', ' ') ?> €
</p>

<hr>

<h2>Effectuer un virement</h2>

<form method="post" action="virement.php">
    <label>Compte source :</label>
    <select name="compte_source" required>
        <?php foreach ($comptes as $compte): ?>
            <option value="<?= $compte['id'] ?>">
                Compte <?= $compte['id'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Compte destination :</label>
    <select name="compte_destination" required>
        <?php foreach ($comptes as $compte): ?>
            <option value="<?= $compte['id'] ?>">
                Compte <?= $compte['id'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Montant (€) :</label>
    <input type="number" name="montant" step="1" min="1" required>

    <br><br>

    <button type="submit">Valider le virement</button>
</form>

</body>
</html>
