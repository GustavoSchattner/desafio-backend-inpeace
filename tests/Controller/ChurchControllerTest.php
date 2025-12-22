<?php

namespace App\Tests\Controller;

use App\Entity\Church;
use App\Entity\Member;
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
        $this->client->followRedirects();

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

    private function createMember(Church $church): void
    {
        $member = new Member();
        $member->setName('Membro Teste');
        $member->setCpf('123.456.789-00');
        $member->setEmail('teste@membro.com');
        $member->setChurch($church);
        $member->setAddress('Rua X');
        $member->setCity('Cidade Y');
        $member->setState('UF');
        $member->setPhone('123');
        $member->setBirthDate(new \DateTime());

        $this->entityManager->persist($member);
        $this->entityManager->flush();
    }

    public function testIndex(): void
    {
        $this->createChurch('Igreja A');
        $this->createChurch('Igreja B');

        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Listagem de Igrejas');
        self::assertAnySelectorTextContains('table', 'Igreja A');
    }

    public function testNew(): void
    {
        $crawler = $this->client->request('GET', $this->path.'new');
        self::assertResponseStatusCodeSame(200);

        $form = $crawler->filter('button[type="submit"]')->form([
            'church[name]' => 'Nova Igreja Teste',
            'church[address]' => 'Av. Teste, 999',
        ]);

        $this->client->submit($form);

        self::assertRouteSame('app_church_index');

        self::assertSelectorExists('.alert-success');
        self::assertSelectorTextContains('.alert-success', 'sucesso');

        $church = $this->repository->findOneBy(['name' => 'Nova Igreja Teste']);
        self::assertNotNull($church);
    }

    public function testEdit(): void
    {
        $church = $this->createChurch('Igreja Antiga');
        $crawler = $this->client->request('GET', $this->path.$church->getId().'/edit');

        self::assertResponseStatusCodeSame(200);

        $form = $crawler->filter('form[name="church"]')->form([
            'church[name]' => 'Igreja Renovada',
        ]);

        $this->client->submit($form);
        self::assertRouteSame('app_church_index');

        self::assertSelectorExists('.alert-success');

        $updatedChurch = $this->repository->find($church->getId());
        self::assertSame('Igreja Renovada', $updatedChurch->getName());
    }

    private function extractCsrfTokenFromIndex(): string
    {
        $crawler = $this->client->request('GET', $this->path);

        return $crawler->filter('#deleteForm input[name="_token"]')->attr('value');
    }

    public function testDeleteSimple(): void
    {
        $church = $this->createChurch('Igreja Para Deletar');
        $churchId = $church->getId();

        $token = $this->extractCsrfTokenFromIndex();

        $this->client->request('POST', $this->path.$churchId, [
            '_token' => $token,
            'move_to_church' => '',
        ]);

        self::assertRouteSame('app_church_index');

        $this->entityManager->clear();
        $deletedChurch = $this->repository->find($churchId);
        self::assertNull($deletedChurch);
    }

    public function testDeleteCascade(): void
    {
        $church = $this->createChurch('Igreja Cascata');
        $this->createMember($church);
        $churchId = $church->getId();
        $token = $this->extractCsrfTokenFromIndex();
        $this->client->request('POST', $this->path.$churchId, [
            '_token' => $token,
            'move_to_church' => 'cascade_delete',
        ]);
        self::assertRouteSame('app_church_index');
        $this->entityManager->clear();
        $deletedChurch = $this->repository->find($churchId);
        self::assertNull($deletedChurch);
        $member = $this->entityManager->getRepository(Member::class)->findOneBy(['email' => 'teste@membro.com']);
        self::assertNull($member, 'O membro deveria ter sido excluído na cascata.');
    }
}
