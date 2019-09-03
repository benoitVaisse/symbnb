<?php 

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class StatistiqueService {


    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * permet de récuperer les nombre d'entrée des commentaires, utilisateurs, reservations et annonces et de le retourner sous formme de tableau
     *
     * @return array 
     */
    public function getStat()
    {
        $ads = $this->getAdStat();
        $users = $this->getUserStat();
        $bookings = $this->getBookingStat();
        $comments = $this->getCommentStat();

        return compact("ads","users","bookings","comments");
    }

    public function getAdStat()
    {
        return $this->manager->createQuery("SELECT count(a) FROM App\Entity\Ad as a")->getSingleScalarResult();
    }


    public function getUserStat()
    {
        return $this->manager->createQuery("SELECT count(u) FROM App\Entity\User as u")->getSingleScalarResult();
    }


    public function getBookingStat()
    {
        return $this->manager->createQuery("SELECT count(b) FROM App\Entity\Booking as b")->getSingleScalarResult();
    }


    public function getCommentStat()
    {
        return $this->manager->createQuery("SELECT count(c) FROM App\Entity\Comment as c")->getSingleScalarResult();
    }

    /**
     * permet de récupere les 5 meilleur ou les plus mauvaise annonces
     *
     * @param [string] $ordre  mettre "ASC" ou "DESC" pour récupere dans l'ordre voulu
     * @return array
     */
    public function getStatsAds($ordre)
    {

        return $this->manager->createQuery("SELECT AVG(c.rating) as rating, u.firstName, u.lastName, u.picture, a.title, a.slug
                                    FROM App\Entity\Comment c
                                    JOIN c.ad a
                                    JOIN a.user u
                                    GROUP BY a
                                    ORDER BY rating ".$ordre)->setMaxResults(5)->getResult();
    }


    public function calculate($a, $b){
        return $a*$b;
    }

}





?>