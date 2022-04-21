<?php

namespace App\Controller;

use App\Entity\CategoryBlog;
use App\Form\CategoryBlogType;
use App\Repository\CategoryBlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category/blog")
 */
class CategoryBlogController extends AbstractController
{
    /**
     * @Route("/", name="app_category_blog_index", methods={"GET"})
     */
    public function index(CategoryBlogRepository $categoryBlogRepository): Response
    {
        return $this->render('category_blog/index.html.twig', [
            'category_blogs' => $categoryBlogRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_category_blog_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoryBlogRepository $categoryBlogRepository): Response
    {
        $categoryBlog = new CategoryBlog();
        $form = $this->createForm(CategoryBlogType::class, $categoryBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryBlogRepository->add($categoryBlog);
            return $this->redirectToRoute('app_category_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category_blog/new.html.twig', [
            'category_blog' => $categoryBlog,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_category_blog_show", methods={"GET"})
     */
    public function show(CategoryBlog $categoryBlog): Response
    {
        return $this->render('category_blog/show.html.twig', [
            'category_blog' => $categoryBlog,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_category_blog_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CategoryBlog $categoryBlog, CategoryBlogRepository $categoryBlogRepository): Response
    {
        $form = $this->createForm(CategoryBlogType::class, $categoryBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryBlogRepository->add($categoryBlog);
            return $this->redirectToRoute('app_category_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category_blog/edit.html.twig', [
            'category_blog' => $categoryBlog,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_category_blog_delete", methods={"POST"})
     */
    public function delete(Request $request, CategoryBlog $categoryBlog, CategoryBlogRepository $categoryBlogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryBlog->getId(), $request->request->get('_token'))) {
            $categoryBlogRepository->remove($categoryBlog);
        }

        return $this->redirectToRoute('app_category_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}
