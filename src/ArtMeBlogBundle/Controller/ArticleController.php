<?php

namespace ArtMeBlogBundle\Controller;

use ArtMeBlogBundle\Entity\Article;
use ArtMeBlogBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/poem/add", name="article_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request){
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $article->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('poem_show_all');
        }

        return $this->render('article/create.html.twig', array(
            'form' => $form->createView()));

    }

    /**
     * @Route("/poem/show/all", name="poem_show_all")
     *
     */
    public function showAllArticles(){
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/showAll.html.twig', ['articles' => $articles]);
    }
}
