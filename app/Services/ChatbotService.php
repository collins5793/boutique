<?php

namespace App\Services;

use App\Models\ChatbotResponse;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ChatbotService
{
    public function processMessage($message)
    {
        $message = Str::lower($message);
        
        // Détection des salutations
        if ($this->isGreeting($message)) {
            $greetings = [
                "Bonjour ! Je suis votre assistant virtuel. Je peux vous aider à trouver des informations sur nos produits, leurs prix et leur disponibilité. Comment puis-je vous aider aujourd'hui ?",
                "Salut ! Content de vous revoir. Comment puis-je vous aider aujourd'hui ?",
                "Bonjour ! Bienvenue sur notre plateforme. Que recherchez-vous aujourd'hui ?",
                "Hello ! Je suis là pour vous aider. Dites-moi ce dont vous avez besoin."
            ];
            return $greetings[array_rand($greetings)];
        }
        
        // Détection des remerciements
        if ($this->isThanks($message)) {
            $thanks = [
                "Je vous en prie ! N'hésitez pas si vous avez d'autres questions.",
                "Avec plaisir ! N'hésitez pas à me solliciter à nouveau.",
                "De rien ! C'est un plaisir de vous aider.",
                "Tout le plaisir est pour moi ! Revenez quand vous voulez."
            ];
            return $thanks[array_rand($thanks)];
        }

        // Détection des au revoir
        if ($this->isGoodbye($message)) {
            $goodbyes = [
                "Au revoir ! À bientôt sur notre plateforme.",
                "À très bientôt ! N'hésitez pas à revenir si vous avez besoin d'aide.",
                "Merci de votre visite ! À bientôt.",
                "Bonne journée ! N'hésitez pas à revenir si vous avez des questions."
            ];
            return $goodbyes[array_rand($goodbyes)];
        }
        
        // Vérifier les demandes de produits disponibles
        if ($this->isProductsRequest($message)) {
            return $this->handleProductsList($message);
        }
        
        // Vérifier les demandes de prix
        if ($this->isPriceRequest($message)) {
            return $this->handlePriceRequest($message);
        }
        
        // Vérifier les demandes de disponibilité
        if ($this->isStockRequest($message)) {
            return $this->handleStockRequest($message);
        }
        
        // Vérifier les demandes de description
        if ($this->isDescriptionRequest($message)) {
            return $this->handleDescriptionRequest($message);
        }
        
        // Vérifier les demandes de catégories
        if ($this->isCategoryRequest($message)) {
            return $this->handleCategoryRequest($message);
        }

        // Vérifier les demandes de promotions
        if ($this->isPromotionRequest($message)) {
            return $this->handlePromotionRequest($message);
        }

        // Vérifier les demandes de nouveautés
        if ($this->isNewProductsRequest($message)) {
            return $this->handleNewProductsRequest($message);
        }

        // Vérifier les demandes de meilleures ventes
        if ($this->isBestSellersRequest($message)) {
            return $this->handleBestSellersRequest($message);
        }
        
        // Rechercher dans les réponses prédéfinies
        $response = $this->checkPredefinedResponses($message);
        if ($response) {
            return $response;
        }
        
        // Réponse par défaut plus utile
        $defaultResponses = [
            "Je n'ai pas bien compris votre demande. Voici ce que je peux faire :
            - Vous informer sur nos produits disponibles
            - Donner les prix de nos articles
            - Vérifier la disponibilité en stock
            - Donner des descriptions détaillées
            - Vous orienter vers des catégories spécifiques
            - Vous informer sur les promotions en cours
            - Vous montrer nos nouveautés

            Pouvez-vous reformuler votre question ?",

            "Désolé, je n'ai pas saisi votre demande. Je peux vous aider avec:
            - Les informations produits
            - Les prix et promotions
            - La disponibilité en stock
            - Les descriptions détaillées
            - Les catégories de produits

            Que souhaitez-vous savoir ?",

            "Je ne suis pas sûr de comprendre. Je peux vous renseigner sur:
            - Nos produits et leurs caractéristiques
            - Les tarifs et réductions
            - Le stock disponible
            - Les différentes catégories
            - Les nouveautés

            Comment puis-je vous aider ?"
        ];

        return $defaultResponses[array_rand($defaultResponses)];
    }
    
    private function isGreeting($message)
    {
        return Str::contains($message, ['bonjour', 'salut', 'coucou', 'hello', 'hi', 'yo', 'bonsoir', 'hey']);
    }
    
    private function isThanks($message)
    {
        return Str::contains($message, ['merci', 'thanks', 'remercie', 'cool', 'super', 'parfait', 'génial', 'parfait','d\'accord','ok']);
    }

    private function isGoodbye($message)
    {
        return Str::contains($message, ['au revoir', 'bye', 'a plus', 'à bientôt', 'adieu', 'goodbye', 'ciao']);
    }
    
    private function isProductsRequest($message)
    {
        return Str::contains($message, [
            'produit', 'article', 'item', 'produits', 'articles', 
            'disponible', 'disponibles', 'liste', 'catalogue', 'inventaire',
            'montre', 'montrez', 'voir', 'quels', 'quelles', 'quoi', 'que', 'qu\'est-ce'
        ]);
    }
    
    private function isPriceRequest($message)
    {
        return Str::contains($message, ['prix', 'cout', 'combien', 'coûte', 'coût', 'tarif', 'euro', '€', 'value', 'coûter']);
    }
    
    private function isStockRequest($message)
    {
        return Str::contains($message, ['stock', 'disponible', 'disponibilité', 'livraison', 'acheter', 'commander', 'en stock', 'rupture']);
    }
    
    private function isDescriptionRequest($message)
    {
        return Str::contains($message, ['description', 'détail', 'caractéristique', 'info', 'information', 'décrire', 'spécification', 'détails']);
    }
    
    private function isCategoryRequest($message)
    {
        return Str::contains($message, ['catégorie', 'type', 'genre', 'sorte', 'variété', 'gamme', 'collection']);
    }

    private function isPromotionRequest($message)
    {
        return Str::contains($message, ['promotion', 'réduction', 'solde', 'soldes', 'discount', 'rabais', 'offre', 'spécial', 'réduit']);
    }

    private function isNewProductsRequest($message)
    {
        return Str::contains($message, ['nouveau', 'nouveauté', 'nouveautés', 'récents', 'dernier', 'derniers', 'neuf', 'récent']);
    }

    private function isBestSellersRequest($message)
    {
        return Str::contains($message, ['meilleur', 'populaire', 'tendance', 'vente', 'best-seller', 'best seller', 'top', 'préféré', 'aimé']);
    }
    
    private function handleProductsList($message)
    {
        // Vérifier si l'utilisateur demande des produits spécifiques à une catégorie
        $category = $this->extractCategoryFromMessage($message);
        
        if ($category) {
            $products = Product::where('status', 'active')
                            ->where('category_id', $category->id)
                            ->take(5)
                            ->get();
            
            if ($products->count() === 0) {
                return "Nous n'avons aucun produit disponible dans la catégorie {$category->name} pour le moment.";
            }
            
            $response = "Voici nos produits disponibles dans la catégorie {$category->name} :\n";
        } else {
            $products = Product::where('status', 'active')->take(5)->get();
            
            if ($products->count() === 0) {
                return "Nous n'avons aucun produit disponible pour le moment.";
            }
            
            $response = "Voici quelques-uns de nos produits disponibles :\n";
        }
        
        foreach ($products as $product) {
            $response .= "• {$product->name} - {$product->price}€";
            if ($product->discount_price && $product->discount_price < $product->price) {
                $response .= " (Promo: {$product->discount_price}€)";
            }
            if ($product->stock_quantity > 0) {
                $response .= " ✅ En stock";
            } else {
                $response .= " ❌ Rupture";
            }
            $response .= "\n";
        }
        
        $totalProducts = Product::where('status', 'active')->count();
        if ($totalProducts > 5) {
            $response .= "\nNous avons {$totalProducts} produits au total. Souhaitez-vous des informations sur un produit spécifique ?";
        } else {
            $response .= "\nSouhaitez-vous plus d'informations sur un produit spécifique ?";
        }
        
        return $response;
    }
    
    private function handlePriceRequest($message)
    {
        $product = $this->extractProductFromMessage($message);
        
        if ($product) {
            $priceInfo = "Le prix de \"{$product->name}\" est de {$product->price} €";
            
            if ($product->discount_price && $product->discount_price < $product->price) {
                $discount = round((($product->price - $product->discount_price) / $product->price) * 100);
                $priceInfo .= " (Promotion : {$product->discount_price} €, soit {$discount}% de réduction !)";
            }
            
            $priceInfo .= ". ";
            
            if ($product->stock_quantity > 0) {
                $priceInfo .= "Il est actuellement en stock. 📦";
            } else {
                $priceInfo .= "Malheureusement, il est en rupture de stock pour le moment. 😔";
            }
            
            return $priceInfo;
        }
        
        return "Pour quel produit souhaitez-vous connaître le prix ? Voici quelques produits : " . 
               $this->getRandomProductNames(3);
    }
    
    private function handleStockRequest($message)
    {
        $product = $this->extractProductFromMessage($message);
        
        if ($product) {
            $status = $product->stock_quantity > 0 ? 
                "en stock ({$product->stock_quantity} disponibles) 📦" : 
                "en rupture de stock 😔";
            return "Le produit \"{$product->name}\" est actuellement {$status}.";
        }
        
        return "Pour quel produit souhaitez-vous connaître la disponibilité ? " . 
               $this->getRandomProductNames(2);
    }
    
    private function handleDescriptionRequest($message)
    {
        $product = $this->extractProductFromMessage($message);
        
        if ($product) {
            $description = "**{$product->name}**\n";
            $description .= "Prix : {$product->price} €";
            
            if ($product->discount_price && $product->discount_price < $product->price) {
                $description .= " (Promo: {$product->discount_price} €)";
            }
            
            $description .= "\n\nDescription : " . Str::limit($product->description, 200);
            
            if ($product->stock_quantity > 0) {
                $description .= "\n\n✅ Disponible en stock";
            } else {
                $description .= "\n\n❌ Actuellement en rupture";
            }
            
            $description .= "\n\nSouhaitez-vous autre chose ?";
            
            return $description;
        }
        
        return "De quel produit souhaitez-vous connaître les caractéristiques ? " . 
               $this->getRandomProductNames(2);
    }
    
    private function handleCategoryRequest($message)
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->get();
        
        if ($categories->count() === 0) {
            return "Nous n'avons pas encore de catégories définies.";
        }
        
        $response = "Voici nos catégories principales :\n";
        foreach ($categories as $category) {
            $response .= "• {$category->name}";
            if ($category->subcategories->count() > 0) {
                $response .= " (sous-catégories: " . $category->subcategories->pluck('name')->implode(', ') . ")";
            }
            $response .= "\n";
        }
        
        $response .= "\nDans quelle catégorie souhaitez-vous naviguer ?";
        
        return $response;
    }

    private function handlePromotionRequest($message)
    {
        $promoProducts = Product::where('status', 'active')
                            ->whereNotNull('discount_price')
                            ->where('discount_price', '<', \DB::raw('price'))
                            ->take(5)
                            ->get();
        
        if ($promoProducts->count() === 0) {
            return "Nous n'avons pas de promotions en ce moment. Revenez bientôt pour découvrir nos offres spéciales !";
        }
        
        $response = "🎉 Voici nos promotions actuelles :\n";
        foreach ($promoProducts as $product) {
            $discount = round((($product->price - $product->discount_price) / $product->price) * 100);
            $response .= "• {$product->name} - {$product->price}€ → {$product->discount_price}€ (-{$discount}%)";
            if ($product->stock_quantity > 0) {
                $response .= " ✅";
            } else {
                $response .= " ❌";
            }
            $response .= "\n";
        }
        
        $response .= "\nProfitez-en tant qu'il en reste !";
        
        return $response;
    }

    private function handleNewProductsRequest($message)
    {
        $newProducts = Product::where('status', 'active')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        if ($newProducts->count() === 0) {
            return "Nous n'avons pas de nouveaux produits pour le moment.";
        }
        
        $response = "🆕 Voici nos dernières nouveautés :\n";
        foreach ($newProducts as $product) {
            $response .= "• {$product->name} - {$product->price}€";
            if ($product->discount_price && $product->discount_price < $product->price) {
                $response .= " (Promo: {$product->discount_price}€)";
            }
            if ($product->stock_quantity > 0) {
                $response .= " ✅";
            } else {
                $response .= " ❌";
            }
            $response .= "\n";
        }
        
        $response .= "\nSouhaitez-vous plus d'informations sur l'un de ces produits ?";
        
        return $response;
    }

    private function handleBestSellersRequest($message)
    {
        // Simuler des best-sellers (vous pourriez avoir un champ 'sales_count' dans votre table products)
        $bestSellers = Product::where('status', 'active')
                            ->inRandomOrder() // À remplacer par ->orderBy('sales_count', 'desc') si vous avez ce champ
                            ->take(5)
                            ->get();
        
        if ($bestSellers->count() === 0) {
            return "Nous n'avons pas encore de best-sellers identifiés.";
        }
        
        $response = "🔥 Voici nos best-sellers du moment :\n";
        foreach ($bestSellers as $product) {
            $response .= "• {$product->name} - {$product->price}€";
            if ($product->discount_price && $product->discount_price < $product->price) {
                $response .= " (Promo: {$product->discount_price}€)";
            }
            if ($product->stock_quantity > 0) {
                $response .= " ✅";
            } else {
                $response .= " ❌";
            }
            $response .= "\n";
        }
        
        $response .= "\nCes produits plaisent beaucoup à nos clients !";
        
        return $response;
    }
    
    private function checkPredefinedResponses($message)
    {
        $responses = ChatbotResponse::all();
        
        foreach ($responses as $response) {
            if ($response->keywords) {
                foreach ($response->keywords as $keyword) {
                    // Recherche plus permissive
                    if (Str::contains(Str::lower($message), Str::lower($keyword))) {
                        return $response->answer;
                    }
                }
            }
            
            // Vérifier aussi dans la question
            if (Str::contains(Str::lower($message), Str::lower($response->question))) {
                return $response->answer;
            }
        }
        
        return null;
    }
    
    private function extractProductFromMessage($message)
    {
        $products = Product::where('status', 'active')->get();
        
        foreach ($products as $product) {
            // Vérification plus permissive
            $productName = Str::lower($product->name);
            $message = Str::lower($message);
            
            if (Str::contains($message, $productName)) {
                return $product;
            }
            
            // Vérifier les mots partiels
            $productWords = explode(' ', $productName);
            foreach ($productWords as $word) {
                if (strlen($word) > 3 && Str::contains($message, $word)) {
                    return $product;
                }
            }
        }
        
        return null;
    }

    private function extractCategoryFromMessage($message)
    {
        $categories = Category::all();
        
        foreach ($categories as $category) {
            $categoryName = Str::lower($category->name);
            $message = Str::lower($message);
            
            if (Str::contains($message, $categoryName)) {
                return $category;
            }
            
            // Vérifier les mots partiels
            $categoryWords = explode(' ', $categoryName);
            foreach ($categoryWords as $word) {
                if (strlen($word) > 3 && Str::contains($message, $word)) {
                    return $category;
                }
            }
        }
        
        return null;
    }
    
    private function getRandomProductNames($count = 3)
    {
        $products = Product::where('status', 'active')->inRandomOrder()->take($count)->get();
        
        if ($products->count() === 0) {
            return "Aucun produit disponible.";
        }
        
        $names = [];
        foreach ($products as $product) {
            $names[] = $product->name;
        }
        
        return implode(', ', $names);
    }
}