<?php

namespace App\Controller;

use App\Entity\Church;
use App\Form\ChurchType;
use App\Repository\ChurchRepository;
use App\Service\ChurchManager;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/church')]
class ChurchController extends AbstractController
{
    #[Route('/', name: 'app_church_index', methods: ['GET'])]
    public function index(
        ChurchRepository $churchRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $query = $churchRepository->getPaginationQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('church/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_church_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $church = new Church();
        $form = $this->createForm(ChurchType::class, $church);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                try {
                    $imageFileName = $fileUploader->upload($imageFile);
                    $church->setImage($imageFileName);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erro ao fazer upload da imagem: '.$e->getMessage());

                    return $this->render('church/new.html.twig', [
                        'church' => $church,
                        'form' => $form,
                    ]);
                }
            }

            $entityManager->persist($church);
            $entityManager->flush();

            return $this->redirectToRoute('app_church_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('church/new.html.twig', [
            'church' => $church,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_church_show', methods: ['GET'])]
    public function show(Church $church): Response
    {
        return $this->render('church/show.html.twig', [
            'church' => $church,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_church_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Church $church, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ChurchType::class, $church);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                try {
                    $imageFileName = $fileUploader->upload($imageFile);
                    $church->setImage($imageFileName);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erro ao fazer upload da imagem: '.$e->getMessage());

                    return $this->render('church/edit.html.twig', [
                        'church' => $church,
                        'form' => $form,
                    ]);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_church_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('church/edit.html.twig', [
            'church' => $church,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_church_delete_complex', methods: ['POST'])]
    public function deleteComplex(
        Request $request,
        Church $church,
        ChurchManager $churchManager,
    ): Response {
        if (!$this->isCsrfTokenValid('delete_generic', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token de segurança inválido.');

            return $this->redirectToRoute('app_church_index');
        }

        $action = $request->request->get('delete_action');

        try {
            $churchManager->deleteChurch($church, $action);

            if ('cascade' === $action) {
                $this->addFlash('success', 'Igreja e membros removidos.');
            } else {
                $this->addFlash('warning', 'Igreja removida. Membros foram despatriados.');
            }

        } catch (\Exception $e) {
            $this->addFlash('error', 'Erro ao processar: '.$e->getMessage());
        }

        return $this->redirectToRoute('app_church_index', [], Response::HTTP_SEE_OTHER);
    }
}
