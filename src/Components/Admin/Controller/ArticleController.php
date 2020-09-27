<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use App\Components\Admin\Service\DTO\ArticleDTO;
use App\Components\Admin\Service\Form\ArticleForm;
use App\Components\Admin\Service\Form\Mapper\ArticleFormDataTransformer;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Entity\Article;
use App\Entity\ArticleLangData;
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

class ArticleController extends AbstractController
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
     * @Route("/article", name="admin.article_list", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function list(Request $request): Response
    {
        $articles = [];

        try {
            $articlePaginator = $this->getDoctrine()->getRepository(Article::class)
                ->getPaginatedArticles($request);

            foreach ($articlePaginator->getIterator() as $article) {
                $articles[] = (new ArticleDTO())->fillByArticle($article);
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $articlePaginator = null;
        }

        return $this->render('admin/article/list.html.twig', [
            'articles' => $articles,
            'next_page' => $articlePaginator ? $articlePaginator->getNextPage() : null,
            'prev_page' => $articlePaginator ? $articlePaginator->getPrevPage() : null,
        ]);
    }

    /**
     * @Route("/article/add", name="admin.article_add", methods={"GET","POST"})
     *
     * @param Request $request
     * @param ArticleFormDataTransformer $articleFormDataTransformer
     *
     * @return Response
     */
    public function add(Request $request, ArticleFormDataTransformer $articleFormDataTransformer): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ArticleForm::class, [], [
            'em' => $entityManager,
            'validation_groups' => ArticleForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = new Article();
            $articleFormDataTransformer->transformToEntity($form->getData(), $article);

            try {
                $entityManager->persist($article);
                foreach ($article->getArticleLangData() as $articleLangData) {
                    $entityManager->persist($articleLangData);
                }
                $entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_ARTICLES);

                $this->addFlash('success', 'Статья успешно добавлена');
            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
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
     * @param ArticleFormDataTransformer $articleFormDataTransformer
     *
     * @return Response
     */
    public function edit(
        Article $article,
        Request $request,
        ArticleFormDataTransformer $articleFormDataTransformer
    ): Response {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $formData = $articleFormDataTransformer->transformFromEntity($article);

        $form = $this->createForm(ArticleForm::class, $formData, [
            'em' => $entityManager,
            'article' => $article,
            'validation_groups' => ArticleForm::VALIDATION_GROUP_UPDATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleFormDataTransformer->transformToEntity($form->getData(), $article);

            try {
                $entityManager->persist($article);
                foreach ($article->getArticleLangData() as $articleLangData) {
                    $entityManager->persist($articleLangData);
                }
                $entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_ARTICLES);

                $this->addFlash('success', 'Статья успешно сохранена');

            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
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
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        if ($request->isMethod('post')) {
            try {
                $entityManager->remove($article);
                $entityManager->flush();

                $this->cacheManager->clearByTag(CacheTagInterface::TAG_ARTICLES);

                $this->addFlash('success', 'Статья успешно удалена');
            } catch (InvalidArgumentException | OptimisticLockException | ORMException $exception) {
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