
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Modifier le Congé</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/conges/update/<?php echo $conge->id; ?>">
                <div class="mb-3">
                    <label>Employé</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Sélectionner un employé</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?php echo $emp->id; ?>" <?php echo ($conge->employee_id == $emp->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($emp->nom . ' ' . $emp->prenom); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Type de Congé</label>
                    <select name="type" class="form-control" required>
                        <option value="annuel" <?php echo ($conge->type == 'annuel') ? 'selected' : ''; ?>>Congé Annuel</option>
                        <option value="maladie" <?php echo ($conge->type == 'maladie') ? 'selected' : ''; ?>>Congé Maladie</option>
                        <option value="exceptionnel" <?php echo ($conge->type == 'exceptionnel') ? 'selected' : ''; ?>>Congé Exceptionnel</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Date Début</label>
                        <input type="date" name="dateDebut" class="form-control" value="<?php echo $conge->date_debut; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Date Fin</label>
                        <input type="date" name="dateFin" class="form-control" value="<?php echo $conge->date_fin; ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Statut</label>
                    <select name="statut" class="form-control" required>
                        <option value="pending" <?php echo ($conge->statut == 'pending') ? 'selected' : ''; ?>>En attente</option>
                        <option value="approved" <?php echo ($conge->statut == 'approved') ? 'selected' : ''; ?>>Approuvé</option>
                        <option value="rejected" <?php echo ($conge->statut == 'rejected') ? 'selected' : ''; ?>>Rejeté</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                    <a href="/conges" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>