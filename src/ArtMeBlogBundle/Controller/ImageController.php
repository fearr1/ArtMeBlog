<?php

namespace ArtMeBlogBundle\Controller;

use ArtMeBlogBundle\Form\ImageDeleteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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

            //$file->guessExtension();



            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            $description = $image->getDescription();

            $image->setImageName($fileName);
            $image->setAuthor($this->getUser());
            $image->setDescription($description);

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

    /**
     * @Route("/picture/delete/{id}", name="image_delete")
     * @param $id
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteImage(Request $request, $id){
        $image = $this->getDoctrine()->getRepository(Image::class)->find($id);
        $imageName = $image->getImageName();
        $path = $this->getParameter('images_directory');
        $file = file($path."/".$imageName);
        $form = $this->createForm(ImageDeleteType::class, $image);



        $form->handleRequest($request);
        if($form->isSubmitted()) {
            //deleting the file from uploads/images..
            unlink($path . '/' . $imageName);
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($image);
            $em->flush();

            return $this->redirectToRoute('pictures_show_all');
        }

        return $this->render('image/delete.html.twig', array(
            'image' => $image,
            'form' => $form->createView()
        ));
    }

}
