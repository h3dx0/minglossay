<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Term;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Term controller.
 *
 */
class TermController extends Controller
{
    /**
     * Lists all term entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $terms = $em->getRepository('AppBundle:Term')->findAll();

        return $this->render('term/index.html.twig', array(
            'terms' => $terms,
        ));
    }

    public function termsByGlossaryAction($idGlossary)
    {
        $em = $this->getDoctrine()->getManager();
        $glossary = $em->getRepository('AppBundle:MiniGlossary')->find($idGlossary);
        $terms = $em->getRepository('AppBundle:Term')->findBy(array('miniglossary' => $idGlossary));
        $idioms = $em->getRepository('AppBundle:Idiom')->findAll();

        return $this->render('term/termsByGlossary.html.twig', array(
            'terms' => $terms,
            'glossary' => $glossary,
            'idioms' => $idioms

        )); 
    }
    /**
     * Creates a new term entity.
     *
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $term = new Term();
        $form = $this->createForm('AppBundle\Form\TermType', $term);
        $form->handleRequest($request);
        $glossary = $form->get('miniglossary')->getData();
        if(!empty($glossary)){
            $miniGlossary = $em->getRepository('AppBundle:MiniGlossary')->find($glossary);

            if(count($miniGlossary->getTerms()) >= 5){
              $this->addFlash(
                'error', 'You have reach the maximum amount of terms 5.'
            );
              return $this->redirectToRoute('user_glosaries');

          }

      }


      if ($form->isSubmitted() && $form->isValid() ) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($term);
        if(count($miniGlossary->getTerms()) >= 2){

          $miniGlossary->setIsActive(true);
          $em->persist($miniGlossary);

      }
      $em->flush();
      $this->addFlash(
        'success', 'Term succefull added to glossary'.$miniGlossary->getTopic()
    );
      return $this->redirectToRoute('miniglossary_index');
  }


  return $this->render('term/new.html.twig', array(
    'term' => $term,
    'form' => $form->createView(),
));
}

    /**
     * Finds and displays a term entity.
     *
     */
    public function showAction(Term $term)
    {
        $deleteForm = $this->createDeleteForm($term);

        return $this->render('term/show.html.twig', array(
            'term' => $term,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing term entity.
     *
     */
    public function editAction(Request $request, Term $term)
    {
        $deleteForm = $this->createDeleteForm($term);
        $editForm = $this->createForm('AppBundle\Form\TermType', $term);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('term_edit', array('id' => $term->getId()));
        }

        return $this->render('term/edit.html.twig', array(
            'term' => $term,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a term entity.
     *
     */
    public function deleteAction(Request $request, Term $term)
    {
        $form = $this->createDeleteForm($term);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($term);
            $em->flush();
        }

        return $this->redirectToRoute('term_index');
    }

    /**
     * Creates a form to delete a term entity.
     *
     * @param Term $term The term entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Term $term)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('term_delete', array('id' => $term->getId())))
        ->setMethod('DELETE')
        ->getForm()
        ;
    }
}
