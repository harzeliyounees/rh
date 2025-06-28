<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Ajouter Heure de Nuit</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/heures-nuit/store">
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
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Heures</label>
                    <input type="number" step="0.01" name="heures" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="/heures-nuit" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>