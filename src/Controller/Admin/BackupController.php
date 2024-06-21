<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\BackupService;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

final class BackupController extends AbstractController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin/backup', name: 'app_admin_backup')]
    public function indexAction(Request $request, BackupService $backupService): Response
    {
        if ($request->isMethod('POST') && $this->isCsrfTokenValid('backup', $request->getPayload()->get('token'))) {
            $status = $backupService->exportData();

            if ($status) {
                $response = new BinaryFileResponse($backupService->getFilePath());
                $response->setContentDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $backupService->getFilename()
                );
                $response->deleteFileAfterSend();

                return $response;
            }
            
            $this->addFlash('danger', "L'export des données a échoué.");
            $url = $this->adminUrlGenerator->setRoute('app_admin_backup')->generateUrl();

            return $this->redirect($url);
        }

        return $this->render('admin/backup.html.twig');
    }
}
