<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ImageBlog;
use App\Entity\CatPremier;
use App\Entity\ArticleBlog;
use App\Entity\CategoryBlog;
use App\Entity\PhotosProduct;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        for ($catSup = 0; $catSup < 3; $catSup++) {
            $categorySup = new CatPremier();
            $categorySup->setName('catégorieSup' . $catSup);
            $manager->persist($categorySup);

            // $product = new Product();
            // $manager->persist($product);
            // create 5 categories! Bam!
            for ($cat = 1; $cat < 2; $cat++) {
                $category = new Category();
                $category->setName('catégorie' . $cat);
                $category->setCatPremier($categorySup);
                $manager->persist($category);

                // create 20 products! Bam!
                for ($i = 1; $i < 3; $i++) {
                    $product = new Product();
                    $product->setName($faker->word());
                    $product->setDescription($faker->paragraph(2));
                    $product->setCategory($category);
                    $product->setStock(
                        $faker->numberBetween($min = 2, $max = 4)
                    );
                    $product->setPrice($faker->randomFloat(2, 20, 30));
                    $product->setPhoto('bijoux' . $i . '.jpg');
                    $manager->persist($product);

                    for ($k = 1; $k < 3; $k++) {
                        $image = new PhotosProduct();
                        $image->setLink('bijoux' . $k . '.jpg');
                        $image->setProduct($product);
                        $manager->persist($image);
                    }
                }
            }
        }

        $manager->flush();

        for ($catBlog = 1; $catBlog < 2; $catBlog++) {
            $categoryBlog = new CategoryBlog();
            $categoryBlog->setName('catégorie' . $catBlog);
            $categoryBlog->setDescription($faker->paragraph);
            $manager->persist($categoryBlog);

            // create 20 products! Bam!
            for ($i = 1; $i < 3; $i++) {
                $article = new ArticleBlog();
                $article->setTitle($faker->word());
                $article->setPhoto('bijoux' . $i . '.jpg');
                $article->setPostDate($faker->dateTime());
                $article->setContent($faker->paragraph(2));
                $article->setCategoryblog($categoryBlog);
                $manager->persist($article);

                for ($k = 1; $k < 3; $k++) {
                    $imageBlog = new ImageBlog();
                    $imageBlog->setLink('bijoux' . $k . '.jpg');
                    $imageBlog->setArticleBlog($article);
                    $manager->persist($imageBlog);
                }
            }
        }
        $manager->flush();
    }
}
