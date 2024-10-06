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

    $description = "Quam si euismod scelerisque phasellus faucibus nec feugiat porta hendrerit. Eleifend integer class litora gravida id scelerisque mattis cursus. Bibendum tellus sagittis viverra inceptos tortor himenaeos arcu diam. Luctus nam platea turpis congue nulla conubia consectetuer aliquam. Nullam letius tortor non maximus phasellus cras torquent. Cras finibus tempor interdum neque tellus morbi. Felis malesuada nam dis et sollicitudin.";

    //create 5 colors


    foreach ($categories as $cat) {
      $category = new Category();
      $category->setName($cat);
      $category->setSlug('slug' . $cat);
      $category->setIllustration('bouquet.jpg');
      $manager->persist($category);
    }
    foreach ($colors as $colorproduct) {
      $color = new ColorProduct();
      $color->setColor($colorproduct);
      $manager->persist($color);
      // Create  products per category
      for ($j = 1; $j <= 30; $j++) {
        $product = new Product();
        $product->setName('product ' . $j);
        $product->setSlug('slug' . $j);
        $product->setImageName('1099d200-2584-46f6-81d3-e1d249c46eff-66faa83cbb274881477130.jpeg');
        $product->setDescription($description);
        $product->setPrice(mt_rand(10, 100));
        $product->setColorProduct($color);
        $product->setTva(10);
        $product->setPromotion(mt_rand(0, 20));
        $product->setCategory($category);
        $manager->persist($product);
      }
    }

    $manager->flush();
  }
}
