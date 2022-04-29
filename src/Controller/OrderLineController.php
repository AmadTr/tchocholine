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
        return $this->render('order_line/indexCustomer.html.twig', [
            'order_lines' => $orderLineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/all", name="app_order_line_all", methods={"GET"})
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Page introuvable")
     * 
     */
    public function indexAdmin(OrderLineRepository $orderLineRepository): Response
    {
        return $this->render('order_line/index.html.twig', [
            'order_lines' => $orderLineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_order_line_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OrderLineRepository $orderLineRepository): Response
    {
        $orderLine = new OrderLine();
        $form = $this->createForm(OrderLineType::class, $orderLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderLineRepository->add($orderLine);
            return $this->redirectToRoute('app_order_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order_line/new.html.twig', [
            'order_line' => $orderLine,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_order_line_show", methods={"GET"})
     */
    public function show(OrderLine $orderLine): Response
    {
        return $this->render('order_line/show.html.twig', [
            'order_line' => $orderLine,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_order_line_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, OrderLine $orderLine, OrderLineRepository $orderLineRepository): Response
    {
        $form = $this->createForm(OrderLineType::class, $orderLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderLineRepository->add($orderLine);
            return $this->redirectToRoute('app_order_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order_line/edit.html.twig', [
            'order_line' => $orderLine,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_order_line_delete", methods={"POST"})
     */
    public function delete(Request $request, OrderLine $orderLine, OrderLineRepository $orderLineRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderLine->getId(), $request->request->get('_token'))) {
            $orderLineRepository->remove($orderLine);
        }

        return $this->redirectToRoute('app_order_line_index', [], Response::HTTP_SEE_OTHER);
    }
}
