<?php

namespace App\Services;

use App\Entity\Article;
use App\Services\ArticleParserServiceInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ArticleParserService implements ArticleParserServiceInterface
{
    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

        
        
    /**
     * Parses the json data.
     *
     * @param  mixed $jsonData The json data representing an article
     * @return Article The parsed article
     */
    function parseJsonData(string $jsonData): Article
    {
        $article = $this->serializer->deserialize($jsonData, Article::class, 'json');

        return $article;
    }
}