<?php

namespace App\Tests\Feature;

use App\Entity\Article;
use App\Services\ArticleParserService;
use Symfony\Component\Serializer\Serializer;
use App\Services\ArticleParserServiceInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



class ArticleParserTest extends KernelTestCase
{
    /** @var ArticleParserServiceInterface */
    private ArticleParserServiceInterface $articleParser;

    public function setUp(): void
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $this->articleParser = new ArticleParserService($serializer);
    }


    public function testCanParseArticle()
    {
        $articleJsonData = file_get_contents("public/price-data/article-1.json.txt");

        /** @var Article */
        $article = $this->articleParser->parseJsonData($articleJsonData);

        $this->assertSame('12303', $article->getArticleNumber());
        $this->assertSame(10.99, $article->getUvpPrice());
        $this->assertSame(12.99, $article->getPrice());
    }
}