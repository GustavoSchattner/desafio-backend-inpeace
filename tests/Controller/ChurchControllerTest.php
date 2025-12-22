<?php

namespace App\Tests\Controller;

use App\Entity\Church;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChurchControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    private \App\Repository\ChurchRepository $churchRepository;

    private string $path = '/church/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->churchRepository = static::getContainer()->get(\App\Repository\ChurchRepository::class);

        foreach ($this->churchRepository->findAll() as $object) {
            $this->churchRepository->remove($object, true);
        }
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
        /*
        $originalNumObjectsInRepository = count($this->churchRepository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'church[name]' => 'Testing',
            'church[address]' => 'Testing',
            'church[city]' => 'Testing',
            'church[state]' => 'Testing',
            'church[phone]' => 'Testing',
            'church[denomination]' => 'Testing',
            'church[website]' => 'Testing',
        ]);

        self::assertResponseRedirects('/church/');
        self::assertSame($originalNumObjectsInRepository + 1, count($this->churchRepository->findAll()));
        */
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        /*
        $fixture = new Church();
        $fixture->setName('My Title');
        $fixture->setAddress('My Title');
        $fixture->setCity('My Title');
        $fixture->setState('My Title');
        $fixture->setPhone('My Title');
        $fixture->setDenomination('My Title');
        $fixture->setWebsite('My Title');
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());

        $this->churchRepository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Church');
        */
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        /*
        $fixture = new Church();
        $fixture->setName('My Title');
        $fixture->setAddress('My Title');
        $fixture->setCity('My Title');
        $fixture->setState('My Title');
        $fixture->setPhone('My Title');
        $fixture->setDenomination('My Title');
        $fixture->setWebsite('My Title');
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());

        $this->churchRepository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'church[name]' => 'Something New',
            'church[address]' => 'Something New',
            'church[city]' => 'Something New',
            'church[state]' => 'Something New',
            'church[phone]' => 'Something New',
            'church[denomination]' => 'Something New',
            'church[website]' => 'Something New',
        ]);

        self::assertResponseRedirects('/church/');

        $fixture = $this->churchRepository->findAll()[0];
        self::assertSame('Something New', $fixture->getName());
        */
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        /*
        $fixture = new Church();
        $fixture->setName('My Title');
        $fixture->setAddress('My Title');
        $fixture->setCity('My Title');
        $fixture->setState('My Title');
        $fixture->setPhone('My Title');
        $fixture->setDenomination('My Title');
        $fixture->setWebsite('My Title');
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());

        $this->churchRepository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/church/');
        self::assertSame(0, count($this->churchRepository->findAll()));
        */
    }
}
