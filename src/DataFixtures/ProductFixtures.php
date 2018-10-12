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
            $gender = ['Curved', 'Fitted', 'Snapback'];
            for($i = 0; $i < 3; $i++){
                $category = new Category();
                $category->setCategory($gender[$i])
                         ->setDescription($faker->text(30));
                $manager->persist($category);
                // Ajout des produits
                for ($j = 1; $j < 20; $j++){
                    $product = new Product();
                    $content = join($faker->paragraphs(5));
                    $product->setTitle($faker->text(5))
                            ->setContent($content)
                            ->setCreated($faker->dateTimeThisYear('now'))
                            ->setImage(rand(1,15).'.jpg')
                            ->setCategory($category)
                            ->setPrice($faker->numberBetween(12, 49))
                            ->setPublished(1);
                    $manager->persist($product);
                }
            }
            $manager->flush();
    }
}