<?php

namespace App\DataFixtures;

use App\Entity\BlogArticle;
use App\Entity\BlogCategory;
use App\Entity\BlogTag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BlogFixtures extends Fixture
{
  private UserPasswordHasherInterface $hasher;

  public function __construct(UserPasswordHasherInterface $hasher)
  {
    $this->hasher = $hasher;
  }


  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create();

    // CREATE USERS
    $users = [];
    for ($i = 1; $i <= 5; $i++) {
      $user = new User();
      $password = $this->hasher->hashPassword($user, 'password123');
      $user->setEmail($faker->email());
      $user->setFirstname($faker->userName());
      $user->setLastname($faker->userName());
      $user->setPassword($password); // DEFAULT PASSWORD
      $manager->persist($user);
      $users[] = $user;
    }

    $user = new User();

    $manager->flush();

    //category
    $categories = [];
    for ($i = 1; $i <= 5; $i++) {
      $category = new BlogCategory();
      $category->setName($faker->word());
      $manager->persist($category);
      $categories[] = $category;
    }

    //tags
    $tags = [];
    for ($i = 0; $i <= 10; $i++) {
      $tag = new BlogTag();
      $tag->setName($faker->word());
      $manager->persist($tag);
      $tags[] = $tag;
    }

    //articles
    for ($$i = 1; $i <= 40; $i++) {
      $article = new BlogArticle();
      $article->setTitle($faker->sentence());
      $article->setContent($faker->paragraphs(5, true));
      $article->setCreatedAt($faker->dateTimeBetween('-1 year', 'now'));

      // ASSIGN A RANDOM AUTHOR
      $article->setAuthor($users[array_rand($users)]);

      //associate to a random category
      $article->setCategory($categories[array_rand($categories)]);

      //associate to a random tag
      $randomTags = $faker->randomElements($tags, rand(2, 5));
      foreach ($randomTags as $tag) {
        $article->addTag($tag);
      }
      // ASSIGN A RANDOM AUTHOR
      $article->setAuthor($users[array_rand($users)]);
      $article->setSlug($faker->sentence());

      $manager->persist($article);
    }
    $manager->flush();
  }
}
