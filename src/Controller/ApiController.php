<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Json;

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

  
    #[Route('/api/users', name: 'app_api_user_create', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $data=json_decode($request->getContent(),true);

        // verify if the user already exists with the same email
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($user) {
           return new JsonResponse("User already exists", 409);
        }

        $user = new User();
        $hashedPassword = $userPasswordHasher->hashPassword($user, $data['password']);
        $user->setEmail($data['email']);
        $user->setPassword($hashedPassword);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setRoles(['ROLE_USER']);

        // create a new panier for the user
        $panier = new Panier();
        $panier->setUser($user);
        $panier->setTotalPrice(0);
        $panier->setCreationDate(new \DateTime());
        $panier->setProducts([]);


        $entityManager->persist($panier);
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

    #[Route('/api/users', name: 'app_api_user', methods: ['PUT'])]

    public function update(#[CurrentUser] ?User $user, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        if (!$user) {
            return $this->json([
                'message' => 'You are not allowed to update this user',
            ], 401);
        }
       // update user data if fields are not empty
        $data=json_decode($request->getContent(),true);
        if(isset($data['email']) && !empty($data['email'])){
            $user->setEmail($data['email']);
        }
        if(isset($data['firstname']) && !empty($data['firstname'])){
            $user->setFirstname($data['firstname']);
        }
        if(isset($data['lastname']) && !empty($data['lastname'])){
            $user->setLastname($data['lastname']);
        }
        if(isset($data['password']) && !empty($data['password'])){
            $user->setPassword($data['password']);
        }

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
    public function show(User $user, $id, UserRepository $userRepository): JsonResponse
    {

        $is_user = $userRepository->find($id);

        if (!$is_user) {
            return new JsonResponse([
                'error' => 'User not found'
            ], 404);


        }
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

