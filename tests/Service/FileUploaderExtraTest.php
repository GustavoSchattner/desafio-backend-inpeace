<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploaderExtraTest extends TestCase
{
    public function testGetTargetDirectoryIsAccessible(): void
    {
        $tmpDir = sys_get_temp_dir() . '/desafio_test_' . uniqid();

        $slugger = $this->createMock(SluggerInterface::class);
        $slugger->method('slug')->willReturn(new UnicodeString('original'));

        $uploader = new FileUploader($tmpDir, $slugger);

        $this->assertSame($tmpDir, $uploader->getTargetDirectory());
    }

    public function testUploadCreatesDirectoryIfNotExists(): void
    {
        $tmpDir = sys_get_temp_dir() . '/desafio_test_' . uniqid();

        $tmpFile = sys_get_temp_dir() . '/upl_' . uniqid() . '.png';
        file_put_contents($tmpFile, 'content');
        $uploaded = new UploadedFile($tmpFile, 'original.png', null, null, true);

        $slugger = $this->createMock(SluggerInterface::class);
        $slugger->method('slug')->willReturn(new UnicodeString('original'));

        // ensure dir does not exist
        if (is_dir($tmpDir)) {
            rmdir($tmpDir);
        }

        $uploader = new FileUploader($tmpDir, $slugger);
        $filename = $uploader->upload($uploaded);

        $this->assertMatchesRegularExpression('/^original-[a-z0-9]+\.[a-z0-9]+$/', $filename);
        $this->assertFileExists($tmpDir . '/' . $filename);

        unlink($tmpDir . '/' . $filename);
        rmdir($tmpDir);
    }
}
