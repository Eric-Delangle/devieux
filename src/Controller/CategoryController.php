<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Media;
use App\Entity\Category;
use App\Repository\MediaRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    protected $media;
    protected $manager;


    public function __construct(MediaRepository $media, EntityManagerInterface $manager)
    {
        $this->media = $media;
        $this->manager = $manager;
    }


    /**
     * @Route("/category/{slug}", name="category_index")
     */
    public function index($slug, CategoryRepository $catRepo, PaginatorInterface $paginator, Request $request, Category $category): Response
    {

        $category = $catRepo->findOneBy([
            'slug' => $slug,
        ]);
        //  $user = $this->getUser();



        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        $liste = $category->getUsers();/* ce sont ces elements que je veux paginer */

        //recuperer l'id des users
        $userImage = $category->getUsers();

        /*
        for ($u = 0; $u < count($liste); $u++) {
            // pour recuperer l'avatar
            $media[$u] = $this->manager->getRepository(Media::class)->findOneBy(['user' => $liste[$u]]);
            dd($media[$u]);
            //dd($media[$u]);
        }

*/

        $avatar = $this->manager->getRepository(Media::class)->findAll();

        return $this->render('category/category.html.twig', [
            'slug' => $slug,
            'avatar' => $avatar,
            'category' => $paginator->paginate(
                $liste,/* ce sont ces elements que je veux paginer */

                $request->query->getInt('page', 1),
                4
            )
        ]);
    }


    /**
     * @Route("/category/user/{slug}", name="category_showUser")
     * Cette fonction me permet de créer un members.json pour géolocaliser le membre sur son profil
     */
    public function showUser($slug)
    {

        //je récupere le repository des users et je vais checher ses infos
        $repositoryCat = $this->getDoctrine()->getRepository(Category::class);
        $repositoryUser = $this->getDoctrine()->getRepository(User::class);


        $category = $repositoryCat->findOneBy([
            'slug' => $slug,
        ]);
        $user = $repositoryUser->findBy(['slug' => $slug]);

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $data = $serializer->serialize(
            $user,
            'json',
            ['attributes' => ['location', 'categories' => ['name']]]
        );

        json_encode($data);

        // Nom du fichier à créer
        $members = 'members.json';

        // Ouverture du fichier
        $members = fopen($members, 'w+');

        // Ecriture dans le fichier
        fwrite($members, $data);

        // Fermeture du fichier
        fclose($members);

        return $this->render('category/categoryUser.html.twig', [
            'slug' => $slug,
            'user' => $user,
            'category' => $category
        ]);
    }
}
