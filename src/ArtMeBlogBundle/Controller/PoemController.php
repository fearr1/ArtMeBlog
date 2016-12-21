<?php

namespace ArtMeBlogBundle\Controller;

use ArtMeBlogBundle\Entity\Poem;
use ArtMeBlogBundle\Form\PoemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    /**
     * @Route("/poem/delete/{id}", name="poem_delete")
     * @param Request $request
     * @param $id
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return RedirectResponse
     */
    public function deletePoem(Request $request, $id ){
        $poem = $this->getDoctrine()->getRepository(Poem::class)->find($id);

        if($poem === null){
            return $this->redirectToRoute('poem_show_all');
        }

        $currentUser = $this->getUser();
        if($currentUser != $poem->getAuthor()){
            return $this->redirectToRoute('poem_show_all');
        }
        $currentUser = $this->getUser();
        if(!$currentUser->isAuthorPoem($poem))
        {
            return $this->redirectToRoute('poem_show_all');
        }
        $form = $this->createForm(PoemType::class, $poem);

        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($poem);
            $em->flush();

            return $this->redirectToRoute('poem_show_all');
        }
        return $this->render('poem/delete.html.twig', array(
            'poem' => $poem,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/poem/edit/{id}", name="poem_edit")
     * @param Request $request
     * @param $id
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return RedirectResponse
     */
    public function editPoem(Request $request, $id ){
        $poem = $this->getDoctrine()->getRepository(Poem::class)->find($id);

        if($poem === null){
            return $this->redirectToRoute('poem_show_all');
        }

        $currentUser = $this->getUser();
        if($currentUser != $poem->getAuthor()){
            return $this->redirectToRoute('poem_show_all');
        }
        $form = $this->createForm(PoemType::class, $poem);

        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($poem);
            $em->flush();

            return $this->redirectToRoute('poem_show_all');
        }
        return $this->render('poem/edit.html.twig', array(
            'poem' => $poem,
            'form' => $form->createView()
        ));
    }
}
