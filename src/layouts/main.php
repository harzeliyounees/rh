<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>RH Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/public/css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">Tableau de Bord</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <?php if (isset($_SESSION['user'])): ?>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/employees"><i class="fas fa-users"></i> Employés</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/conges"><i class="fas fa-calendar"></i> Congés</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/heures-supp"><i class="fas fa-clock"></i> Heures Supp</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/heures-nuit"><i class="fas fa-moon"></i> Heures Nuit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/paniers"><i class="fas fa-box"></i> Paniers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/deplacements"><i class="fas fa-car"></i> Déplacements</a>
                        </li>
                        
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user']->username); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
    <li><a class="dropdown-item" href="/profile"><i class="fas fa-id-card"></i> Profile</a></li>
    <li><hr class="dropdown-divider"></li>
    <?php if ($_SESSION['user']->role === 'admin'): ?>
       <li>
        <a class="dropdown-item" href="/users">
            <i class="fas fa-cogs"></i> Paramètres utilisateurs
        </a>
    </li>
    <li><hr class="dropdown-divider"></li>
    <?php endif; ?>
    <li>
        <form action="/logout" method="POST" class="d-inline">
            <button type="submit" class="dropdown-item">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </button>
        </form>
    </li>
</ul>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="content">
        <?php if (isset($content)) echo $content; ?>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">© <?php echo date('Y'); ?> SPSJ SONEDE. Tous droits réservés.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/main.js"></script>
</body>
</html>