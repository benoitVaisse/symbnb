<?php 

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class PaginationService {

    private $currentPage = 1;
    private $limite = 10;
    private $entityClass ;
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit ;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setPage($page)
    {
        $this->currentPage = $page;

        return $this;
    }

    public function getPage()
    {
        return $this->currentPage;
    }


    public function getNumberPage()
    {
        $numberPage = ceil(count( $this->manager->getRepository($this->entityClass)->findAll()) / $this->limit );
        
        return $numberPage ;
    }
    
    
    public function getData()
    {
        $offset = ($this->currentPage * $this->limit) - $this->limit;

        $data = $this->manager->getRepository($this->entityClass)->findBy([], ["id"=>"ASC"], $this->limit, $offset);

        return $data;

    }



}




?>