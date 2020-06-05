<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Form\PointForm;
use App\Entity\Language;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PointController extends AbstractController
{
    /**
     * @Route("/point/add", name="point_add", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $languages = $this->getDoctrine()->getRepository(Language::class)
            ->findBy(['is_active' => true]);

        $form = $this->createForm(PointForm::class, [], [
            'languages' => $languages
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           // dump($form);
            /*
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pointLangDatum);
            $entityManager->flush();
            */

            //return $this->redirectToRoute('point_lang_data_index');
        }

        return $this->render('admin/point/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}