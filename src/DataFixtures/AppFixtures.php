<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        // $product = new Product();
        // $manager->persist($product);
        // create 5 categories! Bam!
        for ($cat = 0; $cat <3; $cat++) {
            $category = new Category();
            $category->setName($faker->word());
            $manager->persist($category);
        
        // create 20 products! Bam!
        for ($i = 1; $i <11; $i++) {
            $product = new Product();
            $product->setName($faker->word());
            $product->setDescription($faker->paragraph(2));
            $product->setCategory($category);
            $product->setStock($faker->numberBetween($min = 2, $max = 4));
            $product->setPrice($faker->randomFloat(2, 20, 30));
            $product->setPhoto('livre'.$i.'.jpg');
            $manager->persist($product);
        }
    }

        $manager->flush();
    }
}
