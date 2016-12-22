<?php

namespace ArtMeBlogBundle\Controller;

use ArtMeBlogBundle\Entity\Song;
use ArtMeBlogBundle\Form\SongDeleteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use ArtMeBlogBundle\Form\SongType;

class SongController extends Controller
{
    /**
     * @param Request $request
     * @Route("/song/add", name="song_add_new")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return RedirectResponse
     */
    public function newAction(Request $request)
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var \Symfony\Component\HttpFoundation\File\UploadedFile $file
             */
            $file = $song->getSongName();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('songs_directory'),
                $fileName
            );

            $description = $song->getDescription();

            $song->setSongName($fileName);
            $song->setAuthor($this->getUser());
            $song->setDescription($description);
            $song->setAuthorId($this->getUser()->getId());

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($song);
            $em->flush();

            return $this->redirectToRoute('songs_show_all');
        }


        return $this->render('song/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/song/all", name="songs_show_all")
     */
    public function showAll()
    {
        $songs = $this->getDoctrine()->getRepository(Song::class)->findAll();
        return $this->render('song/showAll.html.twig', array(
            'songs' => $songs
        ));
    }

    /**
     * @Route("/song/delete/{id}", name="song_delete")
     * @param $id
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteSong(Request $request, $id)
    {
        $song = $this->getDoctrine()->getRepository(Song::class)->find($id);
        if ($song === null) {
            return $this->redirectToRoute('songs_show_all');
        }
        $currentUser = $this->getUser();
        if ($currentUser != $song->getAuthor()) {
            return $this->redirectToRoute('songs_show_all');
        }
        $songName = $song->getSongName();
        $path = $this->getParameter('songs_directory');
        $file = file($path . "/" . $songName);
        $form = $this->createForm(SongDeleteType::class, $song);


        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            //deleting the file from uploads/images..
            unlink($path . '/' . $songName);
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($song);
            $em->flush();

            return $this->redirectToRoute('songs_show_all');
        }

        return $this->render('song/delete.html.twig', array(
            'song' => $song,
            'form' => $form->createView()
        ));
    }
}
