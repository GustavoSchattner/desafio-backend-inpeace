<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Church;
use App\Form\ChurchType;
use App\Repository\ChurchRepository;
use App\Service\ChurchService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $pagination = $paginator->paginate(
            $churchRepository->getPaginationQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('church/index.html.twig', [
            'pagination' => $pagination,
            'allChurches' => $churchRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_church_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChurchService $churchService): Response
    {
        $church = new Church();
        $form = $this->createForm(ChurchType::class, $church);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $churchService->handleImageUpload($church, $form->get('image')->getData());
                $churchService->save($church);

                $this->addFlash('success', 'Igreja cadastrada com sucesso!');

                return $this->redirectToRoute('app_church_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erro ao salvar: '.$e->getMessage());
            }
        }

        return $this->render('church/new.html.twig', [
            'church' => $church,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_church_show', methods: ['GET'])]
    public function show(Church $church): Response
    {
        return $this->render('church/show.html.twig', ['church' => $church]);
    }

    #[Route('/{id}/edit', name: 'app_church_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Church $church, ChurchService $churchService): Response
    {
        $form = $this->createForm(ChurchType::class, $church);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $churchService->handleImageUpload($church, $form->get('image')->getData());
                $churchService->save($church);

                $this->addFlash('success', 'Igreja atualizada com sucesso!');

                return $this->redirectToRoute('app_church_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erro ao atualizar: '.$e->getMessage());
            }
        }

        return $this->render('church/edit.html.twig', [
            'church' => $church,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_church_delete', methods: ['POST'])]
    public function delete(Request $request, Church $church, ChurchService $churchService): Response
    {
        $token = $request->request->get('_token');
        if (!is_string($token) && null !== $token) {
            $token = (string) $token;
        }

        if ($this->isCsrfTokenValid('delete_generic', $token)) {
            $action = $request->request->get('move_to_church');
            if (!is_string($action) && null !== $action) {
                $action = (string) $action;
            }
            $message = $churchService->deleteWithAction($church, $action);

            $this->addFlash('success', $message);
        } else {
            $this->addFlash('error', 'Token de segurança inválido.');
        }

        return $this->redirectToRoute('app_church_index', [], Response::HTTP_SEE_OTHER);
    }
}
