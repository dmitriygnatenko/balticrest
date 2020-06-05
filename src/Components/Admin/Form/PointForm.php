<?php

declare(strict_types=1);

namespace App\Components\Admin\Form;

use App\Components\Admin\Form\Type\CityFormType;
use App\Components\Admin\Form\Type\IsActiveFormType;
use App\Components\Admin\Form\Type\PointFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointForm extends AbstractType
{
    /** @var string */
    const LANG_FIELD_PREFIX = 'lang_';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('city', CityFormType::class)
            ->add('type', PointFormType::class)
            ->add('is_active', IsActiveFormType::class);

        $builder->add('lat', TextType::class, [
            'label' => 'Широта',
        ]);

        $builder->add('lon', TextType::class, [
            'label' => 'Долгота',
        ]);

        $builder->add('url', TextType::class, [
            'label' => 'URL',
        ]);

        $builder->add('email', EmailType::class, [
            'label' => 'Электронная почта',
        ]);

        $builder->add('website', TextType::class, [
            'label' => 'Сайт',
        ]);

        $builder->add('phones', TextType::class, [
            'label' => 'Телефоны',
        ]);

        $builder->add('logo', TextType::class, [
            'label' => 'Логотип',
        ]);

        $builder->add('comment', TextAreaType::class, [
            'label' => 'Комментарий',
        ]);

        $languages = is_array($options['languages']) ? $options['languages'] : [];

        foreach ($languages as $language) {

            $builder->add(self::LANG_FIELD_PREFIX . $language->getId() . '_title', TextType::class, [
                'label' => 'Название',
            ]);

            $builder->add(self::LANG_FIELD_PREFIX . $language->getId() . '_address', TextType::class, [
                'label' => 'Адрес',
            ]);

            $builder->add(self::LANG_FIELD_PREFIX . $language->getId() . '_desc', TextareaType::class, [
                'label' => 'Описание',
            ]);

            $builder->add(self::LANG_FIELD_PREFIX . $language->getId() . '_short_desc', TextareaType::class, [
                'label' => 'Краткое описание',
            ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('languages');
    }
}
