<?php

namespace App\Services;

interface ArticleParserServiceInterface
{
    function parseJsonData(string $jsonData);
}