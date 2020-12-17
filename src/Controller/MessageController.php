<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Reponse;
use App\Entity\Recruter;
use App\Form\MessageType;
use App\Form\ReponseType;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use App\Repository\RecruterRepository;
use App\Repository\ReponseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController
{


    /**
     * @Route("/", name="message_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepository, ReponseRepository $reponseRepository): Response
    {
        $user = $this->getUser();
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findBy(['destinataire' => $user]),
            'reponses' => $reponseRepository->findBy(['destinataire' => $user])
        ]);
    }

    /**
     * @Route("/new/{id}", name="message_new", methods={"GET","POST"})
     */
    public function new($id, Request $request, User $user, UserRepository $userRepo): Response
    {
        $dest = $user->getId();
        $exp = $this->getUser();

        $userDest = $userRepo->findBy(['id' => $dest]);
        dump($userDest);
        //dd($exp);
        dump($dest);
        $role = $exp->getRoles();


        $message = new Message();
        $slug = $user->getSlug();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // la je vérifie qui envoie le message , user ou recruter ?
            if ($role[0] == "ROLES_RECRUTER") {

                $message->setRecruterExpediteur($exp);
            } else {

                $message->setUserExpediteur($exp);
            }

            $message->setDestinataire($userDest[0]);
            $message->setPostedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);


            $entityManager->flush();
            $this->addFlash('success', 'Votre message a bien été envoyé !');
            return $this->redirectToRoute('member_space');
        }

        return $this->render('message/new.html.twig', [
            'slug' => $slug,
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/reponse/{id}", name="message_reponse", methods={"GET","POST"})
     */
    public function reponse($id, Request $request, UserRepository $destiUser, RecruterRepository $destiRecruter): Response
    {

        $dest = intval($id); // me revoit l'id en int et pas en string
        $test = $destiUser->findOneBy(['id' => $dest]);
        dump($test);


        $essai = $destiRecruter->findOneBy(['id' => $dest]);
        dump($essai);

        $roleDestinataire = $essai ? $essai->getRoles() : $test->getRoles();



        dump($roleDestinataire);



        $exp = $this->getUser();

        $role = $exp->getRoles();


        $reponse = new Reponse();
        //  $slug = $user->getSlug();

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $reponse->setExpediteur($exp);

            if ($roleDestinataire[0] == "ROLES_RECRUTER") {
                $reponse->setDestinataire($essai);
            } else {
                $reponse->setDestinataire($test);
            }
            $reponse->setPostedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reponse);


            $entityManager->flush();
            $this->addFlash('success', 'Votre message a bien été envoyé !');
            return $this->redirectToRoute('member_space');
        }

        return $this->render('message/newReponse.html.twig', [
            // 'slug' => $slug,
            'message' => $reponse,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="message_show", methods={"GET"})
     */
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Message $message): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_index');
    }
}
