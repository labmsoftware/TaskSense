<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\XferObject\ListObject;
use Carbon\Carbon;
use Doctrine\ORM\EntityRepository;
use ListEntity;

class ListRepository extends EntityRepository
{
    public function new(ListObject $data) : ListEntity
    {
        $dt = new Carbon('now');

        $list = (new ListEntity())
        ->setTitle($data->title)
        ->setOwner($data->owner)
        ->setTasks($data->tasks);

        return $list;
    }

    public function save(ListEntity $list) : void 
    {
        $this->_em->persist($list)   ;
        
        $this->_em->flush();
    }

    public function get(array $criteria) : ?ListEntity
    {
        return $this->findAllBy($criteria);
    }

    public function delete(ListEntity $list) : void
    {
        $this->_em->remove($list);
        
        $this->_em->flush();
    }
}