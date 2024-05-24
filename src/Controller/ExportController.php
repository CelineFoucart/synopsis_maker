<?php

namespace App\Controller;

use App\Entity\Synopsis;
use App\Security\Voter\VoterAction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExportController extends AbstractController
{
    #[Route('/export/{id}', name: 'app_export_pdf')]
    public function pdfSynopsisAction(Synopsis $synopsis): Response
    {
        $this->denyAccessUnlessGranted(VoterAction::VIEW, $synopsis);

        $categories = [];
        foreach ($synopsis->getCategories() as $category) {
            $categories[] = $category->getTitle();
        }

        return $this->render('export/synopsis.html.twig', [
            'synopsis' => $synopsis,
            'categories' => $categories,
        ]);
    }
}