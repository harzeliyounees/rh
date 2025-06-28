
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des Employés</h2>
        <?php if ($_SESSION['user']->role === 'admin'): ?>
            <a href="/employees/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau Employé
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
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>CIN</th>
                            <th>Catégorie</th>
                            <th>Classe</th>
                            <th>Spécificité</th>
                            <th>Date Recrutement</th>
                            <?php if ($_SESSION['user']->role === 'admin'): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($employees)): ?>
                            <tr>
                                <td colspan="<?php echo $_SESSION['user']->role === 'admin' ? '9' : '8'; ?>" class="text-center">Aucun employé trouvé</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($employees as $employee): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($employee->id); ?></td>
                                    <td><?php echo htmlspecialchars($employee->nom); ?></td>
                                    <td><?php echo htmlspecialchars($employee->prenom); ?></td>
                                    <td><?php echo htmlspecialchars($employee->cin); ?></td>
                                    <td><?php echo htmlspecialchars($employee->categorie); ?></td>
                                    <td><?php echo htmlspecialchars($employee->classe); ?></td>
                                    <td><?php echo htmlspecialchars($employee->specificite); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($employee->dateRecrutement)); ?></td>
                                    <?php if ($_SESSION['user']->role === 'admin'): ?>
                                    <td>
                                        <a href="/employees/edit/<?php echo $employee->id; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal<?php echo $employee->id; ?>">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </td>
                                    <?php endif; ?>
                                </tr>

                                <?php if ($_SESSION['user']->role === 'admin'): ?>
                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal<?php echo $employee->id; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer l'employé <?php echo htmlspecialchars($employee->nom . ' ' . $employee->prenom); ?> ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="/employees/delete/<?php echo $employee->id; ?>" method="POST" style="display: inline;">
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