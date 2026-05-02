<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
$query = "SELECT commandes.id, commandes.date_commande, commandes.statut, clients.nom, SUM(details_commandes.quantite * details_commandes.prix_unitaire) as total
          FROM commandes
          JOIN clients ON commandes.client_id = clients.id
          JOIN details_commandes ON commandes.id = details_commandes.commande_id
          GROUP BY commandes.id";
$stmt = $pdo->query($query);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h1>Gérer les commandes</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Client</th>
            <th>Total</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($commandes as $commande): ?>
            <tr>
                <td><?= htmlspecialchars($commande['id']) ?></td>
                <td><?= htmlspecialchars($commande['date_commande']) ?></td>
                <td><?= htmlspecialchars($commande['nom']) ?></td>
                <td><?= htmlspecialchars($commande['total']) ?> €</td>
                <td><?= htmlspecialchars($commande['statut']) ?></td>
                <td>
                    <form action="update_order.php" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($commande['id']) ?>">
                        <select name="statut">
                            <option value="En attente" <?= $commande['statut'] == 'En attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="En cours" <?= $commande['statut'] == 'En cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="Livrée" <?= $commande['statut'] == 'Livrée' ? 'selected' : '' ?>>Livrée</option>
                            <option value="Annulée" <?= $commande['statut'] == 'Annulée' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                        <button type="submit">Mettre à jour</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include 'footer.php';
?>