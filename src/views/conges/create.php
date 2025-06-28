<!DOCTYPE html>
<html>
<head>
    <title>Demande de Congé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Nouvelle Demande de Congé</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="/conges/store">
                    <div class="mb-3">
                    <label>Employé</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Sélectionner un employé</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?php echo $emp->id; ?>">
                                <?php echo htmlspecialchars($emp->nom . ' ' . $emp->prenom); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                    <div class="mb-3">
                        <label>Type de Congé</label>
                        <select name="type" class="form-control" required>
                            <option value="annuel">Congé Annuel</option>
                            <option value="maladie">Congé Maladie</option>
                            <option value="exceptionnel">Congé Exceptionnel</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Date Début</label>
                            <input type="date" name="dateDebut" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Date Fin</label>
                            <input type="date" name="dateFin" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                    <label>Statut</label>
                    <select name="statut" class="form-control" required>
                        <option value="pending">En attente</option>
                        <option value="approved">Approuvé</option>
                        <option value="rejected">Rejeté</option>
                    </select>
                </div>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                    <a href="/conges" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>