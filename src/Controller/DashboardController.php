<?php


namespace App\Controller;


use App\Entity\Message;
use App\Entity\Role;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $users = $entityManager->getRepository(User::class)->findAll();
        $lastMessagesCount = $entityManager->getRepository(Message::class)->findUsersWithLastMessageInTopic();
        return $this->render('dashboard.html.twig', ['users' => $users, 'lastMessagesCount' => $lastMessagesCount]);
    }

    /**
     * @Route("/dashboard/{user}/topics/{topic}", defaults={"topic" = null}, name="topics")
     */
    public function topics(EntityManagerInterface $entityManager, User $user, ?Topic $topic): Response
    {
        $role = $this->getUser()->getRole();
        if (!($role instanceof Role) || RoleRepository::ADMIN_ROLE !== $role->getId()) {
            return $this->redirectToRoute('dashboard');
        }

        if ($topic instanceof Topic) {
            $message = new Message();
            $message->setTopic($topic);
            $message->setUser($this->getUser());
            $entityManager->persist($message);
            $entityManager->flush();
        }

        $topicsWithMessages = $entityManager->getRepository(Topic::class)->findTopicsWithMessages($user);

        return $this->render('topics.html.twig', ['user' => $user, 'topicsWithMessages' => $topicsWithMessages]);
    }

    /**
     * @Route("/dashboard/profile", name="profile")
     */
    public function profile(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('profile.html.twig');
    }

    /**
     * @Route("/dashboard/send-message", name="send_message")
     */
    public function sendMessage(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $message = new Message();
        $message->setUser($this->getUser());

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $topic = $formData->getTopic();
            $entityManager->persist($topic);
            $entityManager->persist($formData);
            $entityManager->flush();
            $this->addFlash('success', 'Message is sent!');

            return $this->redirectToRoute('send_message');
        }

        return $this->render('sendMessage.html.twig', ['form' => $form->createView()]);
    }
}