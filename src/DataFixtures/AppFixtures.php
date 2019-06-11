<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        // creation du role Admin
        $role = new Role();
        $role->setTitle("ROLE_ADMIN");
        $manager->persist($role);
        
        $role2 = new Role();
        $role2->setTitle("ROLE_TEST");
        $manager->persist($role2);


        $adminUser = new User();
        $adminUser->setFirstName("Benoit")
                  ->setLastName("Vaisse")
                  ->setPicture("https://static.myfigurecollection.net/pics/figure/850034.jpg?v=1559650817")
                  ->setEmail("benoit.vaisse@symfony.com")
                  ->setHash($this->encoder->encodePassword($adminUser, "password"))
                  ->setIntroduction($faker->sentence())
                  ->setDescription("<p>".join("</p><p>",$faker->paragraphs(3))."</p>")
                  ->addUserRole($role)
                  ->addUserRole($role2);

        $manager->persist($adminUser);

        // nous creons les utilisateurs

        $genres = ["male","female"];
        $users = [];
        for($i=1; $i<=10; $i++)
        {
            $user = new User();
            $content = "<p>".join("</p><p>",$faker->paragraphs(3))."</p>";

            $genre = $genres[mt_rand(0,1)];

            $picture = "https://randomuser.me/api/portraits/";
            $picture = $picture . ($genre == "male" ? "men/" : "women/" ). mt_rand(1,99) . ".jpg";
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname($genre))
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription($content)
                 ->setHash($hash)
                 ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // nous creons les annonces
        for($i=1; $i<=30;$i++)
        {

            $ad = new Ad();
            $title = $faker->sentence(6);
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = "<p>".join("</p><p>",$faker->paragraphs(5))."</p>";

            // je prend un utlisateur au hasard de la bdd
            $user = $users[mt_rand(0,count($users) - 1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,6))
                ->setUser($user);
    
    
                
            $nbImage = mt_rand(3,5);

            // nous creons les images
            for($j = 1; $j<=$nbImage ; $j++)
            {
                $image  = new Image();
                $image->setUrl($faker->imageUrl())
                ->setCaption($faker->sentence())
                ->setAd($ad);
                
                $manager->persist($image);
            }
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
