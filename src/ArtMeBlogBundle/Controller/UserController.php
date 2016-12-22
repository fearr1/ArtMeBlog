<?php

namespace ArtMeBlogBundle\Controller;

use ArtMeBlogBundle\Entity\Image;
use ArtMeBlogBundle\Entity\Poem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ArtMeBlogBundle\Entity\User;
use ArtMeBlogBundle\Form\UserType;
use ArtMeBlogBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('security_login');
        }

        return $this->render(
            'default/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/profile", name="user_profile")
     */
    public function profileAction()
    {
        $user = $this->getUser();
        return $this->render("user/profile.html.twig", ['user' => $user]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/profile/edit", name="profile_edit")
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        if ($user === null) {
            return $this->redirectToRoute('security_login');
        }

        $oldPassword = $user->getPassword();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $newPassword = $user->getPassword();
            if ($newPassword == null) {
                $user->setPassword($oldPassword);
            } else {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_profile');
        }
        return $this->render("user/editProfile.html.twig", array(
            'user' => $user,
            'form' => $form->createView()));
    }

}
