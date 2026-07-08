<?php
include('../inc/functions.php');
$departments = get_all_departments();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Les news</title>
    <link rel="stylesheet" href="../design/theme-corporate/style.css">
</head>
<body>

<nav class="navbar">
    <ul>
        <li class="brand">Gestion des employés</li>
        <li><a href="index.php" class="active">Départements</a></li>
        <li><a href="search.php">🔍 Rechercher un employé</a></li>
        <li><a href="stats.php">📊 Statistiques par emploi</a></li>
        <li><a href="dept_form.php">➕ Ajouter un département</a></li>
        <li><a href="emp_form.php">➕ Ajouter un employé</a></li>
    </ul>
</nav>

<div class="container">
    <h1>Liste des départements</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Department Number</th>
                <th>Department Name</th>
                <th>Manager actuel</th>
                <th>Nombre d'employés</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departments as $line): ?>
                <tr>
                    <td><a href="employees.php?dept_no=<?= urlencode($line['dept_no']) ?>"><?= htmlspecialchars($line['dept_no']) ?></a></td>
                    <td><?= htmlspecialchars($line['dept_name']) ?></td>
                    <td><?= htmlspecialchars($line['manager_name'] ?? '—') ?></td>
                    <td><?= (int)$line['nb_employees'] ?></td>
                    <td><a href="dept_form.php?dept_no=<?= urlencode($line['dept_no']) ?>" class="btn btn-secondary">Éditer</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>