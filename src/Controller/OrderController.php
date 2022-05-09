<?php

namespace App\Controller;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Order;
use DateTimeImmutable;
use App\Entity\OrderLine;
use App\Service\Cart\CartService;
use App\Repository\OrderRepository;
use App\Repository\CategoryRepository;
use App\Repository\CatPremierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{

    /**
     *@Route("/", name="order_all")
     */
    public function index(OrderRepository $orderRepo, UserInterface $user, CatPremierRepository $catPremierRepository, CategoryRepository $categoryRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');

            return $this->render('order/indexCustomer.html.twig', [
                'orders' => $orderRepo->findBy(['user' => $user]),
                'catSups' => $catPremierRepository->findAll(),
                'categories' => $categoryRepository->findAll()
            ]); //Je passe à mon twig le repository de mon order comme paramètre
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }

    }

    /**
     *@Route("/all", name="all_order")
     */
    public function indexAdmin(OrderRepository $orderRepo): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('order/index.html.twig', [
                'orders' => $orderRepo->findByExampleField(),
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }


    /**
     *@Route("/add/{user}", name="order_add")
     */
    public function addOrder(User $user,cartService $cart,EntityManagerInterface $em) 
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');

            //On a récupéré l'id de l'utilisateur directement à partir de index.twig
            $faker = \Faker\Factory::create();
            if ($user) {
                $order = new Order();
                $order->setRefOrder('Ref' . $faker->numberBetween($min = 1000000, $max = 9999999));
                $order->setOrderDate(new \DateTimeImmutable());
                $order->setAmount($cart->getCartTotal()); //On utilise la méthode getCartTotal pour récupérer le total de produits dans le panier
                $order->setUser($user);

                $em->persist($order);

                //Création des lignes de commandes
                $cartDetails = $cart->getCartDetails();
                foreach ($cartDetails as $line) {
                    $orderLine = new OrderLine();
                    $orderLine->setQuantity($line['quantity']);
                    $orderLine->setProduct($line['product']);
                    $orderLine->setOrders($order);

                    $totalLine = $line['quantity'] * $line['product']->getPrice();

                    $orderLine->setAmount($totalLine);

                    $em->persist($orderLine);
                    $em->flush();
                }
                $em->flush();
                $cart->clearCart();
                return $this->redirectToRoute('order_all');
            } else {
                return $this->redirectToRoute('home');
            }
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/detail/{order}", name="order_detail")
     */
    public function showCdeDetail(Order $order, CatPremierRepository $catPremierRepository, CategoryRepository $categoryRepository)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
            return $this->render('/order/showCustomer.html.twig', [
                'ols' => $order->getOrderLines(),
                'order' => $order->getamount(),
                'catSups' => $catPremierRepository->findAll(),
                'categories' => $categoryRepository->findAll()
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
        
    }


    /**
     * @Route("all/detail/{order}", name="detail_order_all")
     */
    public function showCdeDetailAmin(Order $order)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            return $this->render('/order/show.html.twig', [
                'ols' => $order->getOrderLines(),
                'order' => $order->getamount(),
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }
}
