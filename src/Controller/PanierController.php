<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Panier;
use App\Entity\Product;
use App\Repository\PanierRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class PanierController extends AbstractController
{

    #[Route('/api/carts', name: 'app_panier', methods: ['GET'])]
    public function getPanierByUserId(PanierRepository $panierRepository, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->getUser()) {
            return new JsonResponse([
                'error' => "You must be logged in to get a Panier",
            ], 403);
        }
        // Récupérer le panier de l'utilisateur en fonction de son ID
        $panier = $panierRepository->findOneBy(['user' => $this->getUser() ]);

        // Si le panier n'existe pas encore, retourner une erreur
        if (!$panier) {
            return new JsonResponse([
                'error' => "Panier doesn't exist",
            ], 404);
        }

        $array_products = $panier->getProducts();


        $products = [];
        foreach ($array_products as $productId) {
            $product = $entityManager->getRepository(Product::class)->find($productId);
            if ($product) {
                $products[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'description' => $product->getDescription(),
                    'photo' => $product->getPhoto(),
                ];
            }
        }

        $new_panier = [
            'id' => $panier->getId(),
            'total_price' => $panier->getTotalPrice(),
            'creation_date' => $panier->getCreationDate(),
            'products' => $products,
        ];

        // Retourner une réponse JSON contenant le panier
        return new JsonResponse([
            'success' => true,
            'panier' => $new_panier,
        ]);
    }

    #[Route('/api/carts', name: 'app_panier_create', methods: ['POST'])]
        public function create(EntityManagerInterface $entityManager): JsonResponse {
            if (!$this->getUser()) {
                return new JsonResponse([
                    'error' => "You must be logged in to create a Panier",
                ], 403);
            }

            // if an Panier with the same user already exists, return an error
            $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $this->getUser()]);
            if ($panier) {
                // throw error already exists
                return new JsonResponse([
                    'error' => "Panier already exists",
                ], 409);
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
    #[Route('/api/carts/{product_id}', methods: ['POST'])]
        public function addProduct(int $product_id, Request $request, EntityManagerInterface $entityManager, Security $security): JsonResponse {

            if (!$this->getUser()) {
               return new JsonResponse("You must be logged in to add a product to a Panier", 403);
            }
            // Récupérer l'utilisateur en cours à partir de la requête
            $user = $security->getUser();


            // Récupérer le panier de l'utilisateur en cours
            $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]);

            // Si le panier n'existe pas encore, retourner une erreur
            if (!$panier) {
               return new JsonResponse([
                   'error' => "Panier doesn't exist",
               ], 400);
            }

            // Ajouter le produit au panier
            $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $product_id]);

            if (!$product) {
                return new JsonResponse(['error' => "Product not found"], 404);
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
                'message' => 'Produit ajouté au panier avec succès',
            ]);
        }

    // remove product from panier
    #[Route('/api/carts/{product_id}', methods: ['DELETE'])]
        public function removeProduct(Request $request, EntityManagerInterface $entityManager, Security $security, int $product_id): JsonResponse
        {
            // Récupérer l'utilisateur en cours à partir de la requête
            $user = $security->getUser();

            // Récupérer le panier de l'utilisateur en cours
            $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]);

            // Si le panier n'existe pas encore, retourner une erreur
            if (!$panier) {
                return new JsonResponse (['error' => "Panier not found"], 400);
            }



            // Supprimer le produit du panier
            $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $product_id]);

            if (!$product) {
                return new JsonResponse(['error' => "Product not found"], 404);
            }

            $product_price = $product->getPrice();
            $product = $product->getId();
            if (!$product) {
                return new JsonResponse(['error' => "Product not found"], 404);
            }
            $products = $panier->getProducts();

            // verifier si le produit existe dans le panier
            $index = array_search($product, $products);
            if ($index === false) {
                return new JsonResponse(['error' => "Product not found in Panier"], 404);
            }
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
            ]);
        }

        // remove all products from panier
    #[Route('/api/carts', methods: ['DELETE'])]

        public function removeAllProducts(EntityManagerInterface $entityManager, Security $security): JsonResponse {

        if (!$this->getUser()) {
            return new JsonResponse([
                'error' => "You must be logged in to remove all products from a Panier",
            ], 403);
        }

            // Récupérer l'utilisateur en cours à partir de la requête
            $user = $security->getUser();

            // Récupérer le panier de l'utilisateur en cours
            $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]);

                // Si le panier n'existe pas encore, retourner une erreur
                if (!$panier) {
                   return new JsonResponse(['error' => "Panier not found"], 400);
                }

                // Si le panier est vide, retourner une erreur
                if (empty($panier->getProducts())) {
                    return new JsonResponse(['error' => "Panier is already empty"], 409);
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
            ]);
        }

        // validate panier : create order and empty panier
    #[Route('/api/carts/validate', methods: ['GET'])]

        public function validatePanier(EntityManagerInterface $entityManager, Security $security): JsonResponse {

        if (!$this->getUser()) {
            return new JsonResponse([
                'error' => "You must be logged in to validate a Panier",
            ], 403);
        }

        // Récupérer l'utilisateur en cours à partir de la requête
        $user = $security->getUser();

        // Récupérer le panier de l'utilisateur en cours
        $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]);

        if ($panier->getProducts() == []) {
          return new JsonResponse(['error' => "Panier vide"], 400);
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
