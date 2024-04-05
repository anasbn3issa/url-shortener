<?php

// src/Controller/UrlShortenerController.php

namespace App\Controller;

use App\Entity\Click;
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
    #[Route('/', name: 'analytics_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $urlRepository = $entityManager->getRepository(Url::class);
        $urlStats = $urlRepository->getUrlStats();
        $urls = $entityManager->getRepository(Url::class)->findAll();

        return $this->render('dashboard.html.twig', [
            'urlStats' => $urlStats,
            'urls' => $urls,
        ]);
    }

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

        
        return $this->render('form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/r/{shortCode}', name: 'redirect_short_code')]
    public function redirectShortCode(string $shortCode, EntityManagerInterface $entityManager, Request $request): Response
    {
        $urlRepository = $entityManager->getRepository(Url::class);
        $url = $urlRepository->findOneBy(['shortCode' => $shortCode]);

        if (!$url) {
            throw $this->createNotFoundException('The short URL does not exist.');
        }

        // Create and save the click
        $click = new Click();
        $click->setUrl($url);
        $click->setClickedAt(new \DateTime());
        $click->setSourceIp($request->getClientIp());
        $click->setReferrer($request->headers->get('referer') ?? '');

        $entityManager->persist($click);
        $entityManager->flush();

        return $this->redirect($url->getOriginalUrl());
    }
}
