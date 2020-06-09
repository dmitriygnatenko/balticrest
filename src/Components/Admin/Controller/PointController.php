<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\DTO\PointDTO;
use App\Components\Admin\Service\Form\Mapper\PointFormDataMapper;
use App\Components\Admin\Service\Form\PointForm;
use App\Entity\Point;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class PointController extends AbstractController
{
    /**
     * @Route("/point", name="point_list", methods={"GET"})
     *
     * @param Request $request
     * @param LoggerInterface $logger
     *
     * @return Response
     */
    public function pointList(Request $request, LoggerInterface $logger): Response {
        $page = $request->query->getInt('page', 1);

        $points = [];

        try {
            $pointPaginator = $this->getDoctrine()->getRepository(Point::class)->getAllForList($page);

            foreach ($pointPaginator->getIterator() as $point) {
                $points[] = (new PointDTO())->fillByPoint($point);
            }
        } catch (Exception $exception) {
            $pointPaginator = null;
            $logger->error($exception->getMessage(), ['exception' => $exception]);
        }

        return $this->render('admin/point/list.html.twig', [
            'points' => $points,
            'next_page' => $pointPaginator->getNextPage(),
            'prev_page' => $pointPaginator->getPrevPage(),
        ]);
    }

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
                $this->addFlash('danger', 'Ошибка при добавлении объекта');
            }

            $this->addFlash('success', 'Объект успешно добавлен');

            return $this->redirectToRoute('point_list');
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
                $this->addFlash('danger', 'Ошибка при сохранении объекта');
            }

            $this->addFlash('success', 'Объект успешно сохранен');

            return $this->redirectToRoute('point_list');
        }

        return $this->render('admin/point/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редактирование объекта',
        ]);
    }
}