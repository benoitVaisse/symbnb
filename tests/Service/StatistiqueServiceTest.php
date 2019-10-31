<?php

namespace App\test\Service;
use PHPUnit\Framework\TestCase;
use App\Service\StatistiqueService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class StatistiqueServiceTest extends KernelTestCase
{

    private $entityManager;
    public function testAdd()
    {
        $manager = $this->createMock(ObjectManager::class);
        $calculator = new StatistiqueService($manager);
        $result = $calculator->calculate(3, 4);

        // assert that your calculator added the numbers correctly!
        $this->assertEquals(12, $result);
    }

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testgetAdStat(){
        
        // $manager = $this->createMock(ObjectManager::class);
        $calculator = new StatistiqueService( $this->entityManager);
        
        $result = $calculator->getAdStat();
        $this->assertEquals(36, $result);

    }
}
?>