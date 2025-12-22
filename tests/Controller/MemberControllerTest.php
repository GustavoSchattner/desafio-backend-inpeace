<?php

namespace App\Tests\Controller;

use App\Entity\Church;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MemberControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private MemberRepository $repository;
    private EntityManagerInterface $entityManager;
    private string $path = '/member/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $this->repository = $container->get(MemberRepository::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    private function createChurch(): Church
    {
        $church = new Church();
        $church->setName('Igreja Teste Unitario');
        $church->setAddress('Rua Teste');

        $this->entityManager->persist($church);
        $this->entityManager->flush();

        return $church;
    }

    public function testIndexRendersPagination(): void
    {
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Listagem de Membros');
        self::assertSelectorExists('table.table');
    }

    public function testNewMemberSubmission(): void
    {
        $church = $this->createChurch();

        $crawler = $this->client->request('GET', $this->path.'new');
        self::assertResponseStatusCodeSame(200);

        $form = $crawler->selectButton('Salvar')->form([
            'member[name]' => 'Teste Automatizado',
            'member[cpf]' => '529.982.247-25',
            'member[email]' => 'teste@senior.com',
            'member[birthDate]' => '1990-01-01',
            'member[phone]' => '11999999999',
            'member[address]' => 'Rua dos Testes, 123',
            'member[state]' => 'SP',
            'member[city]' => 'São Paulo',
            'member[church]' => $church->getId(),
        ]);

        $this->client->submit($form);

        self::assertResponseRedirects($this->path);

        $this->client->followRedirect();
        self::assertAnySelectorTextContains('table', 'Teste Automatizado');

        $member = $this->repository->findOneBy(['email' => 'teste@senior.com']);
        self::assertNotNull($member);
        self::assertSame('529.982.247-25', $member->getCpf());
    }

    public function testInvalidCpfSubmissionFails(): void
    {
        $church = $this->createChurch();

        $crawler = $this->client->request('GET', $this->path.'new');

        $form = $crawler->selectButton('Salvar')->form([
            'member[name]' => 'CPF Inválido',
            'member[cpf]' => '111.111.111-11',
            'member[email]' => 'falha@teste.com',
            'member[birthDate]' => '1990-01-01',
            'member[phone]' => '11999999999',
            'member[address]' => 'Rua',
            'member[state]' => 'SP',
            'member[city]' => 'SP',
            'member[church]' => $church->getId(),
        ]);

        $this->client->submit($form);

        self::assertResponseStatusCodeSame(422);

        self::assertAnySelectorTextContains('li', 'não é válido');
    }
}
