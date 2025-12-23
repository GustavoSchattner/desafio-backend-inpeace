<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Church;
use App\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MemberRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = self::getContainer()->get('doctrine')->getManager();
    }

    public function testFindByNameAndByCpfAndEmail(): void
    {
        $church = new Church();
        $church->setName('Repo Church');
        $church->setAddress('Repo City');

        $member = new Member();
        $member->setName('Repo Member');
        $member->setCpf('12345678909');
        $member->setEmail('repo@example.com');
        $member->setChurch($church);

        $this->em->persist($church);
        $this->em->persist($member);
        $this->em->flush();

        $repo = $this->em->getRepository(Member::class);

        $byName = $repo->findByName('Repo');
        $this->assertNotEmpty($byName);

        $byCpf = $repo->findByCpf('12345678909');
        $this->assertInstanceOf(Member::class, $byCpf);

        $byEmail = $repo->findByEmail('repo@example.com');
        $this->assertInstanceOf(Member::class, $byEmail);

        $byChurch = $repo->findByChurch($church);
        $this->assertIsArray($byChurch);

        $count = $repo->countByChurch($church);
        $this->assertGreaterThanOrEqual(1, $count);

        $withChurch = $repo->findWithChurch();
        $this->assertIsArray($withChurch);
    }

    public function testFindByCity(): void
    {
        $repo = $this->em->getRepository(Member::class);
        $res = $repo->findByCity('Repo City');
        $this->assertIsArray($res);

        $q = $repo->getPaginationQuery();
        $this->assertNotNull($q);
    }
}
