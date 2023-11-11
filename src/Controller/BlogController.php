<?php

namespace App\Controller;

use DateTimeImmutable;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\PostRepository;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comment;

class BlogController extends AbstractController
{
    #[Route('/', name: 'get_posts')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $postRepository->getPaginatedPosts()
        ]);
    }

    #[Route('/posts/{slug}', name: 'get_post', methods: ['GET'])]
    public function getPost(Post $post): Response
    {
        $form = $this->createForm(CommentType::class);
        return $this->render('blog/post.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/posts/{slug}', name: 'save_comment', methods: ['POST'])]
    public function saveComment(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setPost($post);
        $comment->setCreatedAt(new DateTimeImmutable());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest(($request));

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('get_post', ['slug' => $post->getSlug()]);
        }
        return $this->render('blog/post.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
