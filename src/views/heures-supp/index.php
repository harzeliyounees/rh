<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des Heures Supplémentaires</h2>
        <?php if ($_SESSION['user']->role === 'admin'): ?>
        <a href="/heures-supp/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter Heure Supplémentaire
        </a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
            <?php 
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employé</th>
                            <th>Date</th>
                            <th>Heures</th>
                            <?php if ($_SESSION['user']->role === 'admin'): ?>
                            <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($heures_supp)): ?>
                            <tr>
                                <td colspan="<?php echo $_SESSION['user']->role === 'admin' ? '5' : '4'; ?>" class="text-center">Aucune heure supplémentaire trouvée</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($heures_supp as $hs): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($hs->id); ?></td>
                                    <td>
                                        <?php echo isset($hs->nom) ? htmlspecialchars($hs->nom . ' ' . $hs->prenom) : 'ID: ' . $hs->employee_id; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($hs->date)); ?></td>
                                    <td><?php echo htmlspecialchars($hs->heures); ?></td>
                                    <?php if ($_SESSION['user']->role === 'admin'): ?>
                                    <td>
                                        <a href="/heures-supp/edit/<?php echo $hs->id; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form action="/heures-supp/delete/<?php echo $hs->id; ?>" method="POST" style="display:inline;">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>