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
    public function addPoint(Request $request, PointFormDataMapper $pointFormDataMapper, LoggerInterface $logger): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(PointForm::class, [], [
            'em' => $entityManager,
            'validation_groups' => PointForm::VALIDATION_GROUP_CREATE
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $point = new Point();
            $pointFormDataMapper->mapFormsToData($form->getData(), $point);

            try {
                $entityManager->persist($point);

                foreach ($point->getPointLangData() as $pointLangData) {
                    $entityManager->persist($pointLangData);
                }

                $entityManager->flush();
            } catch (\Throwable $exception) {
                $logger->error($exception->getMessage(), ['exception' => $exception]);
            }

            // TODO
            //return $this->redirectToRoute('point_lang_data_index');
        }

        return $this->render('admin/point/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}