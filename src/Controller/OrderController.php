<?php

namespace App\Controller;


use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/orders')]
class OrderController extends AbstractController
{

    // get all orders
    #[Route('/', name: 'get_all_orders', methods: ['GET'])]
    public function getAllOrders(#[CurrentUser] ?User $user, OrderRepository $orderRepository, EntityManagerInterface $entityManager): Response
    {
        // get all orders from current user
        $orders = $orderRepository->findBy(['user' => $user]);

        if (!$orders) {
            return new JsonResponse(['error' => 'Orders not found'], Response::HTTP_NOT_FOUND);
        }

        $arrayOfOrders = [];

        foreach ($orders as $order) {
            $array_products = $order->getProducts();

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

            $arrayOfOrders[] = [
                'id' => $order->getId(),
                'total_price' => $order->getTotalprice(),
                'created_at' => $order->getCreatedAt(),
                'products' => $products,
            ];
        }

        return new JsonResponse($arrayOfOrders, Response::HTTP_OK);
    }

    // get order by id

    #[Route('/{order_id}', name: 'get_order_by_id', methods: ['GET'])]
    public function getOrderById(#[CurrentUser] ?User $user,OrderRepository $orderRepository, EntityManagerInterface $entityManager, int $order_id ): Response
    {
        $order = $orderRepository->find($order_id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        if ($order->getUser() !== $user) {
            return new JsonResponse(['error' => 'You are not allowed to see this order'], Response::HTTP_FORBIDDEN);
        }

        $array_products = $order->getProducts();

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

        $arrayOfOrder = [
            'id' => $order->getId(),
            'total_price' => $order->getTotalprice(),
            'created_at' => $order->getCreatedAt(),
            'products' => $products,
        ];

        return new JsonResponse($arrayOfOrder, Response::HTTP_OK);
    }






}
