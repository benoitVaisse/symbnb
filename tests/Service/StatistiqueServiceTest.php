<?php

namespace App\test\Service;
use PHPUnit\Framework\TestCase;
use App\Service\StatistiqueService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;


class StatistiqueServiceTest extends TestCase
{

    public function testAdd()
    {
        $manager = $this->createMock(ObjectManager::class);
        $calculator = new StatistiqueService($manager);
        $result = $calculator->calculate(3, 4);

        // assert that your calculator added the numbers correctly!
        $this->assertEquals(12, $result);
    }
}
?>