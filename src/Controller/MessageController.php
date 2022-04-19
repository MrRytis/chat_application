<?php

namespace App\Controller;

use App\DTO\MessageDTO;
use App\DTO\StatusDTO;
use App\Entity\Chat;
use App\Entity\ChatUser;
use App\Entity\Message;
use App\Request\MessageRequest;
use App\Service\ChatGuardService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api", name="api_")
 */
class MessageController extends AbstractFOSRestController
{
    private EntityManagerInterface $entityManager;
    private ChatGuardService $guardService;
    private NotificationService $notificationService;

    public function __construct(EntityManagerInterface $entityManager, ChatGuardService $guardService, NotificationService $notificationService)
    {
        $this->entityManager = $entityManager;
        $this->guardService = $guardService;
        $this->notificationService = $notificationService;
    }

    /**
     * @Rest\Get("/chat/{chatId}/messages", defaults={"_format": "json"})
     *
     * @param Request $request
     * @param int $chatId
     * @return View
     * @throws \App\Exception\ChatException
     */
    public function getChatMessagesAction(Request $request, int $chatId): View
    {
        $this->guardService->checkChatAccess($chatId, $this->getUser()->getId());

        $limit = $request->query->get('limit', 25);
        $page = $request->query->get('page', 1);

        $messages = $this->entityManager->getRepository(Message::class)
            ->getAllChatMessage($chatId, $limit, $page);

        $messageDTO = [];
        foreach ($messages as $message) {
            $messageDTO[] = (new MessageDTO())
                ->setId($message->getId())
                ->setText($message->getText())
                ->setCreated($message->getCreated())
                ->setChatUser($message->getChatUser()->getId());
        }

        return $this->view($messageDTO);
    }

    /**
     * @Rest\Post("/chat/{chatId}/message", defaults={"_format": "json"})
     * @ParamConverter("messageRequest", converter="fos_rest.request_body")
     *
     * @param int $chatId
     * @param MessageRequest $messageRequest
     * @return View
     * @throws \App\Exception\ChatException
     */
    public function createMessageAction(int $chatId, MessageRequest $messageRequest): View
    {
        $this->guardService->checkChatAccess($chatId, $this->getUser()->getId());

        $statusDTO = new StatusDTO();
        try {
            $chatUser = $this->entityManager->getRepository(ChatUser::class)->getChatUser($chatId, $this->getUser()->getId());
            if (null === $chatUser) {
                $statusDTO
                    ->setCode(500)
                    ->setMessage('chat user not found');

                return $this->view($statusDTO, 500);
            }

            $message = (new Message())
                ->setText($messageRequest->getText())
                ->setChat($this->entityManager->getReference(Chat::class, $chatId))
                ->setChatUser($chatUser);

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $this->notificationService->createNotification($chatId, $message, $chatUser);

            $statusDTO
                ->setCode(200)
                ->setMessage('Message created');
        } catch (\Throwable $exception) {
            $statusDTO
                ->setCode(500)
                ->setMessage('Failed to create message');
        }

        return $this->view($statusDTO, $statusDTO->getCode());
    }

    /**
     * @Rest\Put("/chat/{chatId}/message/{messageId}", defaults={"_format": "json"})
     * @ParamConverter("messageRequest", converter="fos_rest.request_body")
     *
     * @param int $chatId
     * @param int $messageId
     * @param MessageRequest $messageRequest
     * @return View
     * @throws \App\Exception\ChatException
     */
    public function editMessageAction(int $chatId, int $messageId, MessageRequest $messageRequest): View
    {
        $this->guardService->checkChatAccess($chatId, $this->getUser()->getId());

        //TODO: should be able to edit message only messages user

        $statusDTO = new StatusDTO();
        try {
            $message = $this->entityManager->getRepository(Message::class)->find($messageId);

            if (null === $message) {
                $statusDTO
                    ->setCode(404)
                    ->setMessage('Message not found');

                return $this->view($statusDTO);
            }

            $message->setText($messageRequest->getText());
            $this->entityManager->flush();

            $statusDTO
                ->setCode(200)
                ->setMessage('Message updated');
        } catch (\Throwable $exception) {
            $statusDTO
                ->setCode(500)
                ->setMessage('Failed to update message');
        }

        return $this->view($statusDTO, $statusDTO->getCode());
    }

    /**
     * @Rest\Delete("/chat/{chatId}/message/{messageId}", defaults={"_format": "json"})
     *
     * @param int $chatId
     * @param int $messageId
     * @return View
     * @throws \App\Exception\ChatException
     */
    public function deleteMessageAction(int $chatId, int $messageId): View
    {
        $this->guardService->checkChatAccess($chatId, $this->getUser()->getId());

        $statusDTO = new StatusDTO();
        try {
            $message = $this->entityManager->getRepository(Message::class)->find($messageId);

            if (null === $message) {
                $statusDTO
                    ->setCode(404)
                    ->setMessage('Message not found');

                return $this->view($statusDTO);
            }

            $this->entityManager->remove($message);
            $this->entityManager->flush();

            $statusDTO
                ->setCode(200)
                ->setMessage('Message deleted');
        } catch (\Throwable $exception) {
            $statusDTO
                ->setCode(500)
                ->setMessage('Failed to delete message');
        }

        return $this->view($statusDTO, $statusDTO->getCode());
    }
}
