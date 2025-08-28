
@extends('layouts.livreurs.livreur')

@section('content')

<style>
      :root {
            --primary: #7c3aed;
            --primary-light: #8b5cf6;
            --secondary: #0ea5e9;
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
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        margin-bottom: 25px;
        overflow: hidden;
    }
    
    .support-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        text-align: center;
    }
    
    .support-body {
        padding: 30px;
    }
    
    .contact-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }
    
    .faq-item {
        border-bottom: 1px solid #eee;
        padding: 20px 0;
    }
    
    .faq-question {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    
    .faq-answer {
        color: #666;
        line-height: 1.6;
    }
    
    .btn-support {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-support:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    
    .urgent-contact {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
    }
</style>

<div class="support-container">
    <div class="support-card">
        <div class="support-header">
            <h1><i class="fas fa-headset me-3"></i>Support Livreur</h1>
            <p class="mb-0">Nous sommes là pour vous aider 24h/24</p>
        </div>
        
        <div class="support-body">
            <!-- Section Contact Urgent -->
            <div class="urgent-contact">
                <h4><i class="fas fa-exclamation-triangle me-2"></i>Urgence Livraison</h4>
                <p>Problème urgent lors d'une livraison ? Contactez-nous immédiatement :</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="tel:+22901020304" class="btn btn-danger">
                        <i class="fas fa-phone me-2"></i>Appeler Urgence
                    </a>
                    <a href="https://wa.me/22901020304" class="btn btn-success">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </a>
                </div>
            </div>

            <!-- Formulaire de Contact -->
            <div class="mb-5">
                <h3 class="mb-4">Envoyer un message au support</h3>
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
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Niveau d'urgence *</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="urgence" value="normal" id="normal" checked>
                            <label class="form-check-label" for="normal">Normal (réponse sous 24h)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="urgence" value="urgent" id="urgent">
                            <label class="form-check-label" for="urgent">Urgent (réponse immédiate)</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control" rows="5" required 
                                  placeholder="Décrivez votre problème en détail..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-support">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                    </button>
                </form>
            </div>

            <!-- FAQ -->
            <div class="mb-5">
                <h3 class="mb-4">Questions Fréquentes</h3>
                @foreach($faqCategories as $category => $faq)
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="fas fa-question-circle me-2 text-primary"></i>{{ $faq['question'] }}
                    </div>
                    <div class="faq-answer">
                        {{ $faq['reponse'] }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Contacts -->
            <div>
                <h3 class="mb-4">Contacts du Support</h3>
                <div class="row">
                    @foreach($contacts as $contact)
                    <div class="col-md-6 mb-3">
                        <div class="contact-card">
                            <h5>{{ $contact['service'] }}</h5>
                            <p class="mb-1"><i class="fas fa-phone me-2"></i> {{ $contact['telephone'] }}</p>
                            <p class="mb-1"><i class="fas fa-envelope me-2"></i> {{ $contact['email'] }}</p>
                            <p class="mb-0"><i class="fas fa-clock me-2"></i> {{ $contact['horaires'] }}</p>
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
    Swal.fire({
        icon: 'success',
        title: 'Succès !',
        text: '{{ session('success') }}',
        timer: 3000
    });
</script>
@endif
@endsection
