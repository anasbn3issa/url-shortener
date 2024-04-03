<?php

// src/Controller/UrlShortenerController.php

namespace App\Controller;

use App\Entity\Url;
use App\Form\UrlType;
use App\Service\UrlShortenerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UrlShortenerController extends AbstractController
{
    #[Route('/shorten', name: 'app_shorten', methods: ['GET', 'POST'])]
    public function shorten(Request $request, UrlShortenerService $urlShortenerService, EntityManagerInterface $entityManager): Response
    {
        $url = new Url();
        $form = $this->createForm(UrlType::class, $url);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shortCode = $urlShortenerService->generateShortCode();
            $url->setShortCode($shortCode);
            
            $entityManager->persist($url);
            $entityManager->flush();

            // Pass the Url object to the template to show the short code
            return $this->render('shortened_url.html.twig', [
                'url' => $url,
            ]);
        }

        $urls = $entityManager->getRepository(Url::class)->findAll();
        
        return $this->render('form.html.twig', [
            'form' => $form->createView(),
            'urls' => $urls,
        ]);
    }
    
    #[Route('/r/{shortCode}', name: 'redirect_short_code')]
    public function redirectShortCode(string $shortCode, EntityManagerInterface $entityManager): Response
    {
        $urlRepository = $entityManager->getRepository(Url::class);
        $url = $urlRepository->findOneBy(['shortCode' => $shortCode]);

        if (!$url) {
            throw $this->createNotFoundException('The short URL does not exist.');
        }

        return $this->redirect($url->getOriginalUrl());
    }
}
