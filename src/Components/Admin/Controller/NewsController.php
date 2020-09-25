<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\DTO\NewsDTO;
use App\Components\Admin\Service\Form\Mapper\NewsFormDataTransformer;
use App\Components\Admin\Service\Form\NewsForm;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Entity\News;
use App\Entity\NewsLangData;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class NewsController extends AbstractController
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
     * @Route("/news", name="admin.news_list", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function list(Request $request): Response
    {
        $news = [];

        try {
            $newsPaginator = $this->getDoctrine()->getRepository(News::class)
                ->getPaginatedNews($request);

            foreach ($newsPaginator->getIterator() as $record) {
                $news[] = (new NewsDTO())->fillByNews($record);
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $newsPaginator = null;
        }

        return $this->render('admin/news/list.html.twig', [
            'news' => $news,
            'next_page' => $newsPaginator ? $newsPaginator->getNextPage() : null,
            'prev_page' => $newsPaginator ? $newsPaginator->getPrevPage() : null,
        ]);
    }

    /**
     * @Route("/news/add", name="admin.news_add", methods={"GET","POST"})
     *
     * @param Request $request
     * @param NewsFormDataTransformer $newsFormDataTransformer
     *
     * @return Response
     */
    public function add(Request $request, NewsFormDataTransformer $newsFormDataTransformer): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(NewsForm::class, [], [
            'em' => $entityManager,
            'validation_groups' => NewsForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $news = new News();
            $newsFormDataTransformer->transformToEntity($form->getData(), $news);

            try {
                $entityManager->persist($news);
                foreach ($news->getNewsLangData() as $newsLangData) {
                    $entityManager->persist($newsLangData);
                }
                $entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_NEWS);

                $this->addFlash('success', 'Новость успешно добавлена');
            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('danger', 'Ошибка при добавлении новости');
            }

            return $this->redirectToRoute('admin.news_list');
        }

        return $this->render('admin/news/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Добавление новости',
        ]);
    }

    /**
     * @Route("/news/edit/{id}", name="admin.news_edit", methods={"GET","POST"})
     *
     * @param News $news
     * @param Request $request
     * @param NewsFormDataTransformer $newsFormDataTransformer
     *
     * @return Response
     */
    public function edit(
        News $news,
        Request $request,
        NewsFormDataTransformer $newsFormDataTransformer
    ): Response {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $formData = $newsFormDataTransformer->transformFromEntity($news);

        $form = $this->createForm(NewsForm::class, $formData, [
            'em' => $entityManager,
            'validation_groups' => NewsForm::VALIDATION_GROUP_UPDATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsFormDataTransformer->transformToEntity($form->getData(), $news);

            try {
                $entityManager->persist($news);
                foreach ($news->getNewsLangData() as $newsLangData) {
                    $entityManager->persist($newsLangData);
                }
                $entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_NEWS);

                $this->addFlash('success', 'Новость успешно сохранена');

            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            }

            return $this->redirectToRoute('admin.news_list');
        }

        return $this->render('admin/news/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редактирование новости',
        ]);
    }

    /**
     * @Route("/news/delete/{id}", name="admin.news_delete", methods={"GET","POST"})
     *
     * @param News $news
     * @param Request $request
     *
     * @return Response
     */
    public function delete(News $news, Request $request): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        if ($request->isMethod('post')) {
            try {
                $entityManager->remove($news);
                $entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_NEWS);

                $this->addFlash('success', 'Новость успешно удалена');
            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('danger', 'Ошибка при удалении новости');
            }

            return $this->redirectToRoute('admin.news_list');
        }

        $newsRuData = $news->getNewsLangData()->filter(static function ($newsLangData) {
            /** @var NewsLangData $newsLangData */
            return $newsLangData->getLanguage()->getCode() === 'ru';
        });

        $title = !$newsRuData->isEmpty() ? $newsRuData->first()->getTitle() : '';

        return $this->render('admin/news/delete.html.twig', [
            'title' => $title,
        ]);
    }
}