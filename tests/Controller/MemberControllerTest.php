<?php

namespace App\Tests\Controller;

use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MemberControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $memberRepository;
    private string $path = '/member/';

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->manager = self::getContainer()->get('doctrine')->getManager();
        $this->memberRepository = $this->manager->getRepository(Member::class);

        foreach ($this->memberRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Member index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'member[name]' => 'Testing',
            'member[cpf]' => 'Testing',
            'member[birthDate]' => 'Testing',
            'member[email]' => 'Testing',
            'member[phone]' => 'Testing',
            'member[address]' => 'Testing',
            'member[city]' => 'Testing',
            'member[state]' => 'Testing',
            'member[church]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->memberRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Member();
        $fixture->setName('My Title');
        $fixture->setCpf('My Title');
        $fixture->setBirthDate('My Title');
        $fixture->setEmail('My Title');
        $fixture->setPhone('My Title');
        $fixture->setAddress('My Title');
        $fixture->setCity('My Title');
        $fixture->setState('My Title');
        $fixture->setChurch('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Member');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Member();
        $fixture->setName('Value');
        $fixture->setCpf('Value');
        $fixture->setBirthDate('Value');
        $fixture->setEmail('Value');
        $fixture->setPhone('Value');
        $fixture->setAddress('Value');
        $fixture->setCity('Value');
        $fixture->setState('Value');
        $fixture->setChurch('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'member[name]' => 'Something New',
            'member[cpf]' => 'Something New',
            'member[birthDate]' => 'Something New',
            'member[email]' => 'Something New',
            'member[phone]' => 'Something New',
            'member[address]' => 'Something New',
            'member[city]' => 'Something New',
            'member[state]' => 'Something New',
            'member[church]' => 'Something New',
        ]);

        self::assertResponseRedirects('/member/');

        $fixture = $this->memberRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getCpf());
        self::assertSame('Something New', $fixture[0]->getBirthDate());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getPhone());
        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getState());
        self::assertSame('Something New', $fixture[0]->getChurch());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Member();
        $fixture->setName('Value');
        $fixture->setCpf('Value');
        $fixture->setBirthDate('Value');
        $fixture->setEmail('Value');
        $fixture->setPhone('Value');
        $fixture->setAddress('Value');
        $fixture->setCity('Value');
        $fixture->setState('Value');
        $fixture->setChurch('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/member/');
        self::assertSame(0, $this->memberRepository->count([]));
    }
}
