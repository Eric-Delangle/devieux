<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Reponse;
use App\Form\MessageType;
use App\Form\ReponseType;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use App\Repository\ReponseRepository;
use App\Repository\RecruterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController
{


    /**
     * @Route("/", name="message_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepo, ReponseRepository $reponseRepo): Response
    {

        $user = $this->getUser();
        $role = $user->getRoles();


        $messages = $messageRepo->findBy(['userDestinataire' => $user]); // liste des messages 
        $reponses = $reponseRepo->findBy(['destinataire' => $user]); // liste des reponses


        if ($role[0] == "ROLE_USER") {


            $expediteur = null;


            //Pour chaque MESSAGE je dois récuperer les données qui concernent l'expediteur
            for ($m = 0; $m < count($messages); $m++) {
                $messuser = $messages[$m]->getUserExpediteur();
                $messrecruter = $messages[$m]->getRecruterExpediteur();

                //si le message reçu est envoyé par un user
                if ($messuser != null) {
                    $messuserid  = $messuser->getId();
                    $expediteur = $messageRepo->findBy(['userExpediteur' => $messuserid]);
                }
                // sinon si le message reçu est envoyé par un recruteur
                elseif ($messrecruter != null) {
                    $messrecruterid =  $messrecruter->getId();
                    $expediteur = $messageRepo->findBy(['recruterExpediteur' => $messrecruterid]);
                }
            }

            //Pour chaque REPONSE je dois récuperer les données qui concernent l'expediteur
            for ($r = 0; $r < count($reponses); $r++) {
                $reponseuser = $reponses[$r]->getExpediteur();
                //si le destinataire est un recruter
                $reponserecruter = $reponses[$r]->getExpediteur();

                //si le message reçu est envoyé par un user
                if ($reponseuser != null) {
                    $reponseuserid  = $reponseuser->getId();
                    $expediteur = $reponseRepo->findBy(['expediteur' => $reponseuserid]);
                }
                // sinon si le message reçu est envoyé par un recruteur
                elseif ($reponserecruter != null) {
                    $reponserecruterid =  $reponserecruter->getId();
                    $expediteur = $reponseRepo->findBy(['recruterExpediteur' => $reponserecruterid]);
                }
            }


            $key_password = "lacléquipermetdecrypteretdedecrypter";


            // je modifie mon tableau de messages pour afficher les données décryptées 
            foreach ($messages as $message) {
                // DECRYPTER
                $decrypted_titre = openssl_decrypt(
                    $message->getTitre(),
                    "AES-128-ECB",
                    $key_password
                );

                $message->setTitre($decrypted_titre);

                $decrypted_message = openssl_decrypt(
                    $message->getMessage(),
                    "AES-128-ECB",
                    $key_password
                );

                $message->setMessage($decrypted_message);
            }

            // je modifie mon tableau des reponses pour afficher les données décryptées 
            foreach ($reponses as $reponse) {
                // DECRYPTER
                dump($reponse);
                $decrypted_reponse = openssl_decrypt(
                    $reponse->getMessage(),
                    "AES-128-ECB",
                    $key_password
                );

                $reponse->setMessage($decrypted_reponse);
                dump($decrypted_reponse);
            }


            return $this->render('message/index.html.twig', [
                'messages' => $messages,
                'expediteurs' => $expediteur,
                'reponses' => $reponses
            ]);
        }

        // La il faut que si le user est un recruter je dois chercher le user dans les recruters
        elseif ($role[0] == "ROLES_RECRUTER") {

            $messages = $messageRepo->findBy(['recruterDestinataire' => $user]); // liste des messages 

            $reponses = $reponseRepo->findBy(['destinataire_recruter' => $user]); // liste des reponses
            $expediteur = null;


            //Pour chaque MESSAGE je dois récuperer les données qui concernent l'expediteur
            for ($m = 0; $m < count($messages); $m++) {
                $messuser = $messages[$m]->getUserExpediteur();
                $messrecruter = $messages[$m]->getRecruterExpediteur();

                //si le message reçu est envoyé par un user
                if ($messuser != null) {
                    $messuserid  = $messuser->getId();
                    $expediteur = $messageRepo->findBy(['userExpediteur' => $messuserid]);
                }
                // sinon si le message reçu est envoyé par un recruteur
                elseif ($messrecruter != null) {
                    $messrecruterid =  $messrecruter->getId();
                    $expediteur = $messageRepo->findBy(['recruterExpediteur' => $messrecruterid]);
                }
            }

            //Pour chaque REPONSE je dois récuperer les données qui concernent l'expediteur
            for ($r = 0; $r < count($reponses); $r++) {
                $reponseuser = $reponses[$r]->getExpediteur();
                //si le destinataire est un recruter
                $reponserecruter = $reponses[$r]->getExpediteur();

                //si le message reçu est envoyé par un user
                if ($reponseuser != null) {
                    $reponseuserid  = $reponseuser->getId();
                    $expediteur = $reponseRepo->findBy(['expediteur' => $reponseuserid]);
                }
                // sinon si le message reçu est envoyé par un recruteur
                elseif ($reponserecruter != null) {
                    $reponserecruterid =  $reponserecruter->getId();
                    $expediteur = $reponseRepo->findBy(['recruterExpediteur' => $reponserecruterid]);
                }
            }

            $key_password = "lacléquipermetdecrypteretdedecrypter";

            // je modifie mon tableau de messages pour afficher les données décryptées 
            foreach ($messages as $message) {
                // DECRYPTER
                $decrypted_titre = openssl_decrypt(
                    $message->getTitre(),
                    "AES-128-ECB",
                    $key_password
                );

                $message->setTitre($decrypted_titre);

                $decrypted_message = openssl_decrypt(
                    $message->getMessage(),
                    "AES-128-ECB",
                    $key_password
                );

                $message->setMessage($decrypted_message);
            }

            // je modifie mon tableau des reponses pour afficher les données décryptées 
            foreach ($reponses as $reponse) {
                // DECRYPTER

                $decrypted_reponse = openssl_decrypt(
                    $reponse->getMessage(),
                    "AES-128-ECB",
                    $key_password
                );

                $reponse->setMessage($decrypted_reponse);
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

        $dest = $user->getSlug();

        $exp = $this->getUser();


        $userDest = $userRepo->findBy(['slug' => $dest]); // je recupere le destinataire user
        $recrutDest = $recrutRepo->findBy(['slug' => $dest]); // je recupere le destinataire recruter


        // récupération de l'id du destinataire pour la comparer a l'id de l'expediteur
        $destId = $user->getId(); // me donne l'id du destinataire

        $expId = $this->getUser()->getId();

        $expRole = $exp->getRoles();

        $destRole = $user->getRoles();

        if ($expId != $destId || $expRole[0] != $destRole[0]) {

            $role = $exp->getRoles();

            $roleDestiUser = $userDest[0]->getRoles();

            //  dump($roleDestiUser[0]); // me renvoit le role de l'user destinataire

            $message = new Message();

            $slug = $user->getSlug();

            $form = $this->createForm(MessageType::class, $message);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {


                // la je vérifie qui envoie le message , user ou recruter ?
                if ($role[0] == "ROLES_RECRUTER") {
                    $message->setRecruterExpediteur($exp); // si l'expediteur est un recruteur
                } else {
                    $message->setUserExpediteur($exp); // si l'expediteur est un user
                }

                if ($roleDestiUser[0] == "ROLE_USER") {
                    $message->setUserDestinataire($userDest[0]); // si le destinataire est un user

                } else {
                    $message->setRecruterDestinataire($recrutDest); // si le destinataire est un recruteur
                }

                $message->setPostedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($message);


                $objet = $message->getTitre();
                $texte = $message->getMessage();
                $key_password = "lacléquipermetdecrypteretdedecrypter";

                // CRYPTER
                $encrypted_titre = openssl_encrypt($objet, "AES-128-ECB", $key_password);
                $encrypted_message = openssl_encrypt($texte, "AES-128-ECB", $key_password);
                // dd($encrypted_titre, $encrypted_message);



                $message->setTitre($encrypted_titre);
                $message->setMessage($encrypted_message);
                //  dd($message);
                $entityManager->flush();
                $this->addFlash('success', 'Votre message a bien été envoyé !');
                return $this->redirectToRoute('member_space');
            }

            return $this->render('message/new.html.twig', [
                'user' => $user,
                'slug' => $slug,
                'message' =>  $message,
                'form' => $form->createView(),
            ]);
        } else {
            $this->addFlash('success', 'Vous ne pouvez pas vous auto-envoyer de message !');
            return $this->redirectToRoute('member_space');
        }
    }


    /**
     * @Route("/reponse/{id}", name="message_reponse", methods={"GET","POST"})
     */
    public function reponse($id, Request $request, UserRepository $destiUser, RecruterRepository $destiRecruter, MessageRepository $messageRepo): Response
    {

        $dest = intval($id); // me revoit l'id en int et pas en string

        $messageid = $messageRepo->findBy(['id' => $dest]);

        $trucuser = $messageid[0]->getUserExpediteur();
        $trucrecruter = $messageid[0]->getRecruterExpediteur();

        $exp = $this->getUser();

        $reponse = new Reponse();

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


            $texte = $reponse->getMessage();
            $key_password = "lacléquipermetdecrypteretdedecrypter";

            // CRYPTER
            $encrypted_message = openssl_encrypt($texte, "AES-128-ECB", $key_password);

            $reponse->setMessage($encrypted_message);

            $entityManager->flush();
            $this->addFlash('success', 'Votre message a bien été envoyé !');
            return $this->redirectToRoute('member_space');
        }

        return $this->render('message/newReponse.html.twig', [
            'message' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/conversation/{id}", name="reponse_reponse", methods={"GET","POST"})
     */
    public function reponseReponse($id, Request $request, UserRepository $destiUser, RecruterRepository $destiRecruter, ReponseRepository $reponseRepo): Response
    {
        $expediteur = $reponseRepo->findBy(['id' => $id]);
        $expediteuraquirepondre = $expediteur[0]->getExpediteur(); // me donne l'id du gars a qui je dois repondre

        //  $nomdugaraquirepondre = ucfirst($expediteur[0]->getExpediteur()->getFirstName()) . " " . ucfirst($expediteur[0]->getExpediteur()->getLastName());


        $exp = $this->getUser();
        $exprecruter = $exp->getRoles();

        $reponse = new Reponse();

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($exprecruter[0] == "ROLES_RECRUTER") {
                $reponse->setExpediteur_recruter($exp);
            } elseif ($exprecruter[0] == "ROLE_USER") {
                $reponse->setExpediteur($exp);
            }

            $reponse->setDestinataire($expediteuraquirepondre);
            $reponse->setPostedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reponse);

            $texte = $reponse->getMessage();
            $key_password = "lacléquipermetdecrypteretdedecrypter";

            // CRYPTER
            $encrypted_message = openssl_encrypt($texte, "AES-128-ECB", $key_password);

            $reponse->setMessage($encrypted_message);

            $entityManager->flush();
            $this->addFlash('success', 'Votre message a bien été envoyé !');
            return $this->redirectToRoute('member_space');
        }

        return $this->render('message/newReponse.html.twig', [
            // 'name' => $nomdugaraquirepondre,
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
