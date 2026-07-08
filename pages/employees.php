<?php
include('../inc/functions.php');

$dept_no = $_GET['dept_no'] ?? '';
$department = get_one_department($dept_no);

// --- Pagination ---
$par_page = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $par_page;

$total = count_employees_by_department($dept_no);
$nb_pages = (int)ceil($total / $par_page);

$employees = get_employees_by_department($dept_no, $par_page, $offset);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Employés du département</title>
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
    <p><a href="index.php">&larr; Retour aux départements</a></p>

    <?php if (!$department): ?>
        <div class="alert alert-error">
            <strong>Département introuvable.</strong> Le département demandé n'existe pas.
        </div>
    <?php else: ?>
        <h1>Employés du département <?= htmlspecialchars($department['dept_name']) ?> (<?= htmlspecialchars($department['dept_no']) ?>)</h1>

        <?php if (count($employees) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Genre</th>
                        <th>Date d'embauche</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $emp): ?>
                        <tr>
                            <td><a href="fiche.php?emp_no=<?= urlencode($emp['emp_no']) ?>"><?= htmlspecialchars($emp['emp_no']) ?></a></td>
                            <td><?= htmlspecialchars($emp['first_name']) ?></td>
                            <td><?= htmlspecialchars($emp['last_name']) ?></td>
                            <td><?= htmlspecialchars($emp['gender']) ?></td>
                            <td><?= htmlspecialchars($emp['hire_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="employees.php?dept_no=<?= urlencode($dept_no) ?>&page=<?= $page - 1 ?>">&larr; Précédent</a>
                <?php endif; ?>

                <span class="current">Page <?= $page ?> / <?= $nb_pages ?></span>

                <?php if ($page < $nb_pages): ?>
                    <a href="employees.php?dept_no=<?= urlencode($dept_no) ?>&page=<?= $page + 1 ?>">Suivant &rarr;</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-error">Aucun employé trouvé dans ce département.</div>
        <?php endif; ?>

        <p class="text-muted mt"><?= $total ?> employé(s) au total dans ce département.</p>
    <?php endif; ?>
</div>

</body>
</html>