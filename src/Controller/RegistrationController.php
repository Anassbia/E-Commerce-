<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Service\UserRegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'store_register', methods: ['POST'])]
    public function register(Request $request, UserRegistrationService $registrationService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user, [
            'action' => $this->generateUrl('store_register'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('store/login.html.twig', [
                'error' => null,
                'lastUsername' => '',
                'registrationForm' => $form->createView(),
            ], new Response(status: Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        $registrationService->register($user, (string) $form->get('plainPassword')->getData());
        $this->addFlash('success', 'Your account has been created. You can now log in.');

        return $this->redirectToRoute('store_login');
    }
}
