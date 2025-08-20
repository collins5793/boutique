<?php

namespace Database\Seeders;

use App\Models\ChatbotResponse;
use Illuminate\Database\Seeder;

class ChatbotResponseSeeder extends Seeder
{
    public function run(): void
    {
        $responses = [
            [
                'question' => 'Comment puis-vous m\'aider?',
                'answer' => 'Je peux vous renseigner sur nos produits, leurs prix, leur disponibilité et leurs caractéristiques. Je peux aussi vous parler de nos promotions, nouveautés et best-sellers! Dites-moi simplement ce que vous cherchez !',
                'keywords' => ['aide', 'assistance', 'help', 'aider', 'faire', 'peux', 'peut', 'aide-moi'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Quelles sont vos heures d\'ouverture?',
                'answer' => 'Notre service client est disponible 24h/24 et 7j/7 grâce à ce chatbot! Pour les questions complexes, notre équipe humaine est joignable du lundi au vendredi de 9h à 18h, et le samedi de 10h à 16h.',
                'keywords' => ['heure', 'ouverture', 'contact', 'jour', 'ouvert', 'fermé', 'horaire', 'disponible', 'joignable'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Comment commander un produit?',
                'answer' => 'Pour commander : 1) Choisissez votre produit 2) Ajoutez-le au panier 3) Validez votre commande 4) Paiement sécurisé. Simple et rapide ! Vous pouvez aussi créer un compte pour suivre vos commandes et bénéficier d\'avantages exclusifs.',
                'keywords' => ['commander', 'acheter', 'panier', 'paiement', 'commande', 'achat', 'comment', 'procédure', 'processus'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Quels sont vos modes de livraison?',
                'answer' => 'Nous proposons plusieurs options : Livraison standard (3-5 jours), express (24-48h) et point relais. Les frais varient selon le mode choisi et le montant de votre commande. La livraison est offerte à partir de 50€ d\'achat !',
                'keywords' => ['livraison', 'livrer', 'expédition', 'transport', 'delai', 'délai', 'frais', 'livraison offerte', 'frais de port'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Qui êtes-vous?',
                'answer' => 'Je suis l\'assistant virtuel de la boutique, toujours disponible pour vous aider à trouver le produit parfait, répondre à vos questions et vous guider dans votre shopping en ligne !',
                'keywords' => ['qui', 'êtes', 'es', 'tu', 'robot', 'assistant', 'bot', 'présente', 'identité'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Que proposez-vous?',
                'answer' => 'Nous proposons une large sélection de produits de qualité dans différentes catégories. Je peux vous aider à naviguer dans notre catalogue, trouver des promotions, découvrir nos nouveautés ou vous renseigner sur un produit spécifique. Que cherchez-vous?',
                'keywords' => ['proposez', 'offrez', 'vendez', 'vente', 'produits', 'articles', 'catalogue', 'offre', 'sélection'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Comment créer un compte?',
                'answer' => 'Créez votre compte en cliquant sur "Mon compte" en haut à droite, puis "Créer un compte". Remplissez le formulaire avec vos informations et validez. Vous pourrez ainsi suivre vos commandes, gérer vos adresses et bénéficier d\'avantages exclusifs!',
                'keywords' => ['compte', 'créer', 'inscription', 'enregistrement', 'profil', 'inscrire', 's\'enregistrer'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Comment suivre ma commande?',
                'answer' => 'Pour suivre votre commande, connectez-vous à votre compte et allez dans "Mes commandes". Vous y trouverez le statut de livraison et le numéro de suivi. Sans compte, utilisez le numéro de commande reçu par email dans la section "Suivi de commande".',
                'keywords' => ['suivre', 'commande', 'suivi', 'livraison', 'statut', 'où est', 'tracking', 'numéro'],
                'response_type' => 'text'
            ]
        ];

        foreach ($responses as $response) {
            ChatbotResponse::create($response);
        }
    }
}