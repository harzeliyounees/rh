<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="text-center">Inscription Utilisateur</h3>
                </div>
<div class="card-body">
    <form method="POST" action="/auth/register/submit" autocomplete="off">
        <div class="mb-3">
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <small class="form-text text-muted">
                Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un caractère spécial.
            </small>
            <div class="progress mt-2" style="height: 5px;">
                <div id="passwordStrength" class="progress-bar" role="progressbar" style="width: 0%;"></div>
            </div>
        </div>
        <div class="mb-3">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            <small id="matchIndicator" class="form-text"></small>
        </div>
        <div class="mb-3">
            <label>Rôle</label>
            <select name="role" class="form-control" required>
                <option value="admin">Administrateur</option>
                <option value="viewer">Observateur</option>
            </select>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary w-50 me-2">Ajout</button>
            <a href="/users" class="btn btn-secondary w-50">Annuler</a>
        </div>
    </form>
</div>
                </div>
            </div>
        </div>
    </div>
<script>
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('passwordStrength');
    const matchIndicator = document.getElementById('matchIndicator');

    function checkStrength(pwd) {
        let strength = 0;
        if (pwd.length >= 8) strength += 1;
        if (/[A-Z]/.test(pwd)) strength += 1;
        if (/[a-z]/.test(pwd)) strength += 1;
        if (/[0-9]/.test(pwd)) strength += 1;
        if (/[^A-Za-z0-9]/.test(pwd)) strength += 1;

        return strength;
    }

    password.addEventListener('input', function() {
        const val = password.value;
        const strength = checkStrength(val);
        let percent = (strength / 5) * 100;
        let color = 'bg-danger';
        if (strength >= 4) color = 'bg-success';
        else if (strength === 3) color = 'bg-warning';

        strengthBar.style.width = percent + '%';
        strengthBar.className = 'progress-bar ' + color;
    });

    function checkMatch() {
        if (passwordConfirmation.value.length === 0) {
            matchIndicator.textContent = '';
            matchIndicator.className = 'form-text';
            return;
        }
        if (password.value === passwordConfirmation.value) {
            matchIndicator.textContent = 'Les mots de passe correspondent ✔️';
            matchIndicator.className = 'form-text text-success';
        } else {
            matchIndicator.textContent = 'Les mots de passe ne correspondent pas ❌';
            matchIndicator.className = 'form-text text-danger';
        }
    }

    password.addEventListener('input', checkMatch);
    passwordConfirmation.addEventListener('input', checkMatch);
</script>