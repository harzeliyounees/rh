<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Mon Profil</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>Nom d'utilisateur</th>
                    <td><?php echo htmlspecialchars($user->username); ?></td>
                </tr>
                <tr>
                    <th>Rôle</th>
                    <td><?php echo htmlspecialchars($user->role); ?></td>
                </tr>
                <tr>
                    <th>Date de création</th>
                    <td><?php echo htmlspecialchars($user->created_at); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>