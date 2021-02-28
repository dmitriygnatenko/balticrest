<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\Form\ArticleForm;
use App\Components\Admin\Service\Mapper\ArticleDTOMapper;
use App\Components\Admin\Service\Transformer\ArticleFormDataTransformer;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Entity\Article;
use App\Entity\ArticleLangData;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ArticleController extends AbstractController
{
    /** @var CacheManager */
    private $cacheManager;

    /** @var LoggerInterface */
    private $logger;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ArticleFormDataTransformer */
    private $articleFormDataTransformer;

    /**
     * @param CacheManagerInterface $cacheManager
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     * @param ArticleFormDataTransformer $articleFormDataTransformer
     */
    public function __construct(
        CacheManagerInterface $cacheManager,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        ArticleFormDataTransformer $articleFormDataTransformer
    ) {
        $this->cacheManager = $cacheManager;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->articleFormDataTransformer = $articleFormDataTransformer;
    }

    /**
     * @Route("/article", name="admin.article_list", methods={"GET"})
     *
     * @param Request $request
     * @param ArticleDTOMapper $articleDTOMapper
     *
     * @return Response
     */
    public function list(Request $request, ArticleDTOMapper $articleDTOMapper): Response
    {
        $articles = [];
        $page = $request->query->getInt('page', 1);

        try {
            $articlesPaginator = $this->entityManager->getRepository(Article::class)->getPaginatedArticles($page);
            $articles = $articleDTOMapper->fillAll((array) $articlesPaginator->getIterator());
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $articlesPaginator = null;
        }

        return $this->render('admin/article/list.html.twig', [
            'articles' => $articles,
            'next_page' => $articlesPaginator ? $articlesPaginator->getNextPage() : null,
            'prev_page' => $articlesPaginator ? $articlesPaginator->getPrevPage() : null,
        ]);
    }

    /**
     * @Route("/article/add", name="admin.article_add", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(ArticleForm::class, [], [
            'validation_groups' => ArticleForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = new Article();
            $this->articleFormDataTransformer->transformToEntity($form->getData(), $article);

            try {
                $this->entityManager->persist($article);
                foreach ($article->getArticleLangData() as $articleLangData) {
                    $this->entityManager->persist($articleLangData);
                }
                $this->entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_ARTICLES);

                $this->addFlash('success', 'Статья успешно добавлена');
            } catch (Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('danger', 'Ошибка при добавлении статьи');
            }

            return $this->redirectToRoute('admin.article_list');
        }

        return $this->render('admin/article/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Добавление статьи',
        ]);
    }

    /**
     * @Route("/article/edit/{id}", name="admin.article_edit", methods={"GET","POST"})
     *
     * @param Article $article
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Article $article, Request $request): Response {
        $formData = $this->articleFormDataTransformer->transformFromEntity($article);

        $form = $this->createForm(ArticleForm::class, $formData, [
            'article' => $article,
            'validation_groups' => ArticleForm::VALIDATION_GROUP_UPDATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleFormDataTransformer->transformToEntity($form->getData(), $article);

            try {
                $this->entityManager->persist($article);
                foreach ($article->getArticleLangData() as $articleLangData) {
                    $this->entityManager->persist($articleLangData);
                }
                $this->entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_ARTICLES);

                $this->addFlash('success', 'Статья успешно сохранена');
            } catch (Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            }

            return $this->redirectToRoute('admin.article_list');
        }

        return $this->render('admin/article/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редактирование статьи',
        ]);
    }

    /**
     * @Route("/article/delete/{id}", name="admin.article_delete", methods={"GET","POST"})
     *
     * @param Article $article
     * @param Request $request
     *
     * @return Response
     */
    public function delete(Article $article, Request $request): Response
    {
        if ($request->isMethod('post')) {
            try {
                $this->entityManager->remove($article);
                $this->entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_ARTICLES);

                $this->addFlash('success', 'Статья успешно удалена');
            } catch (Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('danger', 'Ошибка при удалении статьи');
            }

            return $this->redirectToRoute('admin.article_list');
        }

        $articleRuData = $article->getArticleLangData()->filter(static function($articleLangData) {
            /** @var ArticleLangData $articleLangData */
            return $articleLangData->getLanguage()->getCode() === 'ru';
        });

        $title = !$articleRuData->isEmpty() ? $articleRuData->first()->getTitle() : '';

        return $this->render('admin/article/delete.html.twig', [
            'title' => $title,
        ]);
    }
}