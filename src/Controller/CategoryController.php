<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="category_index")
     */
    public function index($slug, CategoryRepository $catRepo): Response
    {
        /* pour paginer 
        return $this->render('gallery/category.html.twig',[ 
            'artisticwork' => $artisticrepo->findBySlug('slug'),
            
             'galleries' => $paginator->paginate(
              $galleryRepository->findBy(['category' => $category]),
              $request->query->getInt('page' , 1 ),
              4),
              'category' =>$category,
             
            ]);


*/
        $category = $catRepo->findOneBy([
            'slug' => $slug,
        ]);


        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        return $this->render('category/category.html.twig', [
            'slug' => $slug,
            'category' => $category

        ]);
    }


    /**
     * @Route("/category/user/{slug}", name="category_showUser")
     */
    public function showUser(UserRepository $userRepo, $slug, CategoryRepository $catRepo)
    {

        $category = $catRepo->findOneBy([
            'slug' => $slug,
        ]);
        $user = $userRepo->findBy(['slug' => $slug]);

        return $this->render('category/categoryUser.html.twig', [
            'slug' => $slug,
            'user' => $user,
            'category' => $category
        ]);
    }
}
