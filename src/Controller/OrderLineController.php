<?php

namespace App\Controller;

use App\Entity\OrderLine;
use App\Form\OrderLineType;
use App\Repository\OrderLineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/order/line")
 */
class OrderLineController extends AbstractController
{
    /**
     * @Route("/", name="app_order_line_index", methods={"GET"})
     */
    public function index(OrderLineRepository $orderLineRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');

            return $this->render('order_line/indexCustomer.html.twig', [
                'order_lines' => $orderLineRepository->findAll(),
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }

    }


    /**
     * @Route("/{id}", name="app_order_line_show", methods={"GET"})
     */
    public function show(OrderLine $orderLine): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');

            return $this->render('order_line/show.html.twig', [
                'order_line' => $orderLine,
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/all", name="app_order_line_all", methods={"GET"})
     * 
     */
    public function indexAdmin(OrderLineRepository $orderLineRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('order_line/index.html.twig', [
                'order_lines' => $orderLineRepository->findAll(),
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }




    /**
     * @Route("/new", name="app_order_line_new", methods={"GET", "POST"})
     */
    // public function new(Request $request, OrderLineRepository $orderLineRepository): Response
    // {
    //     $orderLine = new OrderLine();
    //     $form = $this->createForm(OrderLineType::class, $orderLine);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $orderLineRepository->add($orderLine);
    //         return $this->redirectToRoute('app_order_line_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('order_line/new.html.twig', [
    //         'order_line' => $orderLine,
    //         'form' => $form,
    //     ]);
    // }


 
}
