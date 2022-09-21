<?php

namespace App\Services;

use Symfony\Component\Finder\Finder;


class FileFetcherService
{
    /** @var Finder */
    private Finder $finder;


    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }
     
    
    public function getFileList($folder, $fileExtension): array
    {
        $fileList = $this->finder->in($folder)
                     ->name(sprintf("/\.%s$/i", $fileExtension))
                     ->sortByName();

        return iterator_to_array($fileList);
    }
}