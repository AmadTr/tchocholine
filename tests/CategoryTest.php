<?php

namespace App\Tests;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;
use App\Controller\CategoryController;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class CategoryTest extends TestCase
{
    public function testSetNom()
    {
        $cat = new Category;
        $value = 'fruits';
        $cat->setName($value);
        $this->assertEquals($value, $cat->getName());
    }
//     public function testGetAllCategories(){
//         $cat = new CategoryController;
//         $registry = new ManagerRegistry();
// $categoryRepository = new CategoryRepository($registry);
//         $this->assertEquals(array(),$cat->index($categoryRepository));
//     }
    // public function testAddCategory(){

    //     $this->assertEquals(true,addCategory(1));
    // }
    // public function testGetCategoryById(){

    //     $this->assertEquals(false,getCategoryById(1));
    // }

    // public function testDelCategory(){

    //     $this->assertEquals(true,deleteCategoryById(1));
    // }
    // public function testUpdateCategory(){

    //     $this->assertEquals(true,updateCategory('fruit',1));
    // }
}
