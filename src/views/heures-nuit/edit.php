<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Modifier Heure de Nuit</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/heures-nuit/update/<?php echo $heure_nuit->id; ?>">
                <div class="mb-3">
                    <label>Employé</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Sélectionner un employé</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?php echo $emp->id; ?>" <?php echo ($heure_nuit->employee_id == $emp->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($emp->nom . ' ' . $emp->prenom); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo $heure_nuit->date; ?>" required>
                </div>
                <div class="mb-3">
                    <label>Heures</label>
                    <input type="number" step="0.01" name="heures" class="form-control" value="<?php echo $heure_nuit->heures; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
                <a href="/heures-nuit" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>