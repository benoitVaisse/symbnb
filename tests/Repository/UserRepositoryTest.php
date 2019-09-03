<?php

namespace App\tests\Repository;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;




class UserRepositoryTest extends KernelTestCase
{

    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCountBestUser(){

        $findBestUser = $this->entityManager
            ->getRepository(User::class)
            ->findBestUsers(2);

        $this->assertCount(2, $findBestUser);
        

    }
}


?>