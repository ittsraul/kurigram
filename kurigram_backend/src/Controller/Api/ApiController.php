<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Posts;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api',name: 'api')]
class ApiController extends AbstractController
{
    #[Route('/users/{id}', name: 'getUser_api', methods: ["GET"])]
    public function getUsers(ManagerRegistry $doctrine, $id): JsonResponse
    {
        $getUser = $doctrine->getRepository(User::class)->find($id);

        $data = [
            "name" => $getUser->getName(),
            "Email" => $getUser->getEmail(),
            "phone" => $getUser->getPhone(),
            "events" => []
        ];

        for ($i = 0; $i < count($getUser->getEvent()); $i++) {
            $data["events"][$i] = "localhost:8000/api/events/" . $getUser->getEvents()[$i]->getId();
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/users', name: 'getAllUser_api', methods: ["GET"])]
    public function getAllUsers(ManagerRegistry $doctrine): JsonResponse
    {
        $getUser = $doctrine->getRepository(User::class)->findAll();

        $data = [];

        foreach ($getUser as $response) {
            $data[] = [
                "name" => $response->getName(),
                "Email" => $response->getEmail()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/events/{id}', name: 'getEvent_api', methods: ["GET"])]
    public function getEvent(ManagerRegistry $doctrine, $id): JsonResponse
    {
        $getEvent = $doctrine->getRepository(Event::class)->find($id);

        $data = [
            "name" => $getEvent->getName(),
            "place" => $getEvent->getPlace(),
            "end_date" => $getEvent->getEndDate(),
            "start_date" => $getEvent->getStartDate(),
            "description" => $getEvent->getDescription(),
            "Users" => $getEvent->getIdUser()
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    

    
    #[Route('/posts/{id}', name: 'getEvent_api', methods: ["GET"])]
    public function getPosts(ManagerRegistry $doctrine, $id): JsonResponse
    {
        $getPost = $doctrine->getRepository(Posts::class)->find($id);

        $data = [
            "created_at" => $getPost->getCreatedAt(),
            "likes" => $getPost->getLikes(),
            "text" => $getPost->getText(),
            "isSubmitted" => $getPost->getIsSubmitted(),
            "image" => $getPost->getImage(),
            "title" => $getPost->getTitle(),
            "Users"=>$getPost->getIdUser()
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }
    
    #[Route('/Todos', name: 'getAllEvents_api', methods: ["GET"])]
    public function getAllEvents(ManagerRegistry $doctrine): JsonResponse
    {
        $getAllEvents = $doctrine->getRepository(Event::class)->findAll();

        foreach ($getAllEvents as $getEvent) {
            $data[] = [
                "name" => $getEvent->getName(),
                "place" => $getEvent->getPlace(),
                "end_date" => $getEvent->getEndDate(),
                "start_date" => $getEvent->getStartDate(),
                "description" => $getEvent->getDescription(),
                "imagen" => $getEvent->getImagen(),
                "id" => $getEvent->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }
    
    #[Route('/posts', name: 'getAllPosts_api', methods: ["GET"])]
    public function getAllPosts(ManagerRegistry $doctrine): JsonResponse
    {
        $getAllPosts = $doctrine->getRepository(Posts::class)->findAll();

        foreach ($getAllPosts as $getPosts) {
            $data[] = [
                "created_at" => $getPosts->getCreatedAt(),
                "likes" => $getPosts->getLikes(),
                "text" => $getPosts->getText(),
                "isSubmitted" => $getPosts->getIsSubmitted(),
                "image" => $getPosts->getImage(),
                "title" => $getPosts->getTitle(),
                "id_user" => $getPosts->getIdUser()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/login', name: 'login_api', methods: ["POST"])]
    public function login(ManagerRegistry $doctrine, Request $request, UserRepository $repository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {

        $json = json_decode($request->getContent(), true);
        $user = $doctrine->getRepository(User::class)->findBy(["email" => $json["email"]])[0];

        if ($user !== null) {
            $password = $json["password"];
            if ($passwordHasher->isPasswordValid($user, $password)) {
                $data = [
                    "email" => $user->getEmail(),
                    "user" => $user->getName(),
                    "rol" => ($user->getRoles() === ["USER"]) ? "USER" : "ADMIN",
                    "id" => $user->getId()
                ];
            } else {
                $data = "";
            }
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/update/users/{id}', name: 'updateUser_api', methods: ["PUT"])]
    public function updateUser(ManagerRegistry $doctrine, UserRepository $repository, Request $request, $id): JsonResponse
    {

        $getUser = $doctrine->getRepository(User::class)->find($id);

        $json = json_decode($request->getContent(), true);
        print_r($json);
        empty($json["name"]) ? true : $getUser->setName($json["name"]);
        empty($json["email"]) ? true : $getUser->setEmail($json["email"]);

        $repository->update($getUser);

        return new JsonResponse(["status" => "User updated!"], Response::HTTP_OK);
    }

    #[Route('/update/event/{id}/{idUser}', name: 'updateEvent_api', methods: ["PUT"])]
    public function updateEvent(int $id, int $idUser, EventRepository $repository, ManagerRegistry $doctrine, UserRepository $userRepository): JsonResponse
    {

        $getUser = $doctrine->getRepository(User::class)->findBy(["id" => $idUser]);
        $getEvent = $doctrine->getRepository(Event::class)->findBy(["id" => $id]);
        $userRepository->updateAssistant($getEvent, $getUser);
        return new JsonResponse(["status" => "Event updated!"], Response::HTTP_OK);
    }

}
