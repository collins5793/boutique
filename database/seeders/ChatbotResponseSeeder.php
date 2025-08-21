<?php

namespace Database\Seeders;

use App\Models\ChatbotResponse;
use Illuminate\Database\Seeder;

class ChatbotResponseSeeder extends Seeder
{
    public function run(): void
    {
        $responses = [

            // --- Salutations de base ---
            [
                'question' => 'Bonjour',
                'answer' => 'Bonjour 👋 ! Bienvenue sur notre boutique. Comment puis-je vous aider aujourd’hui ?',
                'keywords' => ['bonjour', 'salut', 'bjr', 'hello', 'coucou'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Merci',
                'answer' => 'Avec plaisir 😊 ! N’hésitez pas si vous avez d’autres questions.',
                'keywords' => ['merci', 'thx', 'thanks'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Au revoir',
                'answer' => 'Au revoir 👋 ! Merci de votre visite et à très bientôt dans notre boutique.',
                'keywords' => ['aurevoir', 'bye', 'à bientôt', 'bonne journée', 'bonne soirée'],
                'response_type' => 'text'
            ],

            // --- Questions fréquentes ---
            [
                'question' => 'Quels articles recommandez-vous pour une occasion ?',
                'answer' => 'Pour un anniversaire, un mariage ou une fête spéciale, nous avons des sélections adaptées 🎁. Voulez-vous que je vous propose une catégorie ?',
                'keywords' => ['recommande', 'occasion', 'anniversaire', 'mariage', 'cadeau', 'fête'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Avez-vous des promotions en cours ?',
                'answer' => 'Oui 🎉 ! Nous proposons régulièrement des réductions et offres spéciales. Souhaitez-vous voir nos promotions actuelles ?',
                'keywords' => ['promo', 'promotion', 'réduction', 'soldes', 'offre', 'bon plan'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Vendez-vous des produits pour enfants ?',
                'answer' => 'Oui 👶 ! Nous avons une section dédiée aux articles pour enfants et bébés.',
                'keywords' => ['enfant', 'bébé', 'kids', 'petit', 'jeune'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Proposez-vous des articles personnalisés ?',
                'answer' => 'Bien sûr ✨ ! Certains produits peuvent être personnalisés (gravure, nom, etc.). Voulez-vous voir les options disponibles ?',
                'keywords' => ['personnalisé', 'gravé', 'custom', 'sur mesure'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Faites-vous des réductions ?',
                'answer' => 'Oui, nous proposons des réductions régulières et des offres spéciales sur certains produits 🛍️.',
                'keywords' => ['réduction', 'promo', 'remise', 'rabais'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Quels sont vos moyens de paiement ?',
                'answer' => 'Nous acceptons les paiements par carte bancaire, Mobile Money (Moov, MTN, etc.), virement bancaire et paiement à la livraison 💳📱.',
                'keywords' => ['paiement', 'payer', 'carte', 'visa', 'mastercard', 'mobile money', 'mtn', 'moov'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Puis-je payer à la livraison ?',
                'answer' => 'Oui ✅ ! Le paiement à la livraison est possible pour certaines zones.',
                'keywords' => ['paiement livraison', 'payer livraison', 'cash à la livraison'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Quels sont vos délais de livraison ?',
                'answer' => 'Les délais dépendent de votre zone 📦 : en général 24-48h pour Cotonou/Calavi et 3-5 jours pour les autres régions.',
                'keywords' => ['délai', 'temps', 'livraison quand', 'combien de jours', 'date livraison'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Faites-vous la livraison à domicile ?',
                'answer' => 'Oui 🚚, nous livrons directement à domicile selon votre adresse.',
                'keywords' => ['livraison domicile', 'chez moi', 'à la maison'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Comment puis-je suivre ma commande ?',
                'answer' => 'Après validation, vous recevrez un numéro de suivi 🔎. Vous pouvez le consulter dans votre espace client ou via notre service client.',
                'keywords' => ['suivi', 'tracking', 'où est ma commande', 'statut'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Que faire si j’ai reçu un article défectueux ?',
                'answer' => 'Nous sommes désolés 🙏. Vous pouvez nous contacter dans les 48h suivant la réception pour un échange ou remboursement.',
                'keywords' => ['défectueux', 'abîmé', 'cassé', 'endommagé', 'problème produit'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Puis-je retourner un produit si je ne suis pas satisfait ?',
                'answer' => 'Oui ✅, vous disposez d’un délai de retour de 7 jours après réception, sous conditions.',
                'keywords' => ['retour', 'rembourser', 'satisfait', 'pas content'],
                'response_type' => 'text'
            ],
            [
                'question' => 'Comment puis-je vous contacter ?',
                'answer' => 'Vous pouvez nous joindre par téléphone, WhatsApp 📱 ou via notre formulaire de contact sur le site.',
                'keywords' => ['contact', 'téléphone', 'whatsapp', 'numéro', 'service client'],
                'response_type' => 'text'
            ],

            // Tu peux continuer à décliner toutes tes 50 questions ici de la même façon...
        ];

        foreach ($responses as $response) {
            ChatbotResponse::create($response);
        }
    }
}
