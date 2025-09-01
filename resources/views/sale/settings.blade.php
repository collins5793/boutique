
@extends('layouts.sales.sale')

@section('title', 'Paramètres du compte')

@section('content')

    <style>
        :root {
            --primary: #f506c4;
            --primary-light: #ff33d1;
            --primary-dark: #c0049b;
            --secondary: #007bff;
            --accent: #ff7b00;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1e293b;
            --dark-light: #334155;
            --light: #ffffff;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .header p {
            color: var(--gray-500);
            font-size: 16px;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid var(--gray-300);
            margin-bottom: 30px;
        }

        .tab {
            padding: 12px 24px;
            font-weight: 600;
            color: var(--gray-500);
            cursor: pointer;
            transition: var(--transition);
            border-bottom: 3px solid transparent;
            margin-right: 5px;
        }

        .tab:hover {
            color: var(--primary);
        }

        .tab.active {
            color: var(--primary);
            border-bottom: 3px solid var(--primary);
        }

        .section {
            background: var(--light);
            border-radius: var(--radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            display: none;
        }

        .section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
            display: flex;
            align-items: center;
        }

        .section h2 i {
            margin-right: 10px;
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 16px;
            transition: var(--transition);
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(245, 6, 196, 0.1);
        }

        .avatar-container {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid var(--primary-light);
        }

        .file-input {
            position: relative;
            display: inline-block;
        }

        .file-input input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--gray-100);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .file-input-label:hover {
            background-color: var(--gray-200);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .tip-box {
            background-color: var(--gray-100);
            border-left: 4px solid var(--primary);
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }

        .tip-box p {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .tip-box ul {
            padding-left: 20px;
        }

        .tip-box li {
            margin-bottom: 5px;
            color: var(--gray-500);
        }

        .notification {
            padding: 15px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .notification-success {
            background-color: rgba(16, 185, 129, 0.1);
            border-left: 4px solid var(--success);
            color: var(--success);
        }

        .notification-error {
            background-color: rgba(239, 68, 68, 0.1);
            border-left: 4px solid var(--danger);
            color: var(--danger);
        }

        .notification-info {
            background-color: rgba(0, 123, 255, 0.1);
            border-left: 4px solid var(--secondary);
            color: var(--secondary);
        }

        .notification i {
            margin-right: 10px;
            font-size: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .password-info {
            font-size: 14px;
            color: var(--gray-500);
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .tabs {
                flex-direction: column;
            }
            
            .tab {
                width: 100%;
                text-align: center;
                margin-bottom: 5px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .avatar-container {
                flex-direction: column;
                text-align: center;
            }
            
            .avatar {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }
    </style>

    

        <div class="tabs">
            <div class="tab active" data-target="profil">Profil</div>
            <div class="tab" data-target="securite">Sécurité</div>
            <div class="tab" data-target="preferences">Préférences</div>
        </div>

        {{-- Messages --}}
        @if(session('success'))
            <div class="notification notification-success">
                <i>✓</i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="notification notification-error">
                <i>!</i>
                <div>
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Onglet Profil --}}
        <section id="profil" class="section active">
            <h2><i>👤</i> Informations personnelles</h2>
            <form action="{{ route('client.settings.profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="avatar-container">
                    <img src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : asset('images/default-avatar.png') }}"
                         class="avatar" id="avatar-preview">
                    <div class="file-input">
                        <input type="file" name="profile_photo" id="avatar-upload" accept="image/*"/>
                        <label for="avatar-upload" class="file-input-label">Changer la photo</label>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nom complet</label>
                        <input type="text" name="name" id="name" placeholder="Votre nom complet" value="{{ $user->name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Votre adresse email" value="{{ $user->email }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="tel" name="phone" id="phone" placeholder="Votre numéro de téléphone" value="{{ $user->phone }}">
                    </div>
                </div>
                <button type="submit" class="btn">Sauvegarder les modifications</button>
            </form>
        </section>

        {{-- Onglet Sécurité --}}
        <section id="securite" class="section">
            <h2><i>🔒</i> Sécurité</h2>
            <form action="{{ route('client.settings.password') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_password">Mot de passe actuel</label>
                        <input type="password" name="current_password" id="current_password" placeholder="Entrez votre mot de passe actuel">
                    </div>
                    <div class="form-group">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" placeholder="Choisissez un nouveau mot de passe">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmez votre nouveau mot de passe">
                    </div>
                </div>
                <p class="password-info">Minimum 8 caractères, incluant majuscules, chiffres et symboles</p>
                <button type="submit" class="btn">Mettre à jour le mot de passe</button>
            </form>
        </section>

        {{-- Onglet Préférences --}}
        <section id="preferences" class="section">
            <h2><i>⚙️</i> Préférences</h2>
            <p>références de communication.</p>

            <form action="{{ route('client.settings.preferences') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="checkbox-group">
                        <label for="email_promotions">Recevoir des e-mails sur les promotions</label>
                    </div>
                    <div class="checkbox-group">
                        <label for="order_notifications">Notifications sur l'état des commandes</label>
                    </div>
                    <div class="checkbox-group">
                        <label for="newsletter">Newsletter / offres spéciales</label>
                    </div>
                </div>

                <div class="tip-box">
                    <p>Astuce :</p>
                    <ul>
                        <li>Activez les notifications pour suivre vos commandes en temps réel.</li>
                        <li>Recevez nos offres exclusives directement dans votre boîte e-mail.</li>
                        <li>Vous pouvez modifier ces préférences à tout moment.</li>
                    </ul>
                </div>

                <div class="notification notification-info">
                    <i>🔔</i> Nouveauté : bientôt, vous pourrez gérer vos préférences de SMS directement depuis cette page !
                </div>

                <button type="submit" class="btn">Mettre à jour les préférences</button>
            </form>
        </section>

    <script>
        // Navigation par onglets
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Désactiver tous les onglets et sections
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
                
                // Activer l'onglet cliqué et sa section correspondante
                tab.classList.add('active');
                document.getElementById(tab.dataset.target).classList.add('active');
            });
        });

        // Prévisualisation de l'avatar
        const avatarUpload = document.getElementById('avatar-upload');
        const avatarPreview = document.getElementById('avatar-preview');
        
        if (avatarUpload && avatarPreview) {
            avatarUpload.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>

@endsection
