@extends('layouts.livreurs.livreur')

@section('content')
<style>
    .faq-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .faq-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 20px;
        text-align: center;
        border-radius: 15px 15px 0 0;
    }
    
    .faq-item {
        background: white;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .faq-question {
        padding: 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }
    
    .faq-answer {
        padding: 20px;
        background: white;
        color: #555;
        line-height: 1.6;
    }
    
    .search-box {
        position: relative;
        margin: 30px 0;
    }
    
    .search-box input {
        padding: 15px 20px 15px 50px;
        border-radius: 50px;
        border: 2px solid #e9ecef;
        width: 100%;
        font-size: 16px;
    }
    
    .search-box i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
    }
</style>

<div class="faq-container">
    <div class="faq-header">
        <h1><i class="fas fa-question-circle me-3"></i>FAQ Livreurs</h1>
        <p>Trouvez rapidement des réponses à vos questions</p>
    </div>
    
    <div class="bg-white p-4 rounded-bottom shadow">
        <!-- Barre de recherche -->
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="faqSearch" placeholder="Rechercher dans la FAQ...">
        </div>
        
        <!-- Liste des FAQ -->
        <div id="faqAccordion">
            @foreach($faqs as $index => $faq)
            <div class="faq-item">
                <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}">
                    {{ $faq['question'] }}
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div id="faq{{ $index }}" class="collapse">
                    <div class="faq-answer">
                        {{ $faq['reponse'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Support supplémentaire -->
        <div class="text-center mt-5 p-4 bg-light rounded">
            <h4>Vous ne trouvez pas de réponse ?</h4>
            <p>Contactez notre équipe de support pour une assistance personnalisée</p>
            <a href="{{ route('livreur.support') }}" class="btn btn-primary">
                <i class="fas fa-headset me-2"></i>Contact Support
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche dans la FAQ
    const searchInput = document.getElementById('faqSearch');
    const faqItems = document.querySelectorAll('.faq-item');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question').textContent.toLowerCase();
            const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Animation des icônes
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        });
    });
});
</script>
@endsection