<?php

namespace App\Tests\Service;

use App\Entity\Church;
use App\Entity\Member;
use App\Service\ChurchManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChurchManagerTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?ChurchManager $churchManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->churchManager = $container->get(ChurchManager::class);
    }

    public function testDeleteChurchKeepsOrphans(): void
    {
        $church = new Church();
        $church->setName('Igreja Teste Orphans');
        $church->setAddress('Rua X');

        $member = new Member();
        $member->setName('Membro Sobrevivente');
        $member->setCpf('111.111.111-11');
        $member->setEmail('teste@teste.com');
        $member->setPhone('11999999999');
        $member->setAddress('Rua Y');
        $member->setCity('Cidade');
        $member->setState('ES');
        $member->setBirthDate(new \DateTime('-20 years'));

        $church->addMember($member);

        $this->entityManager->persist($church);
        $this->entityManager->persist($member);
        $this->entityManager->flush();

        $memberId = $member->getId();
        $churchId = $church->getId();

        $this->churchManager->deleteChurch($church, 'orphans');

        $this->entityManager->clear();

        $deletedChurch = $this->entityManager->getRepository(Church::class)->find($churchId);
        $this->assertNull($deletedChurch, 'A igreja deveria estar oculta (SoftDeleted).');

        $this->entityManager->getFilters()->disable('softdeleteable');
        $rawChurch = $this->entityManager->getRepository(Church::class)->find($churchId);
        $this->assertNotNull($rawChurch->getDeletedAt(), 'O campo deletedAt deveria estar preenchido.');
        $this->entityManager->getFilters()->enable('softdeleteable');

        $orphanMember = $this->entityManager->getRepository(Member::class)->find($memberId);
        $this->assertNotNull($orphanMember, 'O membro não deveria ter sido deletado.');
        $this->assertNull($orphanMember->getChurch(), 'O membro deveria estar despatriado (sem igreja).');
    }

    public function testDeleteChurchCascadesToMembers(): void
    {
        $church = new Church();
        $church->setName('Igreja Teste Cascade');
        $church->setAddress('Rua Z');

        $member = new Member();
        $member->setName('Membro Condenado');
        $member->setCpf('222.222.222-22');
        $member->setEmail('delete@teste.com');
        $member->setPhone('11999999999');
        $member->setAddress('Rua W');
        $member->setCity('Cidade');
        $member->setState('SP');
        $member->setBirthDate(new \DateTime('-20 years'));

        $church->addMember($member);

        $this->entityManager->persist($church);
        $this->entityManager->persist($member);
        $this->entityManager->flush();

        $memberId = $member->getId();

        $this->churchManager->deleteChurch($church, 'cascade');
        $this->entityManager->clear();

        $deletedMember = $this->entityManager->getRepository(Member::class)->find($memberId);
        $this->assertNull($deletedMember, 'O membro deveria ter sido deletado junto com a igreja.');

        $this->entityManager->getFilters()->disable('softdeleteable');
        $rawMember = $this->entityManager->getRepository(Member::class)->find($memberId);
        $this->assertNotNull($rawMember, 'O registro físico do membro ainda deveria existir.');
        $this->assertNotNull($rawMember->getDeletedAt(), 'O membro deveria ter data de deleção (deletedAt).');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
