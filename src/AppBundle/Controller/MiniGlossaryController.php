<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MiniGlossary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Idiom;
/**
 * Miniglossary controller.
 *
 */
class MiniGlossaryController extends Controller
{
    /**
     * Lists all miniGlossary entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $miniGlossaries = $em->getRepository('AppBundle:MiniGlossary')->findBy(array('isActive'=>true));
        $idioms = $em->getRepository('AppBundle:Idiom')->findAll();

        return $this->render('miniglossary/index.html.twig', array(
            'miniGlossaries' => $miniGlossaries,
            'idioms' => $idioms
        ));
    }


    /**
     * Search all miniGlossary entities.
     *
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $request->request->get('query');
        $miniGlossaries = $em->getRepository('AppBundle:MiniGlossary')->createQueryBuilder('m')
        ->where('m.topic LIKE :query')
        ->andWhere('m.isActive = true')
        ->orWhere('m.description LIKE :query')
        ->setParameter('query', '%'.$query.'%')
        ->getQuery()
        ->getResult();
        $terms = $em->getRepository('AppBundle:Term')->createQueryBuilder('t')
        ->where('t.text LIKE :query')
        ->orWhere('t.description LIKE :query')
        ->setParameter('query', '%'.$query.'%')
        ->getQuery()
        ->getResult();

        return $this->render('miniglossary/resultSearch.html.twig', array(
            'miniGlossaries' => $miniGlossaries,
            'terms' => $terms,
            'query' => $query
        ));
    }

    /**
     * Creates a new miniGlossary entity.
     *
     */
    public function newAction(Request $request,UserInterface $user)
    {
        $miniGlossary = new Miniglossary();
        $form = $this->createForm('AppBundle\Form\MiniGlossaryType', $miniGlossary);
        $form->handleRequest($request);
        $miniGlossary->setUser($user);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($miniGlossary);
            $em->flush();

            return $this->redirectToRoute('user_glosaries');
        }

        return $this->render('miniglossary/new.html.twig', array(
            'miniGlossary' => $miniGlossary,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a miniGlossary entity.
     *
     */
    public function showAction(MiniGlossary $miniGlossary)
    {
        $deleteForm = $this->createDeleteForm($miniGlossary);

        return $this->render('miniglossary/show.html.twig', array(
            'miniGlossary' => $miniGlossary,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing miniGlossary entity.
     *
     */
    public function editAction(Request $request, MiniGlossary $miniGlossary)
    {
        $deleteForm = $this->createDeleteForm($miniGlossary);
        $editForm = $this->createForm('AppBundle\Form\MiniGlossaryType', $miniGlossary);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('miniglossary_edit', array('id' => $miniGlossary->getId()));
        }

        return $this->render('miniglossary/edit.html.twig', array(
            'miniGlossary' => $miniGlossary,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a miniGlossary entity.
     *
     */
    public function deleteAction(Request $request, MiniGlossary $miniGlossary)
    {
        $form = $this->createDeleteForm($miniGlossary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($miniGlossary);
            $em->flush();
        }

        return $this->redirectToRoute('miniglossary_index');
    }

    /**
     * Creates a form to delete a miniGlossary entity.
     *
     * @param MiniGlossary $miniGlossary The miniGlossary entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MiniGlossary $miniGlossary)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('miniglossary_delete', array('id' => $miniGlossary->getId())))
        ->setMethod('DELETE')
        ->getForm()
        ;
    }
}
