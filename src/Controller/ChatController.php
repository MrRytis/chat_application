<?php

namespace App\Controller;

use App\Builder\ChatCreator;
use App\Builder\SimpleChatBuilder;
use App\DTO\ChatDTO;
use App\DTO\StatusDTO;
use App\Entity\Chat;
use App\Entity\ChatUser;
use App\Entity\User;
use App\Exception\ChatException;
use App\Request\ChatRequest;
use App\Service\ChatGuardService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api", name="api_")
 */
class ChatController extends AbstractFOSRestController
{
    private EntityManagerInterface $entityManager;
    private ChatGuardService $guardService;

    public function __construct(EntityManagerInterface $entityManager, ChatGuardService $guardService)
    {
        $this->entityManager = $entityManager;
        $this->guardService = $guardService;
    }

    /**
     * @Rest\Get("/chats", defaults={"_format": "json"})
     *
     * @param Request $request
     * @return View
     */
    public function getChatsAction(Request $request): View
    {
        $chats = $this->entityManager->getRepository(Chat::class)
            ->getAllUsersChats($this->getUser()->getId(), $request->query->get('limit', 20));

        $chatDTO = [];
        $userId = $this->getUser()->getId();
        foreach ($chats as $chat) {
            /** @var ChatUser $chatUser */
            $chatUser = $chat->getChatUsers()->filter(function (ChatUser $chatUser) use ($userId) {
                return $chatUser->getUser()->getId() === $userId ? $chatUser : null;
            })->first();

            $chatDTO[] = (new ChatDTO())
                ->setId($chat->getId())
                ->setTitle($chat->getTitle())
                ->setCreated($chat->getCreated())
                ->setLastMessage($chat->getMessages()->last()->getText())
                ->setNotificationCount($chatUser->getNotifications()->count());
        }

        return $this->view($chatDTO);
    }

    /**
     * @Rest\Post("/chat", defaults={"_format": "json"})
     * @ParamConverter("chatRequest", converter="fos_rest.request_body")
     *
     * @param ChatRequest $chatRequest
     * @return View
     */
    public function createChatAction(ChatRequest $chatRequest): View
    {
        $statusDTO = new StatusDTO();
        try {
            $user = $this->entityManager->getRepository(User::class)->find($chatRequest->getUserId());
            if (null === $user) {
                $statusDTO
                    ->setCode(500)
                    ->setMessage('user not found');

                return $this->view($statusDTO, 500);
            }

            $chatBuilder = new SimpleChatBuilder($this->entityManager);
            $chatCreator = new ChatCreator($chatBuilder);
            $chat = $chatCreator->createChat([$chatRequest->getUserId(), $this->getUser()->getId]);

            $this->entityManager->persist($chat);
            $this->entityManager->flush();

            $statusDTO
                ->setCode(200)
                ->setMessage('Chat created');
        } catch (ChatException $exception) {
            $statusDTO
                ->setCode($exception->getCode())
                ->setMessage($exception->getMessage());
        } catch (\Throwable $exception) {
            $statusDTO
                ->setCode(500)
                ->setMessage('Failed to create chat');
        }

        return $this->view($statusDTO, $statusDTO->getCode());
    }

    /**
     * @Rest\Put("/chat/{chatId}", defaults={"_format": "json"})
     * @ParamConverter("messageRequest", converter="fos_rest.request_body")
     *
     * @param int $chatId
     * @param ChatRequest $chatRequest
     * @return View
     * @throws ChatException
     */
    public function editChatAction(int $chatId, ChatRequest $chatRequest): View
    {
        $this->guardService->checkChatAccess($chatId, $this->getUser()->getId());

        $statusDTO = new StatusDTO();
        try {
            $chat = $this->entityManager->getRepository(Chat::class)->find($chatId);

            if (null === $chat) {
                $statusDTO
                    ->setCode(404)
                    ->setMessage('Chat not found');

                return $this->view($statusDTO);
            }

            $chat->setTitle($chatRequest->getTitle());
            $this->entityManager->flush();

            $statusDTO
                ->setCode(200)
                ->setMessage('Chat updated');
        } catch (\Throwable $exception) {
            $statusDTO
                ->setCode(500)
                ->setMessage('Failed to update chat');
        }

        return $this->view($statusDTO, $statusDTO->getCode());
    }

    /**
     * @Rest\Delete("/chat/{chatId}", defaults={"_format": "json"})
     *
     * @param int $chatId
     * @return View
     * @throws ChatException
     */
    public function deleteChatAction(int $chatId): View
    {
        $this->guardService->checkChatAccess($chatId, $this->getUser()->getId());

        $statusDTO = new StatusDTO();
        try {
            $chat = $this->entityManager->getRepository(Chat::class)->find($chatId);

            if (null === $chat) {
                $statusDTO
                    ->setCode(404)
                    ->setMessage('Chat not found');

                return $this->view($statusDTO);
            }

            $this->entityManager->remove($chat);
            $this->entityManager->flush();

            $statusDTO
                ->setCode(200)
                ->setMessage('Chat deleted');
        } catch (\Throwable $exception) {
            $statusDTO
                ->setCode(500)
                ->setMessage('Failed to delete chat');
        }

        return $this->view($statusDTO, $statusDTO->getCode());
    }
}
