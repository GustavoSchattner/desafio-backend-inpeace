<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use App\Service\MemberService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/member')]
final class MemberController extends AbstractController
{
    /**
     * @param MemberRepository $memberRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'app_member_index', methods: ['GET'])]
    public function index(
        MemberRepository $memberRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $pagination = $paginator->paginate(
            $memberRepository->getPaginationQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('member/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param Request $request
     * @param MemberService $memberService
     * @return Response
     */
    #[Route('/new', name: 'app_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MemberService $memberService): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $memberService->save($member);
                $this->addFlash('success', 'Membro cadastrado com sucesso.');

                return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Erro ao cadastrar membro.');
            }
        }

        return $this->render('member/new.html.twig', [
            'form' => $form,
            'member' => $member,
        ]);
    }

    /**
     * @param Member $member
     * @return Response
     */
    #[Route('/{id}', name: 'app_member_show', methods: ['GET'])]
    public function show(Member $member): Response
    {
        return $this->render('member/show.html.twig', [
            'member' => $member,
        ]);
    }

    /**
     * @param Request $request
     * @param Member $member
     * @param MemberService $memberService
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Member $member, MemberService $memberService): Response
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $memberService->save($member);
                $this->addFlash('success', 'Membro atualizado com sucesso.');

                return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Erro ao atualizar membro.');
            }
        }

        return $this->render('member/edit.html.twig', [
            'form' => $form,
            'member' => $member,
        ]);
    }

    /**
     * @param Request $request
     * @param Member $member
     * @param MemberService $memberService
     * @return Response
     */
    #[Route('/{id}', name: 'app_member_delete', methods: ['POST'])]
    public function delete(Request $request, Member $member, MemberService $memberService): Response
    {
        $token = $request->request->get('_token');
        $tokenString = is_string($token) ? $token : '';

        if ($this->isCsrfTokenValid('delete' . $member->getId(), $tokenString)) {
            try {
                $memberService->remove($member);
                $this->addFlash('success', 'Membro excluído com sucesso.');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Erro ao excluir membro.');
            }
        } else {
            $this->addFlash('error', 'Token de segurança inválido.');
        }

        return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
    }
}
