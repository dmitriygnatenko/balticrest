<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\DTO\PointDTO;
use App\Components\Admin\Service\Form\Mapper\PointFormDataMapper;
use App\Components\Admin\Service\Form\PointForm;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Entity\Point;
use App\Entity\PointLangData;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class PointController extends AbstractController
{
    /** @var CacheManager */
    private $cacheManager;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param CacheManagerInterface $cacheManager
     * @param LoggerInterface $logger
     */
    public function __construct(CacheManagerInterface $cacheManager, LoggerInterface $logger)
    {
        $this->cacheManager = $cacheManager;
        $this->logger = $logger;
    }

    /**
     * @Route("/point", name="admin.point_list", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function pointList(Request $request): Response {
        $points = [];

        try {
            $pointPaginator = $this->getDoctrine()->getRepository(Point::class)
                ->getPaginatedFilteredPoints($request);

            foreach ($pointPaginator->getIterator() as $point) {
                $points[] = (new PointDTO())->fillByPoint($point);
            }
        } catch (Exception $exception) {
            $pointPaginator = null;
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
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
     *
     * @return Response
     */
    public function addPoint(Request $request, PointFormDataMapper $pointFormDataMapper): Response {
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

                $this->cacheManager->clearByTag($point->getCity()->getCode());

                $this->addFlash('success', 'Объект успешно добавлен');
            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('danger', 'Ошибка при добавлении объекта');
            }

            return $this->redirectToRoute('admin.point_list');
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
     *
     * @return Response
     */
    public function editPoint(
        Point $point,
        Request $request,
        PointFormDataMapper $pointFormDataMapper
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

                $this->cacheManager->clearByTag($point->getCity()->getCode());

                $this->addFlash('success', 'Объект успешно сохранен');

            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            }

            return $this->redirectToRoute('admin.point_list');
        }

        return $this->render('admin/point/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редактирование объекта',
        ]);
    }

    /**
     * @Route("/point/delete/{id}", name="point_delete", methods={"GET","POST"})
     *
     * @param Point $point
     * @param Request $request
     *
     * @return Response
     */
    public function deletePoint(Point $point, Request $request): Response {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        if ($request->isMethod('post')) {
            try {
                $city = $point->getCity()->getCode();

                $entityManager->remove($point);
                $entityManager->flush();

                $this->cacheManager->clearByTag($city);

                $this->addFlash('success', 'Объект успешно удален');
            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('danger', 'Ошибка при удалении объекта');
            }

            return $this->redirectToRoute('admin.point_list');
        }

        $pointRuData = $point->getPointLangData()->filter(function($pointLangData) {
            /** @var PointLangData $pointLangData */
            return $pointLangData->getLanguage()->getCode() == 'ru';
        });

        $title = !$pointRuData->isEmpty() ? $pointRuData->first()->getTitle() : '';

        return $this->render('admin/point/delete.html.twig', [
            'title' => $title,
        ]);
    }
}