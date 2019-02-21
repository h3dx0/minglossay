<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Idiom;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Idiom controller.
 *
 */
class IdiomController extends Controller
{
    /**
     * Lists all idiom entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $idioms = $em->getRepository('AppBundle:Idiom')->findAll();

        return $this->render('idiom/index.html.twig', array(
            'idioms' => $idioms,
        ));
    }

    /**
     * Creates a new idiom entity.
     *
     */
    public function newAction(Request $request)
    {
        $idiom = new Idiom();
        $form = $this->createForm('AppBundle\Form\IdiomType', $idiom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($idiom);
            $em->flush();

            return $this->redirectToRoute('idiom_show', array('id' => $idiom->getId()));
        }

        return $this->render('idiom/new.html.twig', array(
            'idiom' => $idiom,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a idiom entity.
     *
     */
    public function showAction(Idiom $idiom)
    {
        $deleteForm = $this->createDeleteForm($idiom);

        return $this->render('idiom/show.html.twig', array(
            'idiom' => $idiom,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing idiom entity.
     *
     */
    public function editAction(Request $request, Idiom $idiom)
    {
        $deleteForm = $this->createDeleteForm($idiom);
        $editForm = $this->createForm('AppBundle\Form\IdiomType', $idiom);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('idiom_edit', array('id' => $idiom->getId()));
        }

        return $this->render('idiom/edit.html.twig', array(
            'idiom' => $idiom,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a idiom entity.
     *
     */
    public function deleteAction(Request $request, Idiom $idiom)
    {
        $form = $this->createDeleteForm($idiom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($idiom);
            $em->flush();
        }

        return $this->redirectToRoute('idiom_index');
    }

    /**
     * Creates a form to delete a idiom entity.
     *
     * @param Idiom $idiom The idiom entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Idiom $idiom)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('idiom_delete', array('id' => $idiom->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
