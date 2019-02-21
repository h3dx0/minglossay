<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Translate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
/**
 * Translate controller.
 *
 */
class TranslateController extends Controller
{
    const RESULT_OK = 0;
    const RESULT_ERROR = -1;

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

    public function newAjaxAction(Request $request)
    {
        $result['rc'] = self::RESULT_ERROR;
        $em = $this->getDoctrine()->getManager();

        $textTranslate = $request->request->get('translation');
        $termTranslate = $em->getRepository('AppBundle:Term')->find($request->request->get('term'));

        $idiomTranslate = $em->getRepository('AppBundle:Idiom')->find($request->request->get('idiom'));
        $user = $em->getRepository('AppBundle:User')->find(1);
        //HGACER validacion para q no pueda meter mas de 5
        $translate = new Translate();
        $translate->setText($textTranslate);
        $translate->setTerm($termTranslate);
        $translate->setIdiom($idiomTranslate);
        $translate->setUser($user);
        $validator = $this->get('validator');
        if ($validator->validate($translate)) {           
            $em->persist($translate);
            $em->flush();
            $result['rc'] = self::RESULT_OK;               
        }
        $response = new Response(json_encode($result), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
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
    /**
     * Set base HTTP headers.
     *
     * @param Response $response
     *
     * @return Response
    */
    private function setBaseHeaders(Response $response)
    {
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
        $response->headers->set('Access-Control-Max-Age', 3600);

        return $response;
    }

}
