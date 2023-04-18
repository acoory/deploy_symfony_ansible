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

    #[Route('/api/product/{id}', name: 'app_product_show')]
    public function find_by_id(ProductRepository $productRepository, $id): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
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
            throw $this->createNotFoundException(
                'No products found'
            );
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

    #[Route('/api/products/create', name: 'app_api_product_create', methods: ['POST'])]
        public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse {
            $data=json_decode($request->getContent(),true);

            if (!$data['name'] || !$data['price'] || !$data['description'] || !$data['photo']) {
                // throw exception invalid argument
                throw $this->createNotFoundException(
                    'Missing arguments'
                );

            }

            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setDescription($data['description']);
            $product->setPhoto($data['photo']);

            $entityManager->persist($product);

            $entityManager->flush();

            return $this->json([
                'message' => 'Product created successfully',
            ]);

        }

    #[Route('/api/products/update/{id}', name: 'app_api_product_update', methods: ['PUT'])]
        public function update(EntityManagerInterface $entityManager, Request $request, $id): JsonResponse {
            $data=json_decode($request->getContent(),true);

            $product = $entityManager->getRepository(Product::class)->find($id);
            if (!$product) {
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
            }

            if (!$data['name'] || !$data['price'] || !$data['description'] || !$data['photo']) {
                // throw exception invalid argument
                throw $this->createNotFoundException(
                    'Missing arguments'
                );

            }
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setDescription($data['description']);
            $product->setPhoto($data['photo']);

            $entityManager->persist($product);

            $entityManager->flush();

            return $this->json([
                'message' => 'Product updated successfully',
            ]);
        }

    #[Route('/api/products/delete/{id}', name: 'app_api_product_delete', methods: ['DELETE'])]
        public function delete(EntityManagerInterface $entityManager, $id): JsonResponse {
            $product = $entityManager->getRepository(Product::class)->find($id);

            if (!$product) {
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
            }

            $entityManager->remove($product);

            $entityManager->flush();

            return $this->json([
                'message' => 'Product deleted successfully',
            ]);
        }




}
