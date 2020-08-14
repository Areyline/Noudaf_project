<?php
namespace App\Controller;

use App\Entity\Role;
use App\Form\Type\RoleType;
use App\Repository\RoleRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/role")
 */
class RoleController extends AbstractController
{

    /**
     * @Route("/", name="noudaf_role_list")
     */
    public function list(RoleRepository $repository)
    {
        $roles = $repository->findAll();
        return $this->render('Role/list.html.twig', ['roles' => $roles]);
    }

    /**
     * @Route("/add", name="noudaf_role_add")
     */
    public function add(Request $request, ObjectManager $manager)
    {
        //Creation de l'objet qui contiendra le role
        $role = new Role();

        //Creation du formulaire
        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);
        //verification si le formulaire est rempli
        if($form->isSubmitted() && $form->isValid()){
            //recup des data
            $role = $form->getData();

            // persist the data in the database
            $manager->persist($role);
            //execute the query to save the data
            $manager->flush();


            //return new Response('role add'.$role->getId());
            return $this->redirectToRoute('noudaf_role_add');
        }

        //Creation de l'objet contenant la represantation visuel du form et envoi à la vue
        return $this->render('Role/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="noudaf_role_edit")
     */
    public function edit(ObjectManager $manager, Request $request, Role $role)
    {
        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $manager->persist($role);
            $manager->flush();

            return $this->redirectToRoute('noudaf_role_list');
        }

        return $this->render('Role/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="noudaf_role_delete")
     */
    public function delete(ObjectManager $manager, Role $role)
    {
        $manager->remove($role);
        $manager->flush();
        return $this->render('Role/list.html.twig');

    }

}