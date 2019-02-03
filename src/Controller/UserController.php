<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Entity\User;
use App\Exception\DuplicateEntryException;
use App\Form\UserType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;

class UserController extends ApiController
{
    /**
     * @Route("/api/user", name="add_user", methods={"POST", "GET"})
     *
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function addOrGetUser(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ) {
        if ($request->isMethod('POST')) {
            return $this->addUser($request, $formFactory, $entityManager, $serializer);
        }

        return $this->getUsers($request, $entityManager, $serializer);
    }

    private function getUsers(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ): Response {
        $this->isValidAccept($request);

        if ($request->query->has('havemovie') && $request->query->get('havemovie') === 'true') {
            $users = $entityManager->getRepository(User::class)->findUserHaveMovie();
        } else {
            $users = $entityManager->getRepository(User::class)->findAll();
        }

        return new Response(
            $serializer->serialize($users, self::FORMAT),
            Response::HTTP_OK
        );
    }

    private function addUser(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ): Response {
        $this->isValidBody($request);

        $user = new User();
        $userForm = $formFactory->create(UserType::class, $user);
        $userForm->submit(json_decode($request->getContent(), true));

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            try {
                $entityManager->persist($user);
                $entityManager->flush();

                return new Response(
                    $serializer->serialize($user, self::FORMAT),
                    Response::HTTP_CREATED
                );
            } catch (UniqueConstraintViolationException $e) {
                throw new DuplicateEntryException(Response::HTTP_FORBIDDEN, 'This email is already used', $e);
            }
        }

        if ($userForm->getErrors(true)->count() > 0) {
            return $this->getResponseWithFormErrors($userForm->getErrors(true));
        }
    }

    protected function fetchUser(int $idUser, EntityManagerInterface $em): User
    {
        $user = $em->getRepository(User::class)->find($idUser);

        if (null === $user) {
            throw new NotFoundHttpException('User not found');
        }

        return $user;
    }
}
