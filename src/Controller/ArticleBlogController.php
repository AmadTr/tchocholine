<?php

namespace App\Controller;

use App\Entity\ImageBlog;
use App\Entity\ArticleBlog;
use App\Form\ArticleBlogType;
use App\Repository\ArticleBlogRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/article/blog")
 */
class ArticleBlogController extends AbstractController
{
    /**
     * @Route("/", name="app_article_blog_index", methods={"GET"})
     */
    public function index(ArticleBlogRepository $articleBlogRepository): Response
    {
        return $this->render('article_blog/index.html.twig', [
            'article_blogs' => $articleBlogRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_article_blog_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ArticleBlogRepository $articleBlogRepository): Response
    {
        $articleBlog = new ArticleBlog();
        $form = $this->createForm(ArticleBlogType::class, $articleBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('imageBlog')->getData();

            foreach ($images as $image) {
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $img = new ImageBlog();
                $img->setLink($fichier);
                $articleBlog->addImage($img);
            }



            $articleBlogRepository->add($articleBlog);
            return $this->redirectToRoute('app_article_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article_blog/new.html.twig', [
            'article_blog' => $articleBlog,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_article_blog_show", methods={"GET"})
     */
    public function show(ArticleBlog $articleBlog): Response
    {
        return $this->render('article_blog/show.html.twig', [
            'article_blog' => $articleBlog,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_article_blog_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ArticleBlog $articleBlog, ArticleBlogRepository $articleBlogRepository): Response
    {
        $form = $this->createForm(ArticleBlogType::class, $articleBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageBlog = $form->get('imageBlog')->getData();

            foreach ($imageBlog as $image) {
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $img = new ImageBlog();
                $img->setLink($fichier);
                $articleBlog->addImage($img);
            }

            $articleBlogRepository->add($articleBlog);
            return $this->redirectToRoute('app_article_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article_blog/edit.html.twig', [
            'article_blog' => $articleBlog,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_article_blog_delete", methods={"POST"})
     */
    public function delete(Request $request, ArticleBlog $articleBlog, ArticleBlogRepository $articleBlogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleBlog->getId(), $request->request->get('_token'))) {
            $articleBlogRepository->remove($articleBlog);
        }

        return $this->redirectToRoute('app_article_blog_index', [], Response::HTTP_SEE_OTHER);
    }

 /**
    * @Route("/delete/image/{id}", name="article_delete_image", methods={"DELETE"})
    */
    public function deleteImage(ImageBlog $image, Request $request){
    

        $data = json_decode($request->getContent(), true);
        
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            $name = $image->getlink();
            unlink($this->getParameter('images_directory').'/'.$name);

            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        }
        else{
            return new JsonResponse(['error' => 'token invalide'], 400);
        }

    }

}


