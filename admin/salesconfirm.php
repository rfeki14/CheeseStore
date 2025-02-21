<?php

if (isset($_POST['id'])) {
    // Assainir l'entrée
    $id = intval($_POST['id']); // S'assurer que `id` est un entier

    include 'includes/session.php'; // Connexion à la base de données

    $conn = $pdo->open();

    try {
        // Mettre à jour la colonne `confirmed` pour la vente spécifique
        $stmt = $conn->prepare("UPDATE sales SET confirmed = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // Vérifier si la mise à jour a réussi
        if ($stmt->rowCount() > 0) {
            echo "Vente confirmée avec succès.";
        } else {
            echo "Aucune ligne n'a été mise à jour. Veuillez vérifier l'ID de la vente.";
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }

    $pdo->close();
} else {
    echo "Aucun ID de vente fourni.";
}