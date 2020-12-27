<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RecruType;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="user_show", methods={"GET"})
     */
    public function show($slug, User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'slug' => $slug,
            'user' => $user,
        ]);
    }


    /**
     * @Route("/{slug}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit($slug, Request $request, UserPasswordEncoderInterface $encoder): Response
    {

        $user = $this->getUser();


        $role = $user->getRoles();

        // si c'est un user qui edit
        if ($role[0] == "ROLE_USER") {
            $form = $this->createForm(User1Type::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('success', 'Votre profil a bien été mis à jour !');
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('member_space');
            }
            //si c'est un recruter qui edit
        } else {

            $recruter = $this->getUser();
            $form = $this->createForm(RecruType::class, $recruter);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('success', 'Votre profil a bien été mis à jour !');
                $hash = $encoder->encodePassword($recruter, $recruter->getPassword());
                $recruter->setPassword($hash);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('member_space');
            }
        }

        return $this->render('user/edit.html.twig', [
            'slug' => $slug,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $user = $this->getUser();

        $role = $user->getRoles();


        // code original
        if ($role[0] == "ROLE_USER") {
            $this->container->get('security.token_storage')->setToken(null);
            if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();

                $user->setMainAvatar("");
                $entityManager->remove($user);
                $this->addFlash('success', 'Votre compte a bien été supprimé !');
                $entityManager->flush();
            }
        } else {

            $recruter = $this->getUser();
            $this->container->get('security.token_storage')->setToken(null);
            if ($this->isCsrfTokenValid('delete' . $recruter->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();

                $recruter->setMainAvatar("");
                $entityManager->remove($recruter);
                $this->addFlash('success', 'Votre compte a bien été supprimé !');
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('homepage');
    }
}
