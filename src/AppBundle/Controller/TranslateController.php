<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Translate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Translate controller.
 *
 */
class TranslateController extends Controller
{
    /**
     * Lists all translate entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $translates = $em->getRepository('AppBundle:Translate')->findAll();

        return $this->render('translate/index.html.twig', array(
            'translates' => $translates,
        ));
    }

    /**
     * Creates a new translate entity.
     *
     */
    public function newAction(Request $request)
    {
        $translate = new Translate();
        $form = $this->createForm('AppBundle\Form\TranslateType', $translate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($translate);
            $em->flush();

            return $this->redirectToRoute('translate_show', array('id' => $translate->getId()));
        }

        return $this->render('translate/new.html.twig', array(
            'translate' => $translate,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a translate entity.
     *
     */
    public function showAction(Translate $translate)
    {
        $deleteForm = $this->createDeleteForm($translate);

        return $this->render('translate/show.html.twig', array(
            'translate' => $translate,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing translate entity.
     *
     */
    public function editAction(Request $request, Translate $translate)
    {
        $deleteForm = $this->createDeleteForm($translate);
        $editForm = $this->createForm('AppBundle\Form\TranslateType', $translate);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('translate_edit', array('id' => $translate->getId()));
        }

        return $this->render('translate/edit.html.twig', array(
            'translate' => $translate,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a translate entity.
     *
     */
    public function deleteAction(Request $request, Translate $translate)
    {
        $form = $this->createDeleteForm($translate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($translate);
            $em->flush();
        }

        return $this->redirectToRoute('translate_index');
    }

    /**
     * Creates a form to delete a translate entity.
     *
     * @param Translate $translate The translate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Translate $translate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('translate_delete', array('id' => $translate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
