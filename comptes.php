<?php
$pdo = new PDO("mysql:host=localhost;dbname=banque;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $source = intval($_POST['source']);
    $destination = intval($_POST['destination']);
    $montant = floatval($_POST['montant']);

    if ($source === $destination) {
        $message = "Erreur : le compte source et destination doivent être différents.";
    } else {
        $stmt = $pdo->prepare("CALL virement(:source, :destination, :montant, @res)");
        $stmt->execute([
            ':source' => $source,
            ':destination' => $destination,
            ':montant' => $montant
        ]);

        $res = $pdo->query("SELECT @res AS resultat")->fetch(PDO::FETCH_ASSOC)['resultat'];

        if ($res == 1) {
            $message = "Virement réussi !";
        } else {
            $message = "Erreur : virement impossible (solde insuffisant, compte inexistant ou montant invalide).";
        }
    }
}

$comptes = $pdo->query("SELECT id, solde FROM compte ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
$total = $pdo->query("SELECT SUM(solde) AS total FROM compte")->fetch(PDO::FETCH_ASSOC)['total'];
?>

<h1>Comptes bancaires</h1>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Solde (€)</th>
    </tr>
    <?php foreach ($comptes as $c): ?>
    <tr>
        <td><?= $c['id'] ?></td>
        <td><?= number_format($c['solde'],2) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <th>Total</th>
        <th><?= number_format($total,2) ?></th>
    </tr>
</table>

<h2>Effectuer un virement</h2>
<form method="post">
    Compte source:
    <select name="source">
        <?php foreach ($comptes as $c): ?>
            <option value="<?= $c['id'] ?>"><?= $c['id'] ?> (<?= number_format($c['solde'],2) ?> €)</option>
        <?php endforeach; ?>
    </select>

    </br></br>

    Compte destination:
    <select name="destination">
        <?php foreach ($comptes as $c): ?>
            <option value="<?= $c['id'] ?>"><?= $c['id'] ?> (<?= number_format($c['solde'],2) ?> €)</option>
        <?php endforeach; ?>
    </select>

    </br></br>
    
    Montant:
    <input type="number" step="1" min="1" name="montant" required>
    <input type="submit" value="Virement">
</form>

<?php if ($message): ?>
<p><?= $message ?></p>
<?php endif; ?>
