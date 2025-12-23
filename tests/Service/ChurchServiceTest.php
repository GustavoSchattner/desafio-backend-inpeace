<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Church;
use App\Entity\Member;
use App\Repository\ChurchRepository;
use App\Service\ChurchService;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ChurchServiceTest extends TestCase
{
    public function testHandleImageUploadSetsImage(): void
    {
        $church = $this->createMock(Church::class);
        $church->expects($this->once())->method('setImage')->with('img.png');

        $file = $this->createMock(UploadedFile::class);

        $uploader = $this->createMock(FileUploader::class);
        $uploader->expects($this->once())->method('upload')->with($file)->willReturn('img.png');

        $service = new ChurchService(
            $this->createMock(EntityManagerInterface::class),
            $uploader,
            $this->createMock(ChurchRepository::class)
        );

        $service->handleImageUpload($church, $file);
    }

    public function testDeleteWithActionCascadeRemovesMembersAndChurch(): void
    {
        $member = $this->createMock(Member::class);
        $members = new ArrayCollection([$member]);

        $church = $this->createMock(Church::class);
        $church->method('getMembers')->willReturn($members);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->exactly(2))->method('remove');
        $em->expects($this->once())->method('flush');

        $service = new ChurchService(
            $em,
            $this->createMock(FileUploader::class),
            $this->createMock(ChurchRepository::class)
        );

        $service->deleteWithAction($church, ChurchService::ACTION_CASCADE_DELETE);
    }

    public function testDeleteWithActionTransfersMembersAndReturnsMessage(): void
    {
        $member = $this->createMock(Member::class);
        $member->expects($this->once())
            ->method('setChurch')
            ->with($this->isInstanceOf(Church::class));

        $members = new ArrayCollection([$member]);

        $source = $this->createMock(Church::class);
        $source->method('getMembers')->willReturn($members);
        $source->method('getId')->willReturn(1);

        $target = $this->createMock(Church::class);
        $target->method('getId')->willReturn(2);
        $target->method('getName')->willReturn('Igreja Destino');

        $repo = $this->createMock(ChurchRepository::class);
        $repo->method('find')->willReturn($target);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('flush');
        $em->expects($this->once())->method('remove')->with($source);

        $service = new ChurchService(
            $em,
            $this->createMock(FileUploader::class),
            $repo
        );

        $message = $service->deleteWithAction($source, '2');

        $this->assertSame('Membros transferidos para Igreja Destino.', $message);
    }

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

        $repo = $this->createMock(ChurchRepository::class);
        $repo->method('find')->willReturn($target);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('remove')->with($source);
        $em->expects($this->once())->method('flush');

        $service = new ChurchService(
            $em,
            $this->createMock(FileUploader::class),
            $repo
        );

        $message = $service->deleteWithAction($source, '1');

        $this->assertSame('Igreja removida. Membros ficaram sem vínculo.', $message);
    }
}