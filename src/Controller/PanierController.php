<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Panier;
use App\Entity\Product;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{

    #[Route('/api/panier/{user_id}', name: 'app_panier', methods: ['GET'])]
    public function getPanierByUserId(int $user_id, PanierRepository $panierRepository): JsonResponse
    {
        if (!$this->getUser()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Vous devez être connecté pour accéder à cette ressource',
            ], Response::HTTP_UNAUTHORIZED);
        }
        // Récupérer le panier de l'utilisateur en fonction de son ID
        $panier = $panierRepository->findOneBy(['user' => $user_id]);

        // Si le panier n'existe pas encore, retourner une erreur
        if (!$panier) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Aucun panier pour l\'utilisateur avec cet ID',
            ], Response::HTTP_NOT_FOUND);
        }

        $new_panier = [
            'id' => $panier->getId(),
            'total_price' => $panier->getTotalPrice(),
            'creation_date' => $panier->getCreationDate(),
            'products' =>  $panier->getProducts()
        ];

        // Retourner une réponse JSON contenant le panier
        return new JsonResponse([
            'success' => true,
            'panier' => $new_panier,
        ]);
    }

    #[Route('/api/panier/create', name: 'app_panier_create')]
        public function create(EntityManagerInterface $entityManager): JsonResponse {
            if (!$this->getUser()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour créer un panier',
                ]);
            }

            $panier = new Panier();

            $panier-> setTotalPrice(0);
            $panier-> setCreationDate(new \DateTime());
            $panier-> setUser($this->getUser());
            $panier-> setProducts([]);

            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->json([
                'message' => 'Panier created successfully',
            ]);
        }


    // add product to panier
    #[Route('/api/panier/add/{product_id}', methods: ['POST'])]
        public function addProduct(int $product_id, Request $request, EntityManagerInterface $entityManager, Security $security): JsonResponse {

            if (!$this->getUser()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour ajouter un produit au panier',
                ]);
            }
            // Récupérer l'utilisateur en cours à partir de la requête
            $user = $security->getUser();


            // Récupérer le panier de l'utilisateur en cours
            $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]);

            // Si le panier n'existe pas encore, retourner une erreur
            if (!$panier) {
               return new JsonResponse([
                   'success' => false,
                   'message' => 'Aucun panier pour l\'utilisateur',
               ]);
            }

            // Ajouter le produit au panier
            $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $product_id]);

            if (!$product) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Aucun produit avec cet ID',
                ]);
            }

            $price = $product->getPrice();


            // Ajouter le produit au panier
            $products = $panier->getProducts();
            $products[] += $product->getId();
            $panier->setProducts($products);


            // Mettre à jour le prix total du panier
            $totalPrice = $panier->getTotalPrice();
            $totalPrice += $price;
            $panier->setTotalPrice($totalPrice);

            // Mettre à jour la date de création du panier
            if (!$panier->getCreationDate()) {
                $panier->setCreationDate(new \DateTime());
            }

            // Sauvegarder le panier
            $entityManager->persist($panier);
            $entityManager->flush();

            // Retourner une réponse JSON
            return new JsonResponse([
                'success' => true,
                'message' => 'Produit ajouté au panier avec succès',
                'panier' => $panier,
            ]);
        }

    // remove product from panier
    #[Route('/api/panier/remove/{product_id}', methods: ['POST'])]
        public function removeProduct(Request $request, EntityManagerInterface $entityManager, Security $security, int $product_id): JsonResponse
        {
            // Récupérer l'utilisateur en cours à partir de la requête
            $user = $security->getUser();

            // Récupérer le panier de l'utilisateur en cours
            $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]);

            // Si le panier n'existe pas encore, retourner une erreur
            if (!$panier) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Aucun panier pour l\'utilisateur',
                ]);
            }



            // Supprimer le produit du panier
            $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $product_id]);
            $product_price = $product->getPrice();
            $product = $product->getId();
            if (!$product) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Aucun produit avec cet ID',
                ]);
            }
            $products = $panier->getProducts();
            $index = array_search($product, $products);
            if ($index !== false) {
                array_splice($products, $index, 1);
            }
            $panier->setProducts($products);


            // Mettre à jour le prix total du panier
            $totalPrice = $panier->getTotalPrice();
            $totalPrice -= $product_price;
            $panier->setTotalPrice($totalPrice);

            // Mettre à jour la date de création du panier
            if (!$panier->getCreationDate()) {
                $panier->setCreationDate(new \DateTime());
            }

            // Sauvegarder le panier
            $entityManager->persist($panier);
            $entityManager->flush();

            // Retourner une réponse JSON
            return new JsonResponse([
                'success' => true,
                'message' => 'Produit supprimé du panier avec succès',
                'panier' => $panier,
            ]);
        }

        // remove all products from panier
    #[Route('/api/panier/removeAll', methods: ['POST'])]

        public function removeAllProducts(Request $request, EntityManagerInterface $entityManager, Security $security): JsonResponse {

        if (!$this->getUser()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Vous devez être connecté pour supprimer tous les produits du panier',
            ]);
        }

            // Récupérer l'utilisateur en cours à partir de la requête
            $user = $security->getUser();

            // Récupérer le panier de l'utilisateur en cours
            $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]);

            // Si le panier n'existe pas encore, retourner une erreur
                if (!$panier) {
                    return new JsonResponse([
                        'success' => false,
                        'message' => 'Aucun panier pour l\'utilisateur',
                    ]);
                }

            // Supprimer tous les produits du panier
            $panier->setProducts([]);

            // Mettre à jour le prix total du panier
            $panier->setTotalPrice(0);

            // Mettre à jour la date de création du panier
                if (!$panier->getCreationDate()) {
                    $panier->setCreationDate(new \DateTime());
                }

            // Sauvegarder le panier
            $entityManager->persist($panier);
            $entityManager->flush();

            // Retourner une réponse JSON
            return new JsonResponse([
                'success' => true,
                'message' => 'Tous les produits ont été supprimés du panier avec succès',
                'panier' => $panier,
            ]);
        }

        // validate panier : create order and empty panier
    #[Route('/api/panier/validate', methods: ['POST'])]

        public function validatePanier(Request $request, EntityManagerInterface $entityManager, Security $security): JsonResponse {

        if (!$this->getUser()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Vous devez être connecté pour valider le panier',
            ]);
        }

        // Récupérer l'utilisateur en cours à partir de la requête
        $user = $security->getUser();

        // Récupérer le panier de l'utilisateur en cours
        $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]);

        if ($panier->getProducts() == []) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Le panier est vide',
            ]);
        }

        // Si le panier n'existe pas encore, retourner une erreur
        if (!$panier) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Aucun panier pour l\'utilisateur',
            ]);
        }

        // Créer une nouvelle commande
        $order = new Orders();
        $order->setUser($user);
        $order->setProducts($panier->getProducts());
        $order->setTotalPrice($panier->getTotalPrice());
        $order->setCreatedAt(new \DateTime());

        // Sauvegarder la commande
        $entityManager->persist($order);
        $entityManager->flush();

        // Vider le panier
        $panier->setProducts([]);
        $panier->setTotalPrice(0);
        $panier->setCreationDate(new \DateTime());

        // Sauvegarder le panier
        $entityManager->persist($panier);
        $entityManager->flush();

        // Retourner une réponse JSON
        return new JsonResponse([
            'success' => true,
            'message' => 'Commande créée avec succès',
            'order' => $order,
        ]);

    }

}
