<?php
    include('../inc/functions.php');

    $emp_no   = $_GET['emp_no'] ?? '';
    $employee = get_one_employee($emp_no);
    $current_dept = get_current_department($emp_no);

    $error   = '';
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $current_dept) {
        $start = $_POST['from_date'] ?? '';
        $manager = get_current_manager($current_dept['dept_no']);

        if ($start === '') {
            $error = "Veuillez saisir une date de début.";
        } elseif ($manager && $start < $manager['from_date']) {
            $error = "La date de début ($start) ne peut pas être antérieure à celle du manager actuel (" . $manager['from_date'] . ").";
        } else {
            make_manager($emp_no, $current_dept['dept_no'], $start);
            $success = true;
        }
    }

    $manager = $current_dept ? get_current_manager($current_dept['dept_no']) : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Devenir manager — Employés DB</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="navbar">
        <ul>
            <li class="brand">Employés DB</li>
            <li><a href="index.php">Départements</a></li>
            <li><a href="search.php">Rechercher</a></li>
            <li><a href="stats.php">Statistiques</a></li>
            <li><a href="add.php">Ajouter un employé</a></li>
        </ul>
    </nav>

    <div class="container">
        <p><a href="fiche.php?emp_no=<?= urlencode($emp_no) ?>">&larr; Retour à la fiche</a></p>

        <?php if (!$employee) { ?>
            <div class="alert alert-error">Employé introuvable</div>
        <?php } elseif (!$current_dept) { ?>
            <div class="alert alert-error">Cet employé n'a pas de département actuel.</div>
        <?php } else { ?>
            <h1><?= htmlspecialchars($employee['first_name']) ?> <?= htmlspecialchars($employee['last_name']) ?> — devenir manager de <?= htmlspecialchars($current_dept['dept_name']) ?></h1>

            <?php if ($success) { ?>
                <div class="alert alert-success">
                    C'est fait : l'employé est désormais le manager du département.
                    <a href="index.php">Vérifier dans la liste des départements &rarr;</a>
                </div>
            <?php } ?>
            <?php if ($error !== '') { ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php } ?>

            <div class="card mb">
                <p><strong>Manager en cours :</strong>
                    <?= $manager ? htmlspecialchars($manager['manager_name']) . ' (depuis le ' . htmlspecialchars($manager['from_date']) . ')' : 'aucun' ?>
                </p>
            </div>

            <div class="card">
                <form method="post" action="become_manager.php?emp_no=<?= urlencode($emp_no) ?>">
                    <div class="form-group">
                        <label for="from_date">Date de début</label>
                        <input class="form-control" type="date" id="from_date" name="from_date" required>
                    </div>
                    <button type="submit" class="btn">Devenir manager</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>