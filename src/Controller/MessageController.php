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
    public function index(MessageRepository $messageRepo, ReponseRepository $reponseRepo, UserRepository $useRepo): Response
    {

        $user = $this->getUser();

        $role = $user->getRoles();

        if ($role[0] == "ROLE_USER") {

            $messages = $messageRepo->findBy(['userDestinataire' => $user]); // liste des messages 
            $reponses = $reponseRepo->findBy(['destinataire' => $user]); // liste des reponses
            $expediteur = null;


            //Pour chaque MESSAGE je dois récuperer les données qui concernent l'expediteur
            for ($m = 0; $m < count($messages); $m++) {
                $messuser = $messages[$m]->getUserExpediteur();
                $messrecruter = $messages[$m]->getRecruterExpediteur();

                //si le destinataire est un recruter
                $destirecruter = $messages[$m]->getRecruterDestinataire();


                //si le message reçu est envoyé par un user
                if ($messuser != null) {
                    $messuserid  = $messuser->getId();
                    $expediteur = $messageRepo->findBy(['userExpediteur' => $messuserid]);
                    // return $expediteur;
                }
                // sinon si le message reçu est envoyé par un recruteur
                elseif ($messrecruter != null) {
                    $messrecruterid =  $messrecruter->getId();
                    $expediteur = $messageRepo->findBy(['recruterExpediteur' => $messrecruterid]);
                    // return $expediteur;
                }
            }

            //Pour chaque REPONSE je dois récuperer les données qui concernent l'expediteur
            for ($r = 0; $r < count($reponses); $r++) {
                $reponseuser = $reponses[$m]->getExpediteur();
                // $reponserecruter = $reponses[$m]->getRecruterExpediteur();
                dump($reponseuser);
                //si le destinataire est un recruter
                $reponserecruter = $reponses[$m]->getExpediteur();
                dump($reponserecruter);

                //si le message reçu est envoyé par un user
                if ($reponseuser != null) {
                    $reponseuserid  = $reponseuser->getId();
                    $expediteur = $reponseRepo->findBy(['expediteur' => $reponseuserid]);
                    // return $expediteur;
                }
                // sinon si le message reçu est envoyé par un recruteur
                elseif ($reponserecruter != null) {
                    $reponserecruterid =  $reponserecruter->getId();
                    $expediteur = $reponseRepo->findBy(['recruterExpediteur' => $reponserecruterid]);
                    // return $expediteur;
                }
            }



            return $this->render('message/index.html.twig', [
                'messages' => $messages,
                'expediteurs' => $expediteur,
                'reponses' => $reponses
            ]);
        }
    }

    /**
     * @Route("/new/{id}", name="message_new", methods={"GET","POST"})
     */
    public function new(Request $request, User $user, UserRepository $userRepo, RecruterRepository $recrutRepo): Response
    {
        // $dest = $user->getId();// la si un user a le meme id qu'un recruter c'est le bordel


        $dest = $user->getSlug(); // je vais tenter avec le slug

        $exp = $this->getUser();

        $userDest = $userRepo->findBy(['slug' => $dest]); // je recupere le destinataire user
        $recrutDest = $recrutRepo->findBy(['slug' => $dest]); // je recupere le destinataire recruter

        //dump($userDest, $recrutDest);
        //dd($exp);
        // dump($dest); // la je vois son slug
        $role = $exp->getRoles();
        //dd($role[0]);
        $roleDestiUser = $userDest[0]->getRoles();
        //dump($roleDestiUser[0]);
        //  $roleDestiRecruter = $recrutDest[0]->getRoles();


        dump($roleDestiUser[0]); // me renvoit le role de l'user destinataire

        $message = new Message();
        $slug = $user->getSlug();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // la je vérifie qui envoie le message , user ou recruter ?
            if ($role[0] == "ROLES_RECRUTER") {
                //dd("recruter expediteur");
                $message->setRecruterExpediteur($exp); // si l'expediteur est un recruteur
            } else {
                // dd("user expediteur");
                $message->setUserExpediteur($exp); // si l'expediteur est un user
            }

            if ($roleDestiUser[0] == "ROLE_USER") {
                //dd("user desti");
                $message->setUserDestinataire($userDest[0]); // si le destinataire est un user

            } else {
                // dd("recruter desti");
                $message->setRecruterDestinataire($recrutDest); // si le destinataire est un recruteur
            }

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
    public function reponse($id, Request $request, UserRepository $destiUser, RecruterRepository $destiRecruter, MessageRepository $messageRepo): Response
    {

        $dest = intval($id); // me revoit l'id en int et pas en string
        $test = $destiUser->findOneBy(['id' => $dest]);
        dump($dest); // me donne l'id du message il me faut l'id de l'expediteur


        $messageid = $messageRepo->findBy(['id' => $dest]);

        dump($messageid);

        $trucuser = $messageid[0]->getUserExpediteur();
        $trucrecruter = $messageid[0]->getRecruterExpediteur();


        $exp = $this->getUser();


        $reponse = new Reponse();
        //  $slug = $user->getSlug();

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $reponse->setExpediteur($exp);

            if ($trucrecruter != null) {
                $recruterid = $trucrecruter->getId();
                $aquienvoyerrecruter = $destiRecruter->findBy(['id' => $recruterid]);
                $reponse->setDestinataireRecruter($aquienvoyerrecruter[0]);
            } elseif ($trucuser != null) {
                $userid = $trucuser->getId();
                $aquienvoyeruser = $destiUser->findBy(['id' => $userid]);
                $reponse->setDestinataire($aquienvoyeruser[0]);
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
