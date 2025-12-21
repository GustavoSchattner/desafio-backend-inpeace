<?php

namespace App\Tests\Entity;

use App\Entity\Church;
use App\Entity\Member;
use PHPUnit\Framework\TestCase;

class ChurchTest extends TestCase
{
    public function testChurchCanAddMember()
    {
        $church = new Church();
        $church->setName('Igreja Batista');
        
        $member = new Member();
        $member->setName('Gustavo');
        
        $church->addMember($member);

        $this->assertTrue($church->getMembers()->contains($member));
        
        $this->assertSame($church, $member->getChurch());
    }
}