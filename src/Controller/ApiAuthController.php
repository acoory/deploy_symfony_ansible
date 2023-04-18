<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ApiAuthController extends AbstractController
{
    #[Route('/api/auth', name: 'api_auth', methods: ['POST'])]
    public function index(Request $request, AuthenticationUtils $authUtils ): Response
      {
         $data= json_decode($request->getContent(),true);
       
         $username = $data['username'];

    $error = $authUtils->getLastAuthenticationError();



    if (!empty($error)) {
        return $this->json([
            'message' => 'Invalid credentials',
        ], Response::HTTP_UNAUTHORIZED);
    }

    $token = $this->get('lexik_jwt_authentication.encoder')->encode([
        'username' => $username,
        'exp'      => time() + 3600 // Token valide pendant 1 heure
    ]);

   
    return $this->json([
        'user'  => $username,
        'token' => $token,
    ]);
      }
}
