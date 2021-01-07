<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Media;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MemberController extends AbstractController
{

    protected $users;
    protected $manager;

    public function __construct(UserRepository $users, EntityManagerInterface $manager)
    {
        $this->users = $users;
        $this->manager = $manager;
    }

    /**
     * Liste de tous les membres
     * @Route("/member", name="member_index")
     */
    public function index()
    {

        $users =  $this->manager->getRepository(User::class)->findAll();

        return $this->render('member/users.html.twig', [
            'users' => $users
        ]);
    }


    /**
     * Affiche un espace membre
     * @Route("/member/space", name="member_space")
     */
    public function space()
    {

        $user = $this->getUser();

        $media = $this->manager->getRepository(Media::class)->findBy(['user' => $user]);

        return $this->render('member/space.html.twig', [
            'media' => $media

        ]);
    }
}
