<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Member;
use App\Service\MemberService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class MemberServiceTest extends TestCase
{
    public function testSavePersistsAndFlushes(): void
    {
        $member = new Member();

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist')->with($member);
        $em->expects($this->once())->method('flush');

        $service = new MemberService($em);
        $service->save($member);
    }

    public function testRemoveDeletesAndFlushes(): void
    {
        $member = new Member();

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('remove')->with($member);
        $em->expects($this->once())->method('flush');

        $service = new MemberService($em);
        $service->remove($member);
    }
}
