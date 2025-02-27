<?php

declare(strict_types=1);

namespace TomasVotruba\Website\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TomasVotruba\Blog\Repository\PostRepository;

use TomasVotruba\Website\ValueObject\RouteName;

final class PostController extends AbstractController
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    #[Route(path: '/blog/{slug}', name: RouteName::POST_DETAIL, requirements: [
        'slug' => '(\d+\/\d+.+|[\w\-]+)',
    ])]
    public function __invoke(string $slug): Response
    {
        $post = $this->postRepository->getBySlug($slug);
        $previousPost = $this->postRepository->findPreviousPost($post);

        return $this->render('blog/post_detail.twig', [
            'post' => $post,
            'previous_post' => $previousPost,
            'title' => $post->getTitle(),
        ]);
    }
}
