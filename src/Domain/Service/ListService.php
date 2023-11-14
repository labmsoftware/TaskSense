<?php
declare (strict_types=1);

namespace App\Domain\Service;

use ListEntity;
use App\Domain\Service\Service;
use Doctrine\ORM\EntityManager;
use App\Domain\Entity\UserEntity;
use Odan\Session\SessionInterface;
use App\Domain\Service\UserService;
use App\Domain\XferObject\ListObject;
use App\Domain\Repository\ListRepository;

final class ListService extends Service
{
    private ListRepository $list;
    private SessionInterface $session;
    private CryptographyService $crypto;
    private UserService $userService;

    public function __construct(EntityManager $em,
     SessionInterface $session, 
     CryptographyService $crypto,
     UserService $userService
     )
    {
        $this->list = $em->getRepository(ListEntity::class);
        $this->session = $session;
        $this->crypto = $crypto;
        $this->userService = $userService;
    }

    public function createList(ListObject $listObject)
    {
        $decodedData = $this->crypto->sessionDataDecoder([
            'zenrepair_user' => $this->session->get('zenrepair_user')
        ]);
        
        $user = $this->userService->getUser($decodedData['zenrepair_user']);

        $listObject['ownerId'] = $user;

        $newListToBeSaved = $this->list->new($listObject);

        $this->list->save($newListToBeSaved);

    }
}