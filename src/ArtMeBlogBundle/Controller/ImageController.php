<?php

namespace ArtMeBlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use ArtMeBlogBundle\Entity\Image;
use ArtMeBlogBundle\Form\ImageType;

class ImageController extends Controller
{
    /**
     * @param Request $request
     * @Route("/picture/add", name="image_add_new")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return RedirectResponse
     */
    public function newAction(Request $request)
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var \Symfony\Component\HttpFoundation\File\UploadedFile $file
             */
            $file = $image->getImageName();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            $image->setImageName($fileName);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($image);
            $em->flush();

            return $this->redirect($this->generateUrl('pictures_show_all'));
        }

        return $this->render('image/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/picture/all", name="pictures_show_all")
     */
    public function showAll(){
        $pictures = $this->getDoctrine()->getRepository(Image::class)->findAll();
        return $this->render('image/showAll.html.twig', array(
            'pictures' => $pictures
        ));
    }
}
