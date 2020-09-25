<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Mapper;

interface DataTransformer
{
    /**
     * @param object $entity
     *
     * @return array
     */
    public function transformFromEntity($entity): array;

    /**
     * @param array $form
     * @param object $entity
     */
    public function transformToEntity(array $form, $entity): void;
}