<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\DataFixtures\CategoryFixtures;
use App\Entity\Category;
use App\Entity\ColorProduct;

class ProductFixtures extends Fixture
{

  public function load(ObjectManager $manager): void
  {
    $categories = ['bouquets', 'gifts', 'wedding', 'decoration', 'events'];
    $colors = ['red', 'yellow', 'violet', 'green'];

    $description = "In omnis nobis maiores consectetur ipsum mollit praesentium In omnis nobis maiores consectetur ipsum mollit praesentiumIn omnis nobis maiores consectetur ipsum mollit praesentiumIn omnis.";

    //create 5 colors


    foreach ($categories as $cat) {
      $category = new Category();
      $category->setName($cat);
      $category->setSlug($cat);
      $category->setImageName('bouquet.jpg');
      $manager->persist($category);
    }
    foreach ($colors as $colorproduct) {
      $color = new ColorProduct();
      $color->setColor($colorproduct);
      $manager->persist($color);
      // CREATE  PRODUCTS PER CATEGORY
      for ($j = 1; $j <= 30; $j++) {
        $product = new Product();
        $product->setName('product ' . $j);
        $product->setSlug('slug' . $j);
        $product->setImageName('bouquet.jpg');
        $product->setDescription($description);
        $product->setPrice(mt_rand(10, 100));
        $product->setColorProduct($color);
        $product->setTva(15);
        $product->setPromotion(0);
        $product->setisSuggestion(false);
        $product->setCategory($category);
        $manager->persist($product);
      }
    }

    $manager->flush();
  }
}
