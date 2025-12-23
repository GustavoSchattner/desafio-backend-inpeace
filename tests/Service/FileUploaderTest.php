<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FileUploaderTest extends TestCase
{
    private string $targetDir;
    private string $tempFile;

    protected function setUp(): void
    {
        $this->targetDir = sys_get_temp_dir() . '/inpeace_test_' . uniqid();
        if (is_dir($this->targetDir)) {
            rmdir($this->targetDir);
        }

        $this->tempFile = sys_get_temp_dir() . '/test_upload.jpg';
        touch($this->tempFile);
    }

    public function testUploadCreatesDirectoryAndMovesFile(): void
    {
        $slugger = new AsciiSlugger();
        $uploader = new FileUploader($this->targetDir, $slugger);

        $file = new UploadedFile(
            $this->tempFile,
            'test_upload.jpg',
            'image/jpeg',
            null,
            true 
        );

        $filename = $uploader->upload($file);
        
        $this->assertDirectoryExists($this->targetDir);

        $this->assertFileExists($this->targetDir . '/' . $filename);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->targetDir)) {
            $files = glob($this->targetDir . '/*');
            foreach ($files as $file) {
                unlink($file);
            }
            rmdir($this->targetDir);
        }
        
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }
}