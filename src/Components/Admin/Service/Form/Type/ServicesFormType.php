<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Type;

use App\Entity\Point;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicesFormType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Предоставляемые услуги',
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'Парковка' => Point::FIELD_SERVICE_PARKING,
                'Ресторан' => Point::FIELD_SERVICE_RESTAURANT,
                'Конференц-зал' => Point::FIELD_SERVICE_CONFERENCE_HALL,
                'SPA' => Point::FIELD_SERVICE_SPA,
                'Фитнес-центр' => Point::FIELD_SERVICE_FITNESS,
                'Бар' => Point::FIELD_SERVICE_BAR,
                'Закрытый бассейн' => Point::FIELD_SERVICE_INDOOR_POOL,
                'Открытый бассейн' => Point::FIELD_SERVICE_OUTDOOR_POOL,
                'Прокат авто' => Point::FIELD_SERVICE_CAR_RENTAL,
                'Сейф' => Point::FIELD_SERVICE_SAFE,
                'Библиотека' => Point::FIELD_SERVICE_LIBRARY,
                'Прачечная' => Point::FIELD_SERVICE_LAUNDRY,
                'Услуги гида-переводчика' => Point::FIELD_SERVICE_GUIDE,
                'Wi-Fi' => Point::FIELD_SERVICE_WI_FI,
                'Трансфер' => Point::FIELD_SERVICE_TRANSFER,
                'Сауна' => Point::FIELD_SERVICE_SAUNA,
                'Детская игровая площадка' => Point::FIELD_SERVICE_CHILDREN_PLAYGROUND,
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}