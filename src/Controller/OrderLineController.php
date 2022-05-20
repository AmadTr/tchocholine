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

}
