<?php

namespace ArtMeBlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use ArtMeBlogBundle\Entity\Image;
use ArtMeBlogBundle\Form\ImageType;

class ImageController extends Controller
{
    /**
     * @param Request $request
     * @Route("/image/add", name="image_add_new")
     * @return RedirectResponse
     */
    public function newAction(Request $request)
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $image->getImageName();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $image->setImageName($fileName);

            // ... persist the $product variable or any other work
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($image);
            $em->flush();

            return $this->redirect($this->generateUrl('poem_show_all'));
        }

        return $this->render('image/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
