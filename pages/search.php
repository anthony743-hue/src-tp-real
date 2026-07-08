<?php
include('../inc/functions.php');

$departments = get_all_departments();

// Récupération des critères (?? '' évite le warning si le champ est absent)
$dept_no = $_GET['dept_no'] ?? '';
$name    = $_GET['name']    ?? '';
$age_min = $_GET['age_min'] ?? '';
$age_max = $_GET['age_max'] ?? '';

// On ne lance la recherche que si le formulaire a été soumis
$submitted = isset($_GET['dept_no']);
$results   = $submitted ? search_employees($dept_no, $name, $age_min, $age_max) : array();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche d'employés</title>
    <link rel="stylesheet" href="../design/theme-corporate/style.css">
</head>
<body>

<nav class="navbar">
    <ul>
        <li class="brand">Gestion des employés</li>
        <li><a href="index.php">Départements</a></li>
        <li><a href="search.php" class="active">🔍 Rechercher un employé</a></li>
        <li><a href="stats.php">📊 Statistiques par emploi</a></li>
        <li><a href="dept_form.php">➕ Ajouter un département</a></li>
        <li><a href="emp_form.php">➕ Ajouter un employé</a></li>
    </ul>
</nav>

<div class="container">
    <p><a href="index.php">&larr; Retour aux départements</a></p>
    <h1>Recherche d'employés</h1>

    <form method="get" action="search.php" class="card">
        <div class="form-group">
            <label for="dept_no">Département</label>
            <select name="dept_no" id="dept_no" class="form-control">
                <option value="">— Tous —</option>
                <?php foreach ($departments as $d): ?>
                    <option value="<?= htmlspecialchars($d['dept_no']) ?>" <?= $dept_no === $d['dept_no'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['dept_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Nom de l'employé</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="form-inline">
            <div class="form-group">
                <label for="age_min">Âge min</label>
                <input type="number" name="age_min" id="age_min" class="form-control" value="<?= htmlspecialchars($age_min) ?>">
            </div>
            <div class="form-group">
                <label for="age_max">Âge max</label>
                <input type="number" name="age_max" id="age_max" class="form-control" value="<?= htmlspecialchars($age_max) ?>">
            </div>
        </div>

        <button type="submit" class="btn mt">Rechercher</button>
    </form>

    <?php if ($submitted): ?>
        <h2><?= count($results) ?> résultat(s)<?= count($results) === 200 ? ' (limité à 200)' : '' ?></h2>
        <?php if (count($results) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Genre</th>
                        <th>Âge</th>
                        <th>Département</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $emp): ?>
                        <tr>
                            <td><a href="fiche.php?emp_no=<?= urlencode($emp['emp_no']) ?>"><?= htmlspecialchars($emp['emp_no']) ?></a></td>
                            <td><?= htmlspecialchars($emp['first_name']) ?></td>
                            <td><?= htmlspecialchars($emp['last_name']) ?></td>
                            <td><?= htmlspecialchars($emp['gender']) ?></td>
                            <td><?= (int)$emp['age'] ?></td>
                            <td><?= htmlspecialchars($emp['dept_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-error">Aucun employé trouvé pour ces critères.</div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>