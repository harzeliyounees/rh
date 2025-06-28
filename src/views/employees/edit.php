<!DOCTYPE html>
<html>
<head>
    <title>Modifier Employé - RH Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">RH Management</a>
            <div class="navbar-nav">
                <a class="nav-link" href="/employees">Employés</a>
                <a class="nav-link" href="/conges">Congés</a>
                <a class="nav-link" href="/heures-supp">Heures Supp</a>
                <a class="nav-link" href="/logout">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Modifier l'Employé</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="/employees/update/<?php echo $employee->id; ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?php echo $employee->nom; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Prénom</label>
                            <input type="text" name="prenom" class="form-control" value="<?php echo $employee->prenom; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>CIN</label>
                            <input type="text" name="cin" class="form-control" value="<?php echo $employee->cin; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Date de Naissance</label>
                            <input type="date" name="dateNaissance" class="form-control" value="<?php echo $employee->dateNaissance; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Catégorie</label>
                            <select name="categorie" class="form-control" required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="A" <?php echo ($employee->categorie == 'A') ? 'selected' : ''; ?>>Catégorie A</option>
                                <option value="B" <?php echo ($employee->categorie == 'B') ? 'selected' : ''; ?>>Catégorie B</option>
                                <option value="C" <?php echo ($employee->categorie == 'C') ? 'selected' : ''; ?>>Catégorie C</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Classe</label>
                            <select name="classe" class="form-control" required>
                                <option value="">Sélectionner une classe</option>
                                <option value="1" <?php echo ($employee->classe == '1') ? 'selected' : ''; ?>>Classe 1</option>
                                <option value="2" <?php echo ($employee->classe == '2') ? 'selected' : ''; ?>>Classe 2</option>
                                <option value="3" <?php echo ($employee->classe == '3') ? 'selected' : ''; ?>>Classe 3</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Spécificité</label>
                        <input type="text" name="specificite" class="form-control" value="<?php echo $employee->specificite; ?>">
                    </div>

                    <div class="mb-3">
                        <label>Date de Recrutement</label>
                        <input type="date" name="dateRecrutement" class="form-control" value="<?php echo $employee->dateRecrutement; ?>" required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                        <a href="/employees" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>