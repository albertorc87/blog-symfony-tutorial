<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\PostRepository;

class BlogController extends AbstractController
{
    #[Route('/', name: 'get_posts')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $postRepository->getPaginatedPosts()
        ]);
    }

    #[Route('/posts/{slug}', name: 'get_post')]
    public function getPost(Post $post): Response
    {
        return $this->render('blog/post.html.twig', [
            'post' => $post,
        ]);
    }
}
