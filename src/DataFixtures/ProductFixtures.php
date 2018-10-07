<?php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ProductFixtures extends Fixture {

    public function load(ObjectManager $manager){
            $faker = Faker\Factory::create('fr_FR');
            // Ajout des categories
            for($i = 0; $i < 3; $i++){
                $category = new Category();
                $category->setTitle($faker->sentence())
                         ->setDescription($faker->text(50));
                $manager->persist($category);
                // Ajout des produits
                for ($j = 1; $j < 20; $j++){
                    $product = new Product();
                    $content = join($faker->paragraphs(5));
                    $product->setTitle($faker->text(30))
                            ->setContent($content)
                            ->setCreated($faker->dateTimeThisYear('now'))
                            ->setUser($faker->numberBetween(1,10))
                            ->setImage($faker->imageUrl())
                            ->setCategory($category)
                            ->setPublished(1);
                    $manager->persist($product);
                }
            }
            $manager->flush();
    }
}