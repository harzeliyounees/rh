
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des Congés</h2>
        <?php if ($_SESSION['user']->role === 'admin'): ?>
        <a href="/conges/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Congé
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
                            <th>Type</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Statut</th>
                            <?php if ($_SESSION['user']->role === 'admin'): ?>
                            <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($conges)): ?>
                            <tr>
                                <td colspan="<?php echo $_SESSION['user']->role === 'admin' ? '7' : '6'; ?>" class="text-center">Aucun congé trouvé</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($conges as $conge): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($conge->id); ?></td>
                                    <td>
                                        <?php
                                            echo isset($conge->nom) ? htmlspecialchars($conge->nom . ' ' . $conge->prenom) : 'ID: ' . $conge->employee_id;
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($conge->type); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($conge->date_debut)); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($conge->date_fin)); ?></td>
                                    <td>
                                        <?php
                                            $badge = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger'
                                            ][$conge->statut] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>">
                                            <?php echo ucfirst($conge->statut); ?>
                                        </span>
                                    </td>
                                    <?php if ($_SESSION['user']->role === 'admin'): ?>
                                    <td>
                                        <a href="/conges/edit/<?php echo $conge->id; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal<?php echo $conge->id; ?>">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </td>
                                    <?php endif; ?>
                                </tr>

                                <?php if ($_SESSION['user']->role === 'admin'): ?>
                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal<?php echo $conge->id; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer ce congé ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="/conges/delete/<?php echo $conge->id; ?>" method="POST" style="display: inline;">
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>