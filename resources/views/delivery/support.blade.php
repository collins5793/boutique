@extends('layouts.livreurs.livreur')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --primary: #7c3aed;
        --primary-light: #8b5cf6;
        --secondary: #0ea5e9;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1e293b;
        --light-bg: #f8fafc;
        --radius: 12px;
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .support-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .support-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 25px;
        overflow: hidden;
    }
    
    .support-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 30px;
        text-align: center;
    }
    
    .support-header h1 {
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .support-body {
        padding: 30px;
    }
    
    .contact-card {
        background: var(--light-bg);
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid var(--primary);
        transition: transform 0.2s;
    }
    
    .contact-card:hover {
        transform: translateY(-2px);
    }
    
    .faq-item {
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 0;
        transition: background-color 0.2s;
    }
    
    .faq-item:hover {
        background-color: #fafafa;
    }
    
    .faq-question {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .faq-answer {
        color: #64748b;
        line-height: 1.6;
        padding-left: 30px;
    }
    
    .btn-support {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: var(--radius);
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-support:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(124, 58, 237, 0.3);
        color: white;
    }
    
    .urgent-contact {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        border: 2px solid #f59e0b;
        border-radius: var(--radius);
        padding: 20px;
        margin: 25px 0;
        position: relative;
        overflow: hidden;
    }
    
    .urgent-contact::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ef4444, #f59e0b);
    }
    
    .form-control, .form-select {
        border-radius: var(--radius);
        border: 2px solid #e2e8f0;
        padding: 12px 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1);
    }
    
    .contact-method {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        color: #64748b;
    }
    
    .contact-method i {
        width: 20px;
        color: var(--primary);
    }
    
    .support-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .stat-item {
        background: var(--light-bg);
        padding: 15px;
        border-radius: var(--radius);
        text-align: center;
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .support-header {
            padding: 20px;
        }
        
        .support-body {
            padding: 20px;
        }
        
        .support-stats {
            grid-template-columns: 1fr;
        }
        
        .urgent-contact .d-flex {
            flex-direction: column;
            gap: 10px;
        }
        
        .urgent-contact .btn {
            width: 100%;
        }
    }
    
    @media (max-width: 576px) {
        .support-container {
            padding: 15px;
        }
        
        .faq-answer {
            padding-left: 20px;
        }
    }
</style>

<div class="support-container">
    <div class="support-card">
        <div class="support-header">
            <h1><i class="fas fa-headset me-2"></i>Support Livreur</h1>
            <p class="mb-0">Assistance 24h/24 pour vous aider dans vos livraisons</p>
        </div>
        
        <div class="support-body">
            <!-- Statistiques de support -->
            <div class="support-stats">
                <div class="stat-item">
                    <div class="stat-number">< 1h</div>
                    <div class="stat-label">Temps de réponse moyen</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Problèmes résolus</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Disponibilité</div>
                </div>
            </div>

            <!-- Section Contact Urgent -->
            <div class="urgent-contact">
                <h4><i class="fas fa-exclamation-triangle me-2"></i>Urgence Livraison</h4>
                <p class="mb-3">Problème urgent lors d'une livraison ? Contactez-nous immédiatement :</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="tel:+22901020304" class="btn btn-danger">
                        <i class="fas fa-phone me-2"></i>Appeler Urgence
                    </a>
                    <a href="https://wa.me/22901020304" class="btn btn-success">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp Urgent
                    </a>
                    <a href="sms:+22901020304" class="btn btn-secondary">
                        <i class="fas fa-sms me-2"></i>SMS Urgent
                    </a>
                </div>
            </div>

            <!-- Formulaire de Contact -->
            {{-- <div class="mb-5">
                <h3 class="mb-4"><i class="fas fa-envelope me-2"></i>Envoyer un message au support</h3>
                <form action="{{ route('livreur.support.ticket') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sujet *</label>
                            <input type="text" name="sujet" class="form-control" required 
                                   placeholder="Ex: Problème de géolocalisation">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catégorie *</label>
                            <select name="categorie" class="form-select" required>
                                <option value="">Choisir une catégorie</option>
                                <option value="technique">Problème Technique</option>
                                <option value="livraison">Question Livraison</option>
                                <option value="paiement">Question Paiement</option>
                                <option value="application">Problème Application</option>
                                <option value="client">Problème Client</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Niveau d'urgence *</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="urgence" value="normal" id="normal" checked>
                                <label class="form-check-label" for="normal">
                                    <i class="fas fa-clock me-1"></i> Normal (réponse sous 24h)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="urgence" value="urgent" id="urgent">
                                <label class="form-check-label" for="urgent">
                                    <i class="fas fa-exclamation-circle me-1"></i> Urgent (réponse immédiate)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message détaillé *</label>
                        <textarea name="message" class="form-control" rows="5" required 
                                  placeholder="Décrivez votre problème en détail..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pièce jointe (optionnel)</label>
                        <input type="file" name="fichier" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Formats acceptés: images, PDF (max 5MB)</small>
                    </div>
                    
                    <button type="submit" class="btn-support">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                    </button>
                </form>
            </div> --}}

            <!-- FAQ -->
            <div class="mb-5">
                <h3 class="mb-4"><i class="fas fa-question-circle me-2"></i>Questions Fréquentes</h3>
                @foreach($faqCategories as $category => $faq)
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="fas fa-question-circle text-primary"></i>
                        {{ $faq['question'] }}
                    </div>
                    <div class="faq-answer">
                        {{ $faq['reponse'] }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Contacts -->
            <div>
                <h3 class="mb-4"><i class="fas fa-address-book me-2"></i>Contacts du Support</h3>
                <div class="row">
                    @foreach($contacts as $contact)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="contact-card">
                            <h5 class="mb-3">{{ $contact['service'] }}</h5>
                            <div class="contact-method">
                                <i class="fas fa-phone"></i>
                                <span>{{ $contact['telephone'] }}</span>
                            </div>
                            <div class="contact-method">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $contact['email'] }}</span>
                            </div>
                            <div class="contact-method">
                                <i class="fas fa-clock"></i>
                                <span>{{ $contact['horaires'] }}</span>
                            </div>
                            @if(isset($contact['whatsapp']))
                            <div class="contact-method">
                                <i class="fab fa-whatsapp"></i>
                                <span>{{ $contact['whatsapp'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Message envoyé !',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: '{{ $errors->first() }}',
            timer: 3000
        });
    });
</script>
@endif
@endsection