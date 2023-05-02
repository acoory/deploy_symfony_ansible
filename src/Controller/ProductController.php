<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ProductController extends AbstractController
{
    #[Route('/api/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/api/products/{id}', name: 'app_product_show', methods: ['GET'])]
    public function find_by_id(ProductRepository $productRepository, $id): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            // new jsonreponse with 404 status code and error fields
            return new JsonResponse([
                'error' => 'Product not found',
            ], status: 404);

        }

        return $this->json([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'photo' => $product->getPhoto(),
        ]);
    }

    #[Route('/api/products', name: 'app_api_products', methods: ['GET'])]
    public function find_all(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        if (!$products) {
            // return response appropiate for no products found (404)
            return new JsonResponse([
                'error' => 'Product not found',
            ], status: 404);
        }

        $products = array_map(function ($product) {
            return [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'photo' => $product->getPhoto(),
            ];
        }, $products);

        return $this->json($products);
    }

    #[Route('/api/products', name: 'app_api_product_create', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse {
        $data=json_decode($request->getContent(),true);

        $requiredFields = ['name', 'price', 'description', 'photo'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return new JsonResponse([
                    'error' => "Missing field: $field",
                ], status: 400);
            }
        }

        if (!$this->is_string_and_not_empty($data['description']) || !$this->is_string_and_not_empty($data['name']) || !$this->is_string_and_not_empty($data['photo'])) {
            return new JsonResponse([
                'error' => "Invalid argument: description, name and photo should be non-empty strings",
            ], status: 400);
        }

        if (!$this->is_int_and_not_empty($data['price'])) {
            return new JsonResponse([
                'error' => "Invalid argument: price should be a non-empty double",
            ], status: 400);
        }

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setDescription($data['description']);
        $product->setPhoto($data['photo']);

        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse("Product created successfully", 201);
    }

    function is_string_and_not_empty($field): bool {
        return is_string($field) && !empty($field);
    }

    function is_int_and_not_empty($field): bool {
        return is_double($field) && !empty($field);
    }





    #[Route('/api/products/{id}', name: 'app_api_product_update', methods: ['PUT'])]
        public function update(EntityManagerInterface $entityManager, Request $request, $id): JsonResponse {
            $data=json_decode($request->getContent(),true);

            $product = $entityManager->getRepository(Product::class)->find($id);
            if (!$product) {
               return new JsonResponse ([ 'error' => 'Product not found'], 404);
            }


        // verify format of data if present
            if (isset($data['name']) && !$this->is_string_and_not_empty($data['name'])) {
                return new JsonResponse([
                    'error' => "Invalid argument: name should be a non-empty string",
                ], status: 400);
            }
            if (isset($data['price']) && !$this->is_int_and_not_empty($data['price'])) {
                return new JsonResponse([
                    'error' => "Invalid argument: price should be a non-empty double",
                ], status: 400);
            }
            if (isset($data['description']) && !$this->is_string_and_not_empty($data['description'])) {
                return new JsonResponse([
                    'error' => "Invalid argument: description should be a non-empty string",
                ], status: 400);
            }
            if (isset($data['photo']) && !$this->is_string_and_not_empty($data['photo'])) {
                return new JsonResponse([
                    'error' => "Invalid argument: photo should be a non-empty string",
                ], status: 400);
            }


        // update product with data from request body (if present) and save to database
            $product->setName($data['name'] ?? $product->getName());
            $product->setPrice($data['price'] ?? $product->getPrice());
            $product->setDescription($data['description'] ?? $product->getDescription());
            $product->setPhoto($data['photo'] ?? $product->getPhoto());




            $entityManager->persist($product);

            $entityManager->flush();

            return $this->json([
                'message' => 'Product updated successfully',
            ]);
        }

    #[Route('/api/products/{id}', name: 'app_api_product_delete', methods: ['DELETE'])]
        public function delete(EntityManagerInterface $entityManager, $id): JsonResponse {
            $product = $entityManager->getRepository(Product::class)->find($id);

            if (!$product) {
               return new JsonResponse ([ 'error' => 'Product not found'], 404);
            }

            $entityManager->remove($product);

            $entityManager->flush();

            return $this->json([
                'message' => 'Product deleted successfully',
            ]);
        }

}
