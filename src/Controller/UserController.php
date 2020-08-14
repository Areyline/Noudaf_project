<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="noudaf_user_list")
     */
    public function list(UserRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('User/list_user.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/show/{id}", name="noudaf_user_show")
     */
    public function show(User $user)
    {
        return $this->render('User/show_user.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/add", name="noudaf_user_add")
     */
    public function add (Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($this->passwordEncoder->encodePassword($user,$user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('noudaf_user_add');
        }

        return $this->render('User/add_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="noudaf_user_edit")
     */
    public function edit(User $user, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('noudaf_user_list');
        }

        return $this->render('User/edit_user.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/delete/{id}", name="noudaf_user_delete")
     */
    public function delete(User $user, ObjectManager $manager)
    {
        $manager->remove($user);
        $manager->flush();
        return $this->render('User/list_user.html.twig');
    }

}