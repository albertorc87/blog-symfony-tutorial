<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Factory\TagFactory;
use App\Factory\PostFactory;
use App\Factory\CommentFactory;
use App\Factory\UserFactory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'username' => 'Admin',
            'email' => 'admin@blog-symfony.com',
            'roles' => ['ROLE_ADMIN']
        ]);
    
        TagFactory::createMany(20);
        UserFactory::createMany(10);
    
        PostFactory::createMany(20, function () {
            return [
                'comments' => CommentFactory::new()->many(0, 8),
                'tags' => TagFactory::randomRange(1, 5),
                'user' => UserFactory::random(),
            ];
        });
    }
}
