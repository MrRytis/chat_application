<?php

namespace App\Controller;

use App\DTO\StatusDTO;
use App\Entity\User;
use App\Request\RegisterRequest;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Rest\Route("/api", name="api_")
 */
class RegisterController extends AbstractFOSRestController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @Rest\Post("/register", defaults={"_format": "json"})
     * @ParamConverter("registerRequest", converter="fos_rest.request_body")
     *
     * @param RegisterRequest $registerRequest
     * @return View
     */
    public function registerUserAction(RegisterRequest $registerRequest): View
    {
        $statusDTO = new StatusDTO();
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['username' => $registerRequest->getUsername()]);

        if ($user instanceof User) {
            $statusDTO
                ->setCode(400)
                ->setMessage('Username already used');

            return $this->view($statusDTO, 400);
        }

        $newUser = new User();
        $newUser
            ->setUsername($registerRequest->getUsername())
            ->setPassword($this->passwordHasher->hashPassword($newUser, $registerRequest->getPassword()));

        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        $statusDTO = (new StatusDTO())
            ->setCode(200)
            ->setMessage('User created');

        return $this->view($statusDTO, 200);
    }
}
