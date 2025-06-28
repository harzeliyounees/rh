<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Modifier Panier (Repas)</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/paniers/update/<?php echo $panier->id; ?>">
                <div class="mb-3">
                    <label>Employé</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Sélectionner un employé</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?php echo $emp->id; ?>" <?php echo ($panier->employee_id == $emp->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($emp->nom . ' ' . $emp->prenom); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo $panier->date; ?>" required>
                </div>
                <div class="mb-3">
                    <label>Montant</label>
                    <input type="number" step="0.01" name="montant" class="form-control" value="<?php echo $panier->montant; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
                <a href="/paniers" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>