<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;

class MemberService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Member $member): void
    {
        $this->entityManager->persist($member);
        $this->entityManager->flush();
    }

    public function remove(Member $member): void
    {
        $this->entityManager->remove($member);
        $this->entityManager->flush();
    }
}
