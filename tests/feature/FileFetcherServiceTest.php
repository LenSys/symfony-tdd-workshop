<?php

namespace App\Tests\Feature;

use App\Services\FileFetcherService;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class FileFetcherServiceTest extends KernelTestCase
{
    /** @var FileFetcherService */
    private FileFetcherService $fileFetcherService;

    /** @var Finder */
    private Finder $finder;

    private string $folderPath;


    public function setUp(): void
    {
        // static::bootKernel();
        // $this->finder = static::$kernel->getContainer()->get(Finder::class);

        $this->finder = new Finder();
        $this->fileFetcherService = new FileFetcherService($this->finder);

        $this->folderPath = 'public/price-data/';
    }


    public function tearDown(): void
    {      
        $this->finder->files()->in($this->folderPath)->name('*.*');
        
        // remove all files from public/price-data test folder
        foreach ($this->finder as $file) {
            unlink($file->getPathname());
        }
    }


    public function testCanFetchJsonFiles()
    {
        file_put_contents($this->folderPath . "file1.json", '');
        file_put_contents($this->folderPath . "file2.xml", '');
        file_put_contents($this->folderPath . "file3.JSON", '');

        
        $fileList = $this->fileFetcherService->getFileList($this->folderPath, "json");
        $this->assertCount(2, $fileList);
    }


    public function testEmptyFolder()
    {
        $fileList = $this->fileFetcherService->getFileList($this->folderPath, "json");
        $this->assertCount(0, $fileList);

        $fileList = $this->fileFetcherService->getFileList($this->folderPath, "*");
        $this->assertCount(0, $fileList);
    }


    public function testFolderExists()
    {   
        $folderExists = file_exists($this->folderPath);

        $this->assertSame(true, $folderExists);
    }
}