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

        $em = $this->createMock(EntityManagerInterface::class);
        $repo = $this->createMock(ChurchRepository::class);

        $service = new ChurchService($em, $uploader, $repo);
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

        $repo = $this->createMock(ChurchRepository::class);
        $uploader = $this->createMock(FileUploader::class);

        $service = new ChurchService($em, $uploader, $repo);
        $service->deleteWithAction($church, ChurchService::ACTION_CASCADE_DELETE);
    }

    public function testDeleteWithActionTransfersMembersWhenTargetExists(): void
    {
        $member = $this->createMock(Member::class);
        $member->expects($this->once())->method('setChurch');

        $members = new ArrayCollection([$member]);

        $source = $this->createMock(Church::class);
        $source->method('getMembers')->willReturn($members);

        $target = $this->createMock(Church::class);
        $target->method('getId')->willReturn(2);
        $target->method('getName')->willReturn('Target');

        $source->method('getId')->willReturn(1);

        $repo = $this->createMock(ChurchRepository::class);
        $repo->method('find')->willReturn($target);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('flush');

        $uploader = $this->createMock(FileUploader::class);

        $service = new ChurchService($em, $uploader, $repo);
        $service->deleteWithAction($source, '2');
    }
}
