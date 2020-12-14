<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="category_index")
     */
    public function index($slug, CategoryRepository $catRepo, PaginatorInterface $paginator, Request $request, Category $category): Response
    {
        /*
        $category = $catRepo->findOneBy([
            'slug' => $slug,
        ]);


        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        return $this->render('category/category.html.twig', [
            'slug' => $slug,
            'category' => $paginator->paginate(
                $catRepo->findVisibleQuery(),
                $request->query->getInt('page', 1),
                4
            )

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
            'category' => $paginator->paginate(
                $catRepo->findBy(['slug' => $slug]),
                $request->query->getInt('page', 1),
                4
            )

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
