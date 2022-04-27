<?php

namespace App\Controller;

use App\Entity\ArticleBlog;
use App\Repository\ImageBlogRepository;
use App\Repository\ArticleBlogRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(ArticleBlogRepository $articleBlogRepository, ImageBlogRepository $imageBlogRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'article_blogs' => $articleBlogRepository->findAll(),
        ]);
    }


    /**
     * @Route("/blog/{id}", name="blog_show", methods={"GET"})
     */
    public function show(ArticleBlog $articleBlog): Response
    {
        return $this->render('blog/show.html.twig', [
            'article_blog' => $articleBlog,
        ]);
    }
}
