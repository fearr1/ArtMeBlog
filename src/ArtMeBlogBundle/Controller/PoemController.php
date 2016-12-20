<?php

namespace ArtMeBlogBundle\Controller;

use ArtMeBlogBundle\Entity\Poem;
use ArtMeBlogBundle\Form\PoemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PoemController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/poem/add", name="poem_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request){
        $poem = new Poem();
        $form = $this->createForm(PoemType::class, $poem);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $poem->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($poem);
            $em->flush();
            return $this->redirectToRoute('poem_show_all');
        }

        return $this->render('poem/create.html.twig', array(
            'form' => $form->createView()));

    }

    /**
     * @Route("/poem/show/all", name="poem_show_all")
     *
     */
    public function showAllPoems(){
        $poems = $this->getDoctrine()->getRepository(Poem::class)->findAll();
        return $this->render('poem/showAll.html.twig', ['poems' => $poems]);
    }

    /**
     * @Route("/poem/show/{id}", name="poem_show_one")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOne( $id){
        $poem = $this->getDoctrine()->getRepository(Poem::class)->find($id);
        return $this->render('poem/showOne.html.twig', array(
            'poem' => $poem
        ));
    }
}
