<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $age = date('Y') - 1998;
        $tab = [1, 1, 2, 3, 5, 8, 13];
        return $this->render('AppBundle:default:index.html.twig', [
            'firstname' => 'Marlon',
            'age' => $age,
            'fibo' => $tab,
        ]);
    }

    /**
     * @Route("/hello/{name}", name="hello")
     */
    public function helloAction(Request $request, $name) {
        return new Response('<h1>Hello '. $name . '</h1>');
    }

    /**
     * @Route("/secure", name="secure_test")
     */
    public function secureAction(Request $request)
    {
        return $this->render('AppBundle:default:secure.html.twig');
    }

    /**
     * @Route("/prompt/{name}", name="prompt", defaults={"name"=null})
     */
    public function prompt($name) {
        return $this->render('AppBundle:default:prompt.html.twig', [
            'name' => $name,
            'url' => $this->generateUrl('prompt'),
        ]);
    }
}
