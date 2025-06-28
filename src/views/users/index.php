
<div class="container mt-4">
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Liste des utilisateurs</h3>
            <a href="/auth/register" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus"></i> Ajouter un utilisateur
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom d'utilisateur</th>
                            <th>Rôle</th>
                            <th>Date de création</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user->id); ?></td>
                                    <td><?php echo htmlspecialchars($user->username); ?></td>
                                    <td><?php echo htmlspecialchars($user->role); ?></td>
                                    <td><?php echo htmlspecialchars($user->created_at); ?></td>
                                    <td class="text-center">
                                        <form action="/users/delete/<?php echo $user->id; ?>" method="POST" style="display:inline;">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <form action="/users/deactivate/<?php echo $user->id; ?>" method="POST" style="display:inline;">
                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Désactiver cet utilisateur ?');">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucun utilisateur trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>