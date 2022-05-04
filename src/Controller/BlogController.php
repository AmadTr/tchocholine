<?php

namespace App\Controller;

use App\Entity\ArticleBlog;
use App\Service\Cart\CartService;
use App\Repository\CategoryRepository;
use App\Repository\ImageBlogRepository;
use App\Repository\CatPremierRepository;
use App\Repository\ArticleBlogRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(ArticleBlogRepository $articleBlogRepository, ImageBlogRepository $imageBlogRepository, CatPremierRepository $catPremierRepository, CategoryRepository $categoryRepository,CartService $cart): Response
    {
        
        return $this->render('blog/index.html.twig', [
            'article_blogs' => $articleBlogRepository->findAll(),
            'catSups' => $catPremierRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'items' => $cart->getCartDetails()
        ]);
    }


    /**
     * @Route("/blog/{id}", name="blog_show", methods={"GET"})
     */
    public function show(ArticleBlog $articleBlog, CatPremierRepository $catPremierRepository, CategoryRepository $categoryRepository): Response
    {
        
        return $this->render('blog/show.html.twig', [
            'article_blog' => $articleBlog,
            'catSups' => $catPremierRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
