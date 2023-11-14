<?php

declare (strict_types=1);

use Carbon\Carbon;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use App\Domain\Entity\UserEntity;
use App\Domain\Trait\HasUuidTrait;
use App\Domain\Trait\HasCreatedUpdatedTrait;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: ListRepository::class)]
#[Table(name: 'task_lists')]
class ListEntity
{
    use HasUuidTrait, HasCreatedUpdatedTrait;

    #[Column(type: 'string', updatable: false)]
    private string $title;

    #[ManyToOne(targetEntity: UserEntity::class, cascade: ['PERSIST', 'MERGE'])]
    private UserEntity $owner;

    #[Column(type: 'array', updatable: true)]
    private array $tasks;


    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of owner
     */ 
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @return  self
     */ 
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the value of tasks
     */ 
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set the value of tasks
     *
     * @return  self
     */ 
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;

        return $this;
    }
}