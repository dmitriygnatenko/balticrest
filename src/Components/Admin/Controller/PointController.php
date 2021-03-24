<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\Form\PointForm;
use App\Components\Admin\Service\Mapper\PointDTOMapper;
use App\Components\Admin\Service\Transformer\PointFormDataTransformer;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Entity\Point;
use App\Entity\PointLangData;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class PointController extends AbstractController
{
    /** @var CacheManager */
    private $cacheManager;

    private LoggerInterface $logger;

    private EntityManagerInterface $entityManager;

    private PointFormDataTransformer $pointFormDataTransformer;

    /**
     * @param CacheManagerInterface $cacheManager
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     * @param PointFormDataTransformer $pointFormDataTransformer
     */
    public function __construct(
        CacheManagerInterface $cacheManager,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        PointFormDataTransformer $pointFormDataTransformer
    )
    {
        $this->cacheManager = $cacheManager;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->pointFormDataTransformer = $pointFormDataTransformer;
    }

    /**
     * @Route("/point", name="admin.point_list", methods={"GET"})
     *
     * @param Request $request
     * @param PointDTOMapper $pointDTOMapper
     *
     * @return Response
     */
    public function list(Request $request, PointDTOMapper $pointDTOMapper): Response
    {
        $points = [];

        try {
            $pointPaginator = $this->entityManager->getRepository(Point::class)->getPaginatedFilteredPoints($request);
            $points = $pointDTOMapper->fillAll((array) $pointPaginator->getIterator());
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $pointPaginator = null;
        }

        return $this->render('admin/point/list.html.twig', [
            'points' => $points,
            'next_page' => $pointPaginator ? $pointPaginator->getNextPage() : null,
            'prev_page' => $pointPaginator ? $pointPaginator->getPrevPage() : null,
        ]);
    }

    /**
     * @Route("/point/add", name="admin.point_add", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(PointForm::class, [], [
            'validation_groups' => PointForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $point = new Point();

            $this->pointFormDataTransformer->transformToEntity($form->getData(), $point);

            try {
                $this->entityManager->persist($point);
                foreach ($point->getPointLangData() as $pointLangData) {
                    $this->entityManager->persist($pointLangData);
                }
                $this->entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_POINTS);

                $this->addFlash('success', 'Объект успешно добавлен');
            } catch (Throwable $exception) {
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
     * @Route("/point/edit/{id}", name="admin.point_edit", methods={"GET","POST"})
     *
     * @param Point $point
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Point $point, Request $request): Response
    {
        $formData = $this->pointFormDataTransformer->transformFromEntity($point);

        $form = $this->createForm(PointForm::class, $formData, [
            'point' => $point,
            'validation_groups' => PointForm::VALIDATION_GROUP_UPDATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->pointFormDataTransformer->transformToEntity($form->getData(), $point);

            try {
                $this->entityManager->persist($point);
                foreach ($point->getPointLangData() as $pointLangData) {
                    $this->entityManager->persist($pointLangData);
                }
                $this->entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_POINTS);

                $this->addFlash('success', 'Объект успешно сохранен');
            } catch (Throwable $exception) {
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
     * @Route("/point/delete/{id}", name="admin.point_delete", methods={"GET","POST"})
     *
     * @param Point $point
     * @param Request $request
     *
     * @return Response
     */
    public function delete(Point $point, Request $request): Response
    {
        if ($request->isMethod('post')) {
            try {
                $this->entityManager->remove($point);
                $this->entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_POINTS);

                $this->addFlash('success', 'Объект успешно удален');
            } catch (Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('danger', 'Ошибка при удалении объекта');
            }

            return $this->redirectToRoute('admin.point_list');
        }

        $pointRuData = $point->getPointLangData()->filter(static function($pointLangData) {
            /** @var PointLangData $pointLangData */
            return $pointLangData->getLanguage()->getCode() === 'ru';
        });

        $title = !$pointRuData->isEmpty() ? $pointRuData->first()->getTitle() : '';

        return $this->render('admin/point/delete.html.twig', [
            'title' => $title,
        ]);
    }
}