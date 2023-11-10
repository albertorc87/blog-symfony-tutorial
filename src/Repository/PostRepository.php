<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\TagRepository;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private TagRepository $tagRepository)
    {
        parent::__construct($registry, Post::class);
    }

    public function getPaginatedPosts(int $page = 1, int $limit = 10)
    {
        $posts = $this->createQueryBuilder('post')
            ->select('
            post.id,
            post.title,
            post.slug,
            post.publication_date,
            post.content,
            COUNT(comment.id) as total_comments
        ')
            ->leftJoin('post.comments', 'comment')
            ->groupBy('post.id')
            ->orderBy('post.publication_date', 'DESC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;

        $post_ids = array_map(fn($post) => $post['id'], $posts);

        $tags_by_post = $this->tagRepository->getTagsByPostIds($post_ids);

        foreach ($posts as &$post) {
            $post['tags'] = $tags_by_post[$post['id']] ?? [];
        }
        unset($post);

        return $posts;
    }

    //    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
