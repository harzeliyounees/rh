
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Modifier Heure Supplémentaire</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/heures-supp/update/<?php echo $heure_supp->id; ?>">
                <div class="mb-3">
                    <label>Employé</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Sélectionner un employé</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?php echo $emp->id; ?>" <?php echo ($heure_supp->employee_id == $emp->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($emp->nom . ' ' . $emp->prenom); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo $heure_supp->date; ?>" required>
                </div>
                <div class="mb-3">
                    <label>Heures</label>
                    <input type="number" step="0.01" name="heures" class="form-control" value="<?php echo $heure_supp->heures; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
                <a href="/heures-supp" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>