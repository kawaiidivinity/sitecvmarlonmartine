<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\Type\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/create_cv", name="create_cv")
     */
    public function createCV(){
        $em= $this->getDoctrine()->getManager();
        $p= new Product();
        $p->setTitle('My CV')
            ->setDescription('...')
            ->setPrice("12.99");
        $em->persist($p);
        $em->flush();

        return new Response("Saved with ID: " . $p->getId());
    }

    /**
     * @Route(
     *     "/cv/{key}/{dir}",
     *     name="show_cv",
     *     requirements={"_format": "html|"},
     *     defaults={"key": "title", "dir": "asc"}
     * )
     */
    public function showCV(Request $request, $key, $dir) {
        $form = $this->createFormBuilder()
            ->add('search', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $search = null;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
        }
        $products = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findAllAsArray($key, $dir, $search);
        if ($request->getRequestFormat() === 'json')
            return new JsonResponse($products);
        return $this->render('AppBundle:products:products.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
            'key' => $key,
            'dir' => $dir,
        ]);
    }
    /**
     * @Route("/product/{id}", name ="show_product")
     */
    public function showProduct($id) {
        $product = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($id);
        return $this->render('AppBundle:products:product.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/addcv/", name ="add_cv")
     */
    public function addcv(Request $request) {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        dump($form->isValid());
        if ($form->isSubmitted() && $form->isValid()) {
            $prodToSave = $form->getData();
            dump($prodToSave);
            $em = $this->getDoctrine()->getManager();
            $em->persist($prodToSave);
            $em->flush();
        }

        return $this->render('AppBundle:products:add_product.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
