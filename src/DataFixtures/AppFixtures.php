<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Category;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();
        $categories = [];
        $users = [];


        $cat = ['Javascript', 'PHP', 'Python', 'Ruby', 'Css', 'HTML', 'Jquery', 'React', 'Symfony', 'Laravel'];
        foreach ($cat as $name) {
            $category = new Category();
            $category->setName($name);
            $slug = $slugify->slugify($category->getName());
            $category->setSlug(strtolower($name));
            $manager->persist($category);
            $categories[] = $category;
        }

        for ($u = 1; $u <= 15; $u++) {

            $user = new User();
            $user->setFirstName($faker->firstNameMale());
            $user->setLastName($faker->lastName());
            $user->setEmail("email+" . $u . "@email.com");
            $slug = $slugify->slugify($user->getFirstName() . ' ' . $user->getLastName());
            $user->setSlug($slug);
            $user->setLocation($faker->city());
            $user->setPassword("password");
            $user->setRoles(["ROLE_USER"]);


            foreach ($categories as $category) {
                $user->getCategories($categories)->add($category);
            }
            $users[] = $user;
            $manager->persist($user);
        }



        $manager->flush();
    }
}
