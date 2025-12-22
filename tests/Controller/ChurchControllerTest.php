<?php

namespace App\Tests\Controller;

use App\Entity\Church;
use App\Repository\ChurchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChurchControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ChurchRepository $repository;
    private EntityManagerInterface $entityManager;
    private string $path = '/church/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $this->repository = $container->get(ChurchRepository::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->entityManager->createQuery('DELETE FROM App\Entity\Member')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Church')->execute();
    }

    private function createChurch(string $name = 'Igreja Matriz'): Church
    {
        $church = new Church();
        $church->setName($name);
        $church->setAddress('Rua Principal, 100');
        
        $this->entityManager->persist($church);
        $this->entityManager->flush();

        return $church;
    }

    public function testIndex(): void
    {
        $this->createChurch('Igreja A');
        $this->createChurch('Igreja B');

        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Listagem de Igrejas');
        
        self::assertAnySelectorTextContains('table', 'Igreja A');
        self::assertAnySelectorTextContains('table', 'Igreja B');
    }

    public function testNew(): void
    {
        $crawler = $this->client->request('GET', $this->path . 'new');
        self::assertResponseStatusCodeSame(200);

        $form = $crawler->selectButton('Salvar')->form([
            'church[name]' => 'Nova Igreja Teste',
            'church[address]' => 'Av. Teste, 999',
        ]);

        $this->client->submit($form);

        self::assertResponseRedirects($this->path);

        $church = $this->repository->findOneBy(['name' => 'Nova Igreja Teste']);
        self::assertNotNull($church);
        self::assertSame('Av. Teste, 999', $church->getAddress());
    }

    public function testShow(): void
    {
        $church = $this->createChurch();

        $this->client->request('GET', $this->path . $church->getId());

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Church'); 
        self::assertSelectorTextContains('body', $church->getName());
    }

    public function testEdit(): void
    {
        $church = $this->createChurch('Igreja Antiga');

        $crawler = $this->client->request('GET', $this->path . $church->getId() . '/edit');

        $form = $crawler->selectButton('Atualizar')->form([
            'church[name]' => 'Igreja Renovada',
        ]);

        $this->client->submit($form);

        self::assertResponseRedirects($this->path);

        $updatedChurch = $this->repository->find($church->getId());
        self::assertSame('Igreja Renovada', $updatedChurch->getName());
    }

    public function testRemove(): void
    {
        $church = $this->createChurch('Igreja Para Deletar');
        $churchId = $church->getId();
        
        $crawler = $this->client->request('GET', $this->path . $churchId . '/edit');
        $this->client->submitForm('Deletar'); 

        self::assertResponseRedirects($this->path);

        $this->entityManager->clear();
        
        $deletedChurch = $this->repository->find($churchId);
        self::assertNull($deletedChurch);
    }
}