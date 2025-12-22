<?php

namespace App\Tests\Controller;

use App\Entity\Church;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ChurchControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $churchRepository;
    private string $path = '/church/';

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->manager = self::getContainer()->get('doctrine')->getManager();
        $this->churchRepository = $this->manager->getRepository(Church::class);

        foreach ($this->churchRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        
        self::assertPageTitleContains('Listagem de Igrejas');

    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'church[name]' => 'Testing',
            'church[address]' => 'Testing',
            'church[website]' => 'Testing',
            'church[image]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->churchRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Church();
        $fixture->setName('My Title');
        $fixture->setAddress('My Title');
        $fixture->setWebsite('My Title');
        $fixture->setImage('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Church');

    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Church();
        $fixture->setName('Value');
        $fixture->setAddress('Value');
        $fixture->setWebsite('Value');
        $fixture->setImage('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'church[name]' => 'Something New',
            'church[address]' => 'Something New',
            'church[website]' => 'Something New',
            'church[image]' => 'Something New',
        ]);

        self::assertResponseRedirects('/church/');

        $fixture = $this->churchRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getWebsite());
        self::assertSame('Something New', $fixture[0]->getImage());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Church();
        $fixture->setName('Value');
        $fixture->setAddress('Value');
        $fixture->setWebsite('Value');
        $fixture->setImage('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/church/');
        self::assertSame(0, $this->churchRepository->count([]));
    }
}
