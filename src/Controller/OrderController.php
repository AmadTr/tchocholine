<?php

namespace App\Controller;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Order;
use DateTimeImmutable;
use App\Entity\OrderLine;
use App\Service\Cart\CartService;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{

    /**
     * @IsGranted("ROLE_USER", statusCode=404, message="Page introuvable")
     *@Route("/", name="order_all")
     */
    public function index(OrderRepository $orderRepo,UserInterface $user): Response
    {
        // dd($orderRepo->findBy(['user'=>$user]));
        return $this->render('order/indexCustomer.html.twig', [
            'orders' => $orderRepo->findBy(['user'=>$user]),
        ]); //Je passe à mon twig le repository de mon order comme paramètre
    }

     /**
      * @IsGranted("ROLE_ADMIN", statusCode=404, message="Page introuvable")
     *@Route("/all", name="all_order")
     */
    public function indexAdmin(OrderRepository $orderRepo): Response
    {

        return $this->render('order/index.html.twig', [
            'orders' => $orderRepo->findByExampleField(),            
        ]);
    }

    /**
      * @IsGranted("ROLE_USER", statusCode=404, message="Page introuvable")
     *@Route("/add/{user}", name="order_add")
     */
    public function addOrder(
        User $user,
        cartService $cart,
        EntityManagerInterface $em
    ) {
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

    /**
     * @IsGranted("ROLE_USER", statusCode=404, message="Page introuvable")
     * @Route("/detail/{order}", name="order_detail")
     */
    public function showCdeDetail(Order $order)
    {
        return $this->render('/order/showCustomer.html.twig', [
            'ols' => $order->getOrderLines(),
            'order' => $order->getamount(),
        ]);
    }

     /**
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Page introuvable")
     * @Route("all/detail/{order}", name="detail_order_all")
     */
    public function showCdeDetailAmin(Order $order)
    {
        return $this->render('/order/show.html.twig', [
            'ols' => $order->getOrderLines(),
            'order' => $order->getamount(),
        ]);
    }


}
