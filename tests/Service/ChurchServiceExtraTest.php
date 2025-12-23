<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Church;
use App\Entity\Member;
use App\Service\ChurchService;
use App\Service\FileUploader;
use App\Repository\ChurchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class ChurchServiceExtraTest extends TestCase
{
    public function testDeleteWithActionDoesNotTransferWhenTargetHasSameId(): void
    {
        $member = $this->createMock(Member::class);
        $member->expects($this->never())->method('setChurch');

        $members = new ArrayCollection([$member]);

        $source = $this->createMock(Church::class);
        $source->method('getMembers')->willReturn($members);
        $source->method('getId')->willReturn(1);

        $target = $this->createMock(Church::class);
        $target->method('getId')->willReturn(1);
        $target->method('getName')->willReturn('Target');

        $repo = $this->createMock(ChurchRepository::class);
        $repo->method('find')->willReturn($target);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('remove');
        $em->expects($this->once())->method('flush');

        $uploader = $this->createMock(FileUploader::class);

        $service = new ChurchService($em, $uploader, $repo);
        $message = $service->deleteWithAction($source, '1');

        $this->assertSame('Igreja removida. Membros ficaram sem vínculo.', $message);
    }

    public function testDeleteWithActionReturnsTransferMessage(): void
    {
        $member = $this->createMock(Member::class);
        $member->expects($this->once())->method('setChurch')->with($this->isInstanceOf(Church::class));

        $members = new ArrayCollection([$member]);

        $source = $this->createMock(Church::class);
        $source->method('getMembers')->willReturn($members);
        $source->method('getId')->willReturn(1);

        $target = $this->createMock(Church::class);
        $target->method('getId')->willReturn(2);
        $target->method('getName')->willReturn('Target');

        $repo = $this->createMock(ChurchRepository::class);
        $repo->method('find')->willReturn($target);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('flush');
        $em->expects($this->once())->method('remove');

        $uploader = $this->createMock(FileUploader::class);

        $service = new ChurchService($em, $uploader, $repo);
        $message = $service->deleteWithAction($source, '2');

        $this->assertSame('Membros transferidos para Target.', $message);
    }
}
