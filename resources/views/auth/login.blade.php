<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Espace Membre</title>
    <style>
        body {
    margin-top: 100px;
    font-family: Arial, sans-serif;

    /* Image de fond */
    background-image: url("/images/aa.jpg"); /* remplace par le chemin de ton image */
    background-size: cover; /* occupe tout l'écran */
    background-position: center; /* centre l'image */
    background-repeat: no-repeat; /* pas de répétition */

    /* Couche de couleur transparente au-dessus de l’image */
    position: relative;
}

body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(48, 46, 46, 0.8); /* même couleur que ton ancien fond avec opacité */
    z-index: -1; /* reste derrière le contenu */
}

        .container {
            margin: 0 auto;
            max-width: 400px;
        }
        .form {
            width: 300px;
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding: 2.2rem;
            background: linear-gradient(14deg, #000000cc 0%, #1a1a1ab3 66%, #2d2d2d 100%), 
                        radial-gradient(circle, #00000080 0%, #1a1a1a33 65%, #2d2d2de6 100%);
            border: 2px solid #f506c4;
            border-radius: 8px;
            box-shadow: #f506c4 0px 0px 50px -15px;
            overflow: hidden;
            z-index: +1;
        }

        .title-1 {
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-family: monospace;
            font-weight: 600;
            text-align: center;
            color: #ffffff;
            text-shadow: 0px 0px 10px #f506c4;
            text-transform: uppercase;
            animation-duration: 1.5s;
            overflow: 0.12s;
        }

        .title-1 span {
            animation: flick 2s linear infinite both;
        }

        .title-2 {
            display: block;
            margin-top: -0.5rem;
            font-size: 2.1rem;
            font-weight: 800;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            background: linear-gradient(45deg, #ff7b00, #0066ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.2rem;
            text-transform: uppercase;
            position: relative;
            text-shadow: 0px 0px 20px rgba(245, 6, 196, 0.5);
            margin-bottom: 10px;
        }

        .title-2 span::before,
        .title-2 span::after {
            content: "_";
            color: #f506c4;
        }

        @keyframes flick {
            0%, 100% { opacity: 1; }
            41.99% { opacity: 1; }
            42% { opacity: 0; }
            43% { opacity: 0; }
            43.01% { opacity: 1; }
            47.99% { opacity: 1; }
            48% { opacity: 0; }
            49% { opacity: 0; }
            49.01% { opacity: 1; }
        }

        input,
        button {
            outline: none;
            border: 2px solid #f506c4;
            font-family: monospace;
            border-radius: 4px;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 8px 10px;
            font-size: 0.875rem;
            line-height: 1.25rem;
            box-shadow: 0 1px 2px 0 rgba(245, 6, 196, 0.3);
            color: #000;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #0066ff;
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.3);
        }

        input::placeholder {
            color: #666;
        }

        input:focus::placeholder {
            opacity: 0;
            transform: opacity 0.9s;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #f506c4;
            font-size: 14px;
        }

        .submit {
            width: 100%;
            position: relative;
            display: block;
            padding: 10px;
            background: linear-gradient(90deg, #0066ff 0%, #f506c4 100%);
            color: #ffffff;
            text-shadow: 1px 1px 1px #00000080;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            text-transform: uppercase;
            overflow: hidden;
            border: none;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit:hover {
            transition: all 0.2s ease-out;
            box-shadow: 0px 0px 20px rgba(245, 6, 196, 0.7);
            transform: translateY(-2px);
        }

        .submit::before {
            content: "";
            top: 50%;
            left: 30%;
            display: block;
            width: 0px;
            height: 85%;
            position: absolute;
            background: #fff;
            box-shadow: 0 0 50px 30px #fff;
            transform: skewX(-20deg);
            left: 0;
            opacity: 0;
        }

        .submit:hover::before {
            animation: slide 0.5s 0s linear;
        }

        @keyframes slide {
            from {
                left: 0%;
            }
            50% {
                opacity: 1;
            }
            to {
                opacity: 0;
                left: 100%;
            }
        }

        .login-link {
            color: #c0c0c0;
            font-size: 0.875rem;
            line-height: 1.25rem;
            text-align: center;
            font-family: monospace;
            margin: 8px;
        }

        .login-link a {
            color: #f506c4;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
            color: #0066ff;
        }

        .error-message {
            color: #ff296d;
            font-size: 12px;
            margin-top: -8px;
            font-family: monospace;
            text-shadow: 0px 0px 5px rgba(255, 41, 109, 0.5);
        }

        .bg-stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background-size: cover;
            animation: animateBg 50s linear infinite;
        }

        @keyframes animateBg {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }

        .star {
            position: absolute;
            top: 90%;
            left: 50%;
            width: 4px;
            height: 4px;
            background: #f506c4;
            border-radius: 50%;
            box-shadow: 
                0 0 0 4px rgba(245, 6, 196, 0.1),
                0 0 0 8px rgba(245, 6, 196, 0.1),
                0 0 0 20px rgba(245, 6, 196, 0.1);
            animation: star 3s linear infinite;
        }

        .star::before {
            content: "";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 300px;
            height: 1px;
            background: linear-gradient(90deg, #f506c4, transparent);
        }

        @keyframes star {
            0% {
                transform: rotate(315deg) translateX(0);
                opacity: 1;
            }
            70% {
                opacity: 1;
            }
            100% {
                transform: rotate(315deg) translateX(-1000px);
                opacity: 0;
            }
        }

        .star {
            top: 0;
            left: initial;
        }

        .star:nth-child(1) {
            right: 0;
            animation-delay: 0s;
            animation-duration: 1s;
        }

        .star:nth-child(2) {
            right: 100px;
            animation-delay: 0.2s;
            animation-duration: 3s;
        }

        .star:nth-child(3) {
            right: 220px;
            animation-delay: 2.75s;
            animation-duration: 2.75s;
        }

        .star:nth-child(4) {
            right: -220px;
            animation-delay: 1.6s;
            animation-duration: 1.6s;
        }

        /* --------- Variables (tu peux adapter les couleurs) --------- */
:root{
  --text:#0f172a;              /* slate-900 */
  --muted:#64748b;             /* slate-500 */
  --primary:#2563eb;           /* blue-600 */
  --primary-hover:#1d4ed8;     /* blue-700 */
  --ring:#93c5fd;              /* blue-300 */
  --bg:#ffffff;
  --link:#0ea5e9;              /* sky-500 */
  --link-hover:#0284c7;        /* sky-600 */
  --border:#e5e7eb;            /* gray-200 */
  --danger:#ef4444;            /* red-500 si besoin */
}

@media (prefers-color-scheme: dark){
  :root{
    --text:#e5e7eb;
    --muted:#94a3b8;
    --bg:#0b1220;
    --border:#1f2937;
  }
}

/* --------- Mise en page de la ligne remember/forgot --------- */
.remember-forgot{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:1rem;
  margin:1rem 0 1.25rem;
  color:var(--muted);
  font-size:0.95rem;
}

/* Alignement checkbox + label */
.remember-me{
  display:inline-flex;
  align-items:center;
  gap:0.5rem;
  user-select:none;
}

/* Checkbox custom légère (garde l’accessibilité native) */
.remember-me input[type="checkbox"]{
  appearance:none;
  width:18px;
  height:18px;
  border:1.5px solid var(--border);
  border-radius:4px;
  display:inline-grid;
  place-content:center;
  background:var(--bg);
  cursor:pointer;
  transition:border-color .2s, box-shadow .2s, background-color .2s;
}
.remember-me input[type="checkbox"]:focus{
  outline:none;
  box-shadow:0 0 0 4px color-mix(in oklab, var(--ring) 45%, transparent);
  border-color:var(--primary);
}
.remember-me input[type="checkbox"]:checked{
  background:var(--primary);
  border-color:var(--primary);
}
.remember-me input[type="checkbox"]:checked::before{
  content:"";
  width:10px;height:10px;
  clip-path:polygon(14% 44%, 0 65%, 42% 100%, 100% 22%, 80% 0, 39% 60%);
  background:#fff;
}

/* Lien “mot de passe oublié” */
.forgot-password a{
  color:var(--link);
  text-decoration:none;
  font-weight:500;
}
.forgot-password a:hover,
.forgot-password a:focus{
  color:var(--link-hover);
  text-decoration:underline;
  outline:none;
}

/* --------- Bouton --------- */


/* Texte interne du bouton (au cas où tu ajoutes une icône) */
.sign-text{ line-height:1; }

/* --------- Ligne d’inscription --------- */
.signup-link{
  margin-top:1rem;
  text-align:center;
  color:var(--muted);
  font-size:0.95rem;
}
.signup-link .uo{
  color:var(--primary);
  text-decoration:none;
  font-weight:600;
}
.signup-link .uo:hover,
.signup-link .uo:focus{
  color:var(--primary-hover);
  text-decoration:underline;
  outline:none;
}

/* --------- Petits écrans --------- */
@media (max-width:480px){
  .remember-forgot{
    flex-direction:column;
    align-items:flex-start;
  }
}

    </style>
</head>
<body>
    <div class="container">
        <form class="form" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Session Status -->
            @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif

            <div class="title-1">
                <span>connectez-vous à</span>
            </div>
            <div class="title-2">
                <span>votre espace</span>
            </div>
    
            <!-- Email Address -->
            <div>
                <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @if ($errors->has('email'))
                    <div class="error-message">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>
            
            <!-- Password -->
            <div class="password-container">
                <input id="password" type="password" name="password" placeholder="Mot de passe" required autocomplete="current-password">
                <button type="button" class="toggle-password" id="togglePassword">👁️</button>
                @if ($errors->has('password'))
                    <div class="error-message">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>
            
            <!-- Remember Me -->
            <div class="remember-forgot">
                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Se souvenir de moi</label>
                </div>
                
                @if (Route::has('password.request'))
                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">
                            Mot de passe oublié?
                        </a>
                    </div>
                @endif
            </div>
    
            <button type="submit" class="submit">
                <span class="sign-text">Se connecter</span>
            </button>
    
            <p class="signup-link">Pas encore de compte ?
                @if (Route::has('register'))
                    <a class="uo" href="{{ route('register') }}">
                        Inscrivez-vous !
                    </a>
                @endif
            </p>
    
            <section class="bg-stars">
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
            </section>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            // Fonction pour afficher/masquer le mot de passe
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        togglePassword.textContent = '🔒';
                    } else {
                        passwordInput.type = 'password';
                        togglePassword.textContent = '👁️';
                    }
                });
            }
            
            // Pré-remplir les anciennes valeurs en cas d'erreur
            @if(old('email'))
                document.getElementById('email').value = "{{ old('email') }}";
            @endif
            
            @if(old('remember'))
                document.getElementById('remember_me').checked = true;
            @endif
        });
    </script>
</body>
</html>