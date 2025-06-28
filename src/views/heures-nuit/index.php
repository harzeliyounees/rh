
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des Heures de Nuit</h2>
        <?php if ($_SESSION['user']->role === 'admin'): ?>
        <a href="/heures-nuit/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter Heure de Nuit
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
                        <?php if (empty($heures_nuit)): ?>
                            <tr>
                                <td colspan="<?php echo $_SESSION['user']->role === 'admin' ? '5' : '4'; ?>" class="text-center">Aucune heure de nuit trouvée</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($heures_nuit as $hn): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($hn->id); ?></td>
                                    <td>
                                        <?php echo isset($hn->nom) ? htmlspecialchars($hn->nom . ' ' . $hn->prenom) : 'ID: ' . $hn->employee_id; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($hn->date)); ?></td>
                                    <td><?php echo htmlspecialchars($hn->heures); ?></td>
                                    <?php if ($_SESSION['user']->role === 'admin'): ?>
                                    <td>
                                        <a href="/heures-nuit/edit/<?php echo $hn->id; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form action="/heures-nuit/delete/<?php echo $hn->id; ?>" method="POST" style="display:inline;">
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