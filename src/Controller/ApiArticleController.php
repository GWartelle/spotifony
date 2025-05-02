<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/articles')]
class ApiArticleController extends AbstractController
{
    #[Route('/', name: 'api_articles_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, SerializerInterface $serializer): JsonResponse
    {
        $articles = $articleRepository->findAll();
        $data = $serializer->serialize($articles, 'json', ['groups' => 'article:read']);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', name: 'api_articles_show', methods: ['GET'])]
    public function show(Article $article, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($article, 'json', ['groups' => 'article:read']);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/', name: 'api_articles_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $article = new Article();
        $data = json_decode($request->getContent(), true);

        $article->setTitle($data['title']);
        $article->setContent($data['content']);
        $entityManager->persist($article);
        $entityManager->flush();

        $data = $serializer->serialize($article, 'json', ['groups' => 'article:read']);

        return new JsonResponse($data, 201, [], true);
    }

    #[Route('/{id}', name: 'api_articles_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $article->setTitle($data['title']);
        $article->setContent($data['content']);

        $entityManager->flush();

        $data = $serializer->serialize($article, 'json', ['groups' => 'article:read']);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', name: 'api_articles_delete', methods: ['DELETE'])]
    public function delete(Article $article, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($article);
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}
