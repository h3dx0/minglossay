<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MiniGlossary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        $miniGlossaries = $em->getRepository('AppBundle:MiniGlossary')->findAll();

        return $this->render('miniglossary/index.html.twig', array(
            'miniGlossaries' => $miniGlossaries,
        ));
    }

    /**
     * Creates a new miniGlossary entity.
     *
     */
    public function newAction(Request $request)
    {
        $miniGlossary = new Miniglossary();
        $form = $this->createForm('AppBundle\Form\MiniGlossaryType', $miniGlossary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($miniGlossary);
            $em->flush();

            return $this->redirectToRoute('miniglossary_show', array('id' => $miniGlossary->getId()));
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