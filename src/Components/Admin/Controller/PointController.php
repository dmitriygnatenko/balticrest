<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\Form\Mapper\PointFormDataMapper;
use App\Components\Admin\Service\Form\PointForm;
use App\Entity\Point;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
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
     * @param PointFormDataMapper $pointFormDataMapper
     * @param LoggerInterface $logger
     *
     * @return Response
     */
    public function addPoint(
        Request $request,
        PointFormDataMapper $pointFormDataMapper,
        LoggerInterface $logger
    ): Response {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(PointForm::class, [], [
            'em' => $entityManager,
            'validation_groups' => PointForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $point = new Point();
            $pointFormDataMapper->mapFormToPoint($form->getData(), $point);

            try {
                $entityManager->persist($point);
                foreach ($point->getPointLangData() as $pointLangData) {
                    $entityManager->persist($pointLangData);
                }
                $entityManager->flush();
            } catch (\Throwable $exception) {
                $logger->error($exception->getMessage(), ['exception' => $exception]);
                // TODO
            }

            // TODO
            //return $this->redirectToRoute('point_lang_data_index');
        }

        return $this->render('admin/point/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Добавление объекта',
        ]);
    }

    /**
     * @Route("/point/edit/{id}", name="point_edit", methods={"GET","POST"})
     *
     * @param Point $point
     * @param Request $request
     * @param PointFormDataMapper $pointFormDataMapper
     * @param LoggerInterface $logger
     *
     * @return Response
     */
    public function editPoint(
        Point $point,
        Request $request,
        PointFormDataMapper $pointFormDataMapper,
        LoggerInterface $logger
    ): Response {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $formData = $pointFormDataMapper->mapPointToForm($point);

        $form = $this->createForm(PointForm::class, $formData, [
            'em' => $entityManager,
            'point' => $point,
            'validation_groups' => PointForm::VALIDATION_GROUP_UPDATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pointFormDataMapper->mapFormToPoint($form->getData(), $point);

            try {
                $entityManager->persist($point);
                foreach ($point->getPointLangData() as $pointLangData) {
                    $entityManager->persist($pointLangData);
                }
                $entityManager->flush();
            } catch (\Throwable $exception) {
                $logger->error($exception->getMessage(), ['exception' => $exception]);
                // TODO
            }

            // TODO
            //return $this->redirectToRoute('point_lang_data_index');
        }

        return $this->render('admin/point/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редактирование объекта',
        ]);
    }
}