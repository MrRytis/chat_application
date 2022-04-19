<?php

namespace App\Controller;

use App\DTO\NotificationDTO;
use App\DTO\StatusDTO;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api", name="api_")
 */
class NotificationController extends AbstractFOSRestController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Rest\Get("/notifications", defaults={"_format": "json"})
     *
     * @param Request $request
     * @return View
     */
    public function getUserNotificationAction(Request $request): View
    {
        $notifications = $this->entityManager->getRepository(Notification::class)
            ->getAllUsersNotifications($this->getUser()->getId(), $request->query->get('limit', 20));

        $notificationsDTO = [];
        foreach ($notifications as $notification) {
            $notificationsDTO[] = (new NotificationDTO())
                ->setId($notification->getId())
                ->setUsername($notification->getChatUser()->getUsername())
                ->setText($notification->getMessage()->getText())
                ->setCreated($notification->getCreated())
                ->setIsRead($notification->isRead());
        }

        return $this->view($notificationsDTO);
    }

    /**
     * @Rest\Post("/notifications/read", defaults={"_format": "json"})
     *
     * @return View
     */
    public function markNotificationAsReadAction(): View
    {
        $notifications = $this->entityManager->getRepository(Notification::class)
            ->getAllUsersUnreadNotifications($this->getUser()->getId());

        foreach ($notifications as $notification) {
            $notification->setRead(true);
        }

        $this->entityManager->flush();

        $statusDTO = (new StatusDTO())
            ->setCode(200)
            ->setMessage('Messages updated');

        return $this->view($statusDTO);
    }
}
