<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Category;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));
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

            $hash = $this->encoder->encodePassword($user, "password");

            $user->setFirstName($faker->firstNameMale());
            $user->setLastName($faker->lastName());
            $user->setEmail("email+" . $u . "@email.com");
            $slug = $slugify->slugify($user->getFirstName() . ' ' . $user->getLastName());
            $user->setSlug($slug);
            $user->setLocation($faker->city());
            $user->setPassword($hash);
            $user->setRoles(["ROLE_USER"]);
            $user->setDescription($faker->paragraph());
            $user->setExperience(mt_rand(1, 5));
            $user->setMainAvatar('avatarDefaut.jpg'); // la je donne le nom de l'avatar
            $user->setAvatarFile(new File('public/images/avatarDefaut.jpg')); // la son fichier
            $user->setRegisteredAt($faker->dateTimeBetween($startDate = '-6 months', $endDate = 'now'));


            foreach ($categories as $category) {
                $user->getCategories($categories)->add($category);
            }
            $users[] = $user;
            $manager->persist($user);
        }



        $manager->flush();
    }
}
