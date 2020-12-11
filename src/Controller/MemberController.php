<?php

namespace App\Controller;

use App\Entity\User;
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

        // $user = $this->manager->getRepository(User::class)->findOneBy(['email' => $user]);

        return $this->render('member/space.html.twig', [
            // 'user' => $user
        ]);
    }

    /**
     * Affiche un membre en particulier
     * @Route("/member/{slug}", name="member_show")
     */
    public function show($slug)
    {

        $user =  $this->manager->getRepository(User::class)->findOneBy(['slug' => $slug]);

        return $this->render('member/user.html.twig', [
            'user' => $user,
            'slug' => $slug
        ]);
    }
}
