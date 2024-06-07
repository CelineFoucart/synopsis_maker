<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Synopsis;
use App\Service\WordGenerator;
use App\Security\Voter\VoterAction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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

    #[Route('/export/{id}/word', name: 'app_export_word')]
    public function wordSynopsisAction(Synopsis $synopsis, WordGenerator $wordGenerator): Response
    {
        $this->denyAccessUnlessGranted(VoterAction::VIEW, $synopsis);

        try {
            $file = $wordGenerator->setSynopsis($synopsis)->generate();
            $response = new BinaryFileResponse($file['path']);
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $file['filename']
            );
            $response->deleteFileAfterSend();

            return $response;
        } catch (\Exception $th) {
            $this->addFlash('error', "Le fichier n'a pas pu être généré, car il y a des liens vers des images invalides ou un code HTML invalide.");
            
            return $this->redirectToRoute('app_profile');
        }
    }
}