<?php

namespace App\Tests\Feature;

use App\Entity\Article;
use Symfony\Component\Finder\Finder;
use App\Services\ArticleParserService;
use Symfony\Component\Serializer\Serializer;
use App\Services\ArticleParserServiceInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



class ArticleParserServiceTest extends KernelTestCase
{
    /** @var ArticleParserServiceInterface */
    private ArticleParserServiceInterface $articleParserService;

    public function setUp(): void
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $this->articleParserService = new ArticleParserService($serializer);
    }


    public function tearDown(): void
    {
        $finder = new Finder();
        $finder->files()->in('public/price-data');
        
        // remove all files from public/price-data test folder
        foreach ($finder as $file) {
            unlink($file->getPathname());
        }
    }


    public function testCanParseArticle()
    {
        file_put_contents("public/price-data/article-1.json", '{
            "ArticleNumber": "12303",
            "UvpPrice": 10.99,
            "Price": 12.99
        }');

        $articleJsonData = file_get_contents("public/price-data/article-1.json");

        /** @var Article */
        $article = $this->articleParserService->parseJsonData($articleJsonData);

        $this->assertSame('12303', $article->getArticleNumber());
        $this->assertSame(10.99, $article->getUvpPrice());
        $this->assertSame(12.99, $article->getPrice());
    }


    public function testCanParseEmptyArticle()
    {
        file_put_contents("public/price-data/article-empty.json", '');

        $articleJsonData = file_get_contents("public/price-data/article-empty.json");

        /** @var Article */
        $article = $this->articleParserService->parseJsonData($articleJsonData);
        
        $this->assertSame(false, $article);
        
    }
}