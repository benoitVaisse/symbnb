<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
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
            
            $nbBooking = mt_rand(0,10);
            for($j = 0; $j < $nbBooking; $j++)
            {
                $booking = new Booking();
                $date = new \DateTime();

                $createdAt = $date->modify("-3 months");
                $startDate = $date->modify("-6 months");

                // la date de fin 
                $days = mt_rand(1,7);
                $endDate = (clone $startDate)->modify("+".$days." days");

                $user = $users[mt_rand(0, count($users)-1)];

                $comment = $faker->paragraph();
                $amount = $ad->getPrice() * $days;

                $booking->setCreatedAt($createdAt)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setUser($user)
                        ->setAd($ad)
                        ->setComment($comment)
                        ->setAmount($amount);

                $manager->persist($booking);

                if(mt_rand(0,1)){
                    
                    $comment = new Comment();
                    $comment->setUser($user)
                            ->setAd($ad)
                            ->setContent($faker->paragraph())
                            ->setRating(mt_rand(0,5));
    
                    $manager->persist($comment);
                }

            }

            $manager->persist($ad);
        }

        $manager->flush();
    }

    public function getDepartements()
    {
        return [
                [
                "nom"=> "Ain",
                "code"=> "01",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Aisne",
                "code"=> "02",
                "codeRegion"=> "32"
                ],
                [
                "nom"=> "Allier",
                "code"=> "03",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Alpes-de-Haute-Provence",
                "code"=> "04",
                "codeRegion"=> "93"
                ],
                [
                "nom"=> "Hautes-Alpes",
                "code"=> "05",
                "codeRegion"=> "93"
                ],
                [
                "nom"=> "Alpes-Maritimes",
                "code"=> "06",
                "codeRegion"=> "93"
                ],
                [
                "nom"=> "Ardèche",
                "code"=> "07",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Ardennes",
                "code"=> "08",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Ariège",
                "code"=> "09",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Aube",
                "code"=> "10",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Aude",
                "code"=> "11",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Aveyron",
                "code"=> "12",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Bouches-du-Rhône",
                "code"=> "13",
                "codeRegion"=> "93"
                ],
                [
                "nom"=> "Calvados",
                "code"=> "14",
                "codeRegion"=> "28"
                ],
                [
                "nom"=> "Cantal",
                "code"=> "15",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Charente",
                "code"=> "16",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Charente-Maritime",
                "code"=> "17",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Cher",
                "code"=> "18",
                "codeRegion"=> "24"
                ],
                [
                "nom"=> "Corrèze",
                "code"=> "19",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Côte-d'Or",
                "code"=> "21",
                "codeRegion"=> "27"
                ],
                [
                "nom"=> "Côtes-d'Armor",
                "code"=> "22",
                "codeRegion"=> "53"
                ],
                [
                "nom"=> "Creuse",
                "code"=> "23",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Dordogne",
                "code"=> "24",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Doubs",
                "code"=> "25",
                "codeRegion"=> "27"
                ],
                [
                "nom"=> "Drôme",
                "code"=> "26",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Eure",
                "code"=> "27",
                "codeRegion"=> "28"
                ],
                [
                "nom"=> "Eure-et-Loir",
                "code"=> "28",
                "codeRegion"=> "24"
                ],
                [
                "nom"=> "Finistère",
                "code"=> "29",
                "codeRegion"=> "53"
                ],
                [
                "nom"=> "Corse-du-Sud",
                "code"=> "2A",
                "codeRegion"=> "94"
                ],
                [
                "nom"=> "Haute-Corse",
                "code"=> "2B",
                "codeRegion"=> "94"
                ],
                [
                "nom"=> "Gard",
                "code"=> "30",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Haute-Garonne",
                "code"=> "31",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Gers",
                "code"=> "32",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Gironde",
                "code"=> "33",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Hérault",
                "code"=> "34",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Ille-et-Vilaine",
                "code"=> "35",
                "codeRegion"=> "53"
                ],
                [
                "nom"=> "Indre",
                "code"=> "36",
                "codeRegion"=> "24"
                ],
                [
                "nom"=> "Indre-et-Loire",
                "code"=> "37",
                "codeRegion"=> "24"
                ],
                [
                "nom"=> "Isère",
                "code"=> "38",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Jura",
                "code"=> "39",
                "codeRegion"=> "27"
                ],
                [
                "nom"=> "Landes",
                "code"=> "40",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Loir-et-Cher",
                "code"=> "41",
                "codeRegion"=> "24"
                ],
                [
                "nom"=> "Loire",
                "code"=> "42",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Haute-Loire",
                "code"=> "43",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Loire-Atlantique",
                "code"=> "44",
                "codeRegion"=> "52"
                ],
                [
                "nom"=> "Loiret",
                "code"=> "45",
                "codeRegion"=> "24"
                ],
                [
                "nom"=> "Lot",
                "code"=> "46",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Lot-et-Garonne",
                "code"=> "47",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Lozère",
                "code"=> "48",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Maine-et-Loire",
                "code"=> "49",
                "codeRegion"=> "52"
                ],
                [
                "nom"=> "Manche",
                "code"=> "50",
                "codeRegion"=> "28"
                ],
                [
                "nom"=> "Marne",
                "code"=> "51",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Haute-Marne",
                "code"=> "52",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Mayenne",
                "code"=> "53",
                "codeRegion"=> "52"
                ],
                [
                "nom"=> "Meurthe-et-Moselle",
                "code"=> "54",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Meuse",
                "code"=> "55",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Morbihan",
                "code"=> "56",
                "codeRegion"=> "53"
                ],
                [
                "nom"=> "Moselle",
                "code"=> "57",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Nièvre",
                "code"=> "58",
                "codeRegion"=> "27"
                ],
                [
                "nom"=> "Nord",
                "code"=> "59",
                "codeRegion"=> "32"
                ],
                [
                "nom"=> "Oise",
                "code"=> "60",
                "codeRegion"=> "32"
                ],
                [
                "nom"=> "Orne",
                "code"=> "61",
                "codeRegion"=> "28"
                ],
                [
                "nom"=> "Pas-de-Calais",
                "code"=> "62",
                "codeRegion"=> "32"
                ],
                [
                "nom"=> "Puy-de-Dôme",
                "code"=> "63",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Pyrénées-Atlantiques",
                "code"=> "64",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Hautes-Pyrénées",
                "code"=> "65",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Pyrénées-Orientales",
                "code"=> "66",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Bas-Rhin",
                "code"=> "67",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Haut-Rhin",
                "code"=> "68",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Rhône",
                "code"=> "69",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Haute-Saône",
                "code"=> "70",
                "codeRegion"=> "27"
                ],
                [
                "nom"=> "Saône-et-Loire",
                "code"=> "71",
                "codeRegion"=> "27"
                ],
                [
                "nom"=> "Sarthe",
                "code"=> "72",
                "codeRegion"=> "52"
                ],
                [
                "nom"=> "Savoie",
                "code"=> "73",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Haute-Savoie",
                "code"=> "74",
                "codeRegion"=> "84"
                ],
                [
                "nom"=> "Paris",
                "code"=> "75",
                "codeRegion"=> "11"
                ],
                [
                "nom"=> "Seine-Maritime",
                "code"=> "76",
                "codeRegion"=> "28"
                ],
                [
                "nom"=> "Seine-et-Marne",
                "code"=> "77",
                "codeRegion"=> "11"
                ],
                [
                "nom"=> "Yvelines",
                "code"=> "78",
                "codeRegion"=> "11"
                ],
                [
                "nom"=> "Deux-Sèvres",
                "code"=> "79",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Somme",
                "code"=> "80",
                "codeRegion"=> "32"
                ],
                [
                "nom"=> "Tarn",
                "code"=> "81",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Tarn-et-Garonne",
                "code"=> "82",
                "codeRegion"=> "76"
                ],
                [
                "nom"=> "Var",
                "code"=> "83",
                "codeRegion"=> "93"
                ],
                [
                "nom"=> "Vaucluse",
                "code"=> "84",
                "codeRegion"=> "93"
                ],
                [
                "nom"=> "Vendée",
                "code"=> "85",
                "codeRegion"=> "52"
                ],
                [
                "nom"=> "Vienne",
                "code"=> "86",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Haute-Vienne",
                "code"=> "87",
                "codeRegion"=> "75"
                ],
                [
                "nom"=> "Vosges",
                "code"=> "88",
                "codeRegion"=> "44"
                ],
                [
                "nom"=> "Yonne",
                "code"=> "89",
                "codeRegion"=> "27"
                ],
                [
                "nom"=> "Territoire de Belfort",
                "code"=> "90",
                "codeRegion"=> "27"
                ],
                [
                "nom"=> "Essonne",
                "code"=> "91",
                "codeRegion"=> "11"
                ],
                [
                "nom"=> "Hauts-de-Seine",
                "code"=> "92",
                "codeRegion"=> "11"
                ],
                [
                "nom"=> "Seine-Saint-Denis",
                "code"=> "93",
                "codeRegion"=> "11"
                ],
                [
                "nom"=> "Val-de-Marne",
                "code"=> "94",
                "codeRegion"=> "11"
                ],
                [
                "nom"=> "Val-d'Oise",
                "code"=> "95",
                "codeRegion"=> "11"
                ],
                [
                "nom"=> "Guadeloupe",
                "code"=> "971",
                "codeRegion"=> "01"
                ],
                [
                "nom"=> "Martinique",
                "code"=> "972",
                "codeRegion"=> "02"
                ],
                [
                "nom"=> "Guyane",
                "code"=> "973",
                "codeRegion"=> "03"
                ],
                [
                "nom"=> "La Réunion",
                "code"=> "974",
                "codeRegion"=> "04"
                ],
                [
                "nom"=> "Mayotte",
                "code"=> "976",
                "codeRegion"=> "06"
                ]
            ];
    }

    public function getRegions()
    {
        return [
            [
              "nom"=> "Guadeloupe",
              "code"=> "01"
            ],
            [
              "nom"=> "Martinique",
              "code"=> "02"
            ],
            [
              "nom"=> "Guyane",
              "code"=> "03"
            ],
            [
              "nom"=> "La Réunion",
              "code"=> "04"
            ],
            [
              "nom"=> "Mayotte",
              "code"=> "06"
            ],
            [
              "nom"=> "Île-de-France",
              "code"=> "11"
            ],
            [
              "nom"=> "Centre-Val de Loire",
              "code"=> "24"
            ],
            [
              "nom"=> "Bourgogne-Franche-Comté",
              "code"=> "27"
            ],
            [
              "nom"=> "Normandie",
              "code"=> "28"
            ],
            [
              "nom"=> "Hauts-de-France",
              "code"=> "32"
            ],
            [
              "nom"=> "Grand Est",
              "code"=> "44"
            ],
            [
              "nom"=> "Pays de la Loire",
              "code"=> "52"
            ],
            [
              "nom"=> "Bretagne",
              "code"=> "53"
            ],
            [
              "nom"=> "Nouvelle-Aquitaine",
              "code"=> "75"
            ],
            [
              "nom"=> "Occitanie",
              "code"=> "76"
            ],
            [
              "nom"=> "Auvergne-Rhône-Alpes",
              "code"=> "84"
            ],
            [
              "nom"=> "Provence-Alpes-Côte d'Azur",
              "code"=> "93"
            ],
            [
              "nom"=> "Corse",
              "code"=> "94"
            ]
            ];
    }
}
