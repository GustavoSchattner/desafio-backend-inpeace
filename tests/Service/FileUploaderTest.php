<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploaderTest extends TestCase
{
    public function testUploadMovesFileAndReturnsFilename(): void
    {
        $tmpDir = sys_get_temp_dir() . '/desafio_test_' . uniqid();
        mkdir($tmpDir);

        $tmpFile = sys_get_temp_dir() . '/upl_' . uniqid() . '.png';
        file_put_contents($tmpFile, 'content');
        $uploaded = new UploadedFile($tmpFile, 'original.png', null, null, true);

        $slugger = $this->createMock(SluggerInterface::class);
        $slugger->method('slug')->willReturn(new UnicodeString('original'));

        $uploader = new FileUploader($tmpDir, $slugger);
        $filename = $uploader->upload($uploaded);

        $this->assertMatchesRegularExpression('/^original-[a-z0-9]+\.[a-z0-9]+$/', $filename);
        $this->assertFileExists($tmpDir . '/' . $filename);

        unlink($tmpDir . '/' . $filename);
        rmdir($tmpDir);
    }
}
