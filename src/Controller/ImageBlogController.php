<?php

namespace App\Controller;

use App\Entity\ImageBlog;
use App\Form\ImageBlogType;
use App\Repository\ImageBlogRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/image/blog")
 * @IsGranted("ROLE_ADMIN", statusCode=404, message="Page introuvable")
 * 
 */
class ImageBlogController extends AbstractController
{
    /**
     * @Route("/", name="app_image_blog_index", methods={"GET"})
     */
    public function index(ImageBlogRepository $imageBlogRepository): Response
    {
        return $this->render('image_blog/index.html.twig', [
            'image_blogs' => $imageBlogRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_image_blog_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ImageBlogRepository $imageBlogRepository): Response
    {
        $imageBlog = new ImageBlog();
        $form = $this->createForm(ImageBlogType::class, $imageBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageBlogRepository->add($imageBlog);
            return $this->redirectToRoute('app_image_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('image_blog/new.html.twig', [
            'image_blog' => $imageBlog,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_image_blog_show", methods={"GET"})
     */
    public function show(ImageBlog $imageBlog): Response
    {
        return $this->render('image_blog/show.html.twig', [
            'image_blog' => $imageBlog,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_image_blog_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ImageBlog $imageBlog, ImageBlogRepository $imageBlogRepository): Response
    {
        $form = $this->createForm(ImageBlogType::class, $imageBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageBlogRepository->add($imageBlog);
            return $this->redirectToRoute('app_image_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('image_blog/edit.html.twig', [
            'image_blog' => $imageBlog,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_image_blog_delete", methods={"POST"})
     */
    public function delete(Request $request, ImageBlog $imageBlog, ImageBlogRepository $imageBlogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imageBlog->getId(), $request->request->get('_token'))) {
            $imageBlogRepository->remove($imageBlog);
        }

        return $this->redirectToRoute('app_image_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}
