<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api", name="api_")
 */
class UserController extends AbstractFOSRestController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Rest\Get("/users", defaults={"_format": "json"})
     *
     * @param Request $request
     * @return View
     */
    public function registerUserAction(Request $request): View
    {
        $users = $this->entityManager->getRepository(User::class)
            ->getAllUsers($request->query->get('limit', 20));

        $usersDTO = [];
        foreach ($users as $user) {
            $usersDTO = (new UserDTO())
                ->setId($user->getId())
                ->setTitle($user->getUserIdentifier());
        }

        return $this->view($usersDTO, 200);
    }
}
