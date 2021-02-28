<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\Form\NewsForm;
use App\Components\Admin\Service\Mapper\NewsDTOMapper;
use App\Components\Admin\Service\Transformer\NewsFormDataTransformer;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Entity\News;
use App\Entity\NewsLangData;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use Throwable;

class NewsController extends AbstractController
{
    /** @var CacheManager */
    private $cacheManager;

    /** @var LoggerInterface */
    private $logger;

    /** @var NewsFormDataTransformer */
    private $newsFormDataTransformer;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param CacheManagerInterface $cacheManager
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     * @param NewsFormDataTransformer $newsFormDataTransformer
     */
    public function __construct(
        CacheManagerInterface $cacheManager,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        NewsFormDataTransformer $newsFormDataTransformer
    ) {
        $this->cacheManager = $cacheManager;
        $this->logger = $logger;
        $this->newsFormDataTransformer = $newsFormDataTransformer;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/news", name="admin.news_list", methods={"GET"})
     *
     * @param Request $request
     * @param NewsDTOMapper $newsDTOMapper
     *
     * @return Response
     */
    public function list(Request $request, NewsDTOMapper $newsDTOMapper): Response
    {
        $news = [];

        $page = $request->query->getInt('page', 1);

        try {
            $newsPaginator = $this->entityManager->getRepository(News::class)->getPaginatedNews($page);
            $news = $newsDTOMapper->fillAll((array) $newsPaginator->getIterator());
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
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(NewsForm::class, [], [
            'validation_groups' => NewsForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $news = new News();
            $this->newsFormDataTransformer->transformToEntity($form->getData(), $news);

            try {
                $this->entityManager->persist($news);
                foreach ($news->getNewsLangData() as $newsLangData) {
                    $this->entityManager->persist($newsLangData);
                }
                $this->entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_NEWS);

                $this->addFlash('success', 'Новость успешно добавлена');
            } catch (Throwable $exception) {
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
     *
     * @return Response
     */
    public function edit(News $news, Request $request): Response
    {
        $formData = $this->newsFormDataTransformer->transformFromEntity($news);

        $form = $this->createForm(NewsForm::class, $formData, [
            'validation_groups' => NewsForm::VALIDATION_GROUP_UPDATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->newsFormDataTransformer->transformToEntity($form->getData(), $news);

            try {
                $this->entityManager->persist($news);
                foreach ($news->getNewsLangData() as $newsLangData) {
                    $this->entityManager->persist($newsLangData);
                }
                $this->entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_NEWS);

                $this->addFlash('success', 'Новость успешно сохранена');
            } catch (Throwable $exception) {
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
        if ($request->isMethod('post')) {
            try {
                $this->entityManager->remove($news);
                $this->entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_NEWS);

                $this->addFlash('success', 'Новость успешно удалена');
            } catch (Throwable $exception) {
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