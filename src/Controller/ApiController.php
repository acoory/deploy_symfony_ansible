<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController


{

    public function __construct(TokenStorageInterface $tokenStorage, JWTTokenManagerInterface $JWTManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->JWTManager = $JWTManager;
    }


    #[Route('/api', name: 'app_api')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    // get all users
    #[Route('/api/users', name: 'app_api_users', methods: ['GET'])]
    public function users(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        $users = array_map(function ($user) {
            return [
                'id' => $user->getId(),
                'name' => $user->getName(),
            ];
        }, $users);

        return $this->json($users);
    }

  
    #[Route('/api/users/create', name: 'app_api_user_create', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validato): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setPassword($userPasswordHasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);

        $errors = $validato->validate($user);

        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Invalid data',
                'errors' => (string) $errors,
            ], 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User created successfully',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
            ]
        ]);
    }

    #[Route('/api/users/{id}', name: 'app_api_user', methods: ['PUT'])]

    public function update(#[CurrentUser] ?User $user, EntityManagerInterface $entityManager, Request $request, AuthenticationUtils $authUtils, $id): JsonResponse
    {
        if (!$user) {
            return $this->json([
                'message' => 'You are not allowed to update this user',
            ], 401);
        }

        $data = json_decode($request->getContent(), true);

        $user = $entityManager->getRepository(User::class)->find($user->getId());

        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
            ]
        ]);

    }

    #[Route('/api/users/{id}', name: 'app_api_user_delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User deleted successfully',
        ]);
    }

    #[Route('/api/users/{id}', name: 'app_api_user_show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        return $this->json([
            'message' => 'User details',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
            ]
        ]);
    }

}

