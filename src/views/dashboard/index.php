<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Bienvenue sur le Tableau de Bord</h2>
        </div>
        <div class="card-body">
            <p>Bonjour, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?> !</p>
           
            
            
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center bg-light">
            <div class="card-body">
                <h4><?php echo $totalEmployees ?? 0; ?></h4>
                <p>Employés</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-light">
            <div class="card-body">
                <h4><?php echo $totalConges ?? 0; ?></h4>
                <p>Congés</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-light">
            <div class="card-body">
                <h4><?php echo $totalHeuresSupp ?? 0; ?></h4>
                <p>Heures Supplémentaires</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-light">
            <div class="card-body">
                <h4><?php echo $totalPaniers ?? 0; ?></h4>
                <p>Paniers</p>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="/employees" class="btn btn-outline-primary me-2">Voir les employés</a>
    <a href="/conges" class="btn btn-outline-success me-2">Voir les congés</a>
    <a href="/heures-supp" class="btn btn-outline-info me-2">Voir les heures supp</a>
    <a href="/paniers" class="btn btn-outline-warning">Voir les paniers</a>
</div>
        </div>
    </div>
</div>