<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Interfaces\ServiceTypesInterface as ServiceTypes;

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
                'Парковка' => ServiceTypes::FIELD_SERVICE_PARKING,
                'Ресторан' => ServiceTypes::FIELD_SERVICE_RESTAURANT,
                'Конференц-зал' => ServiceTypes::FIELD_SERVICE_CONFERENCE_HALL,
                'SPA' => ServiceTypes::FIELD_SERVICE_SPA,
                'Фитнес-центр' => ServiceTypes::FIELD_SERVICE_FITNESS,
                'Бар' => ServiceTypes::FIELD_SERVICE_BAR,
                'Закрытый бассейн' => ServiceTypes::FIELD_SERVICE_INDOOR_POOL,
                'Открытый бассейн' => ServiceTypes::FIELD_SERVICE_OUTDOOR_POOL,
                'Прокат авто' => ServiceTypes::FIELD_SERVICE_CAR_RENTAL,
                'Сейф' => ServiceTypes::FIELD_SERVICE_SAFE,
                'Библиотека' => ServiceTypes::FIELD_SERVICE_LIBRARY,
                'Прачечная' => ServiceTypes::FIELD_SERVICE_LAUNDRY,
                'Услуги гида-переводчика' => ServiceTypes::FIELD_SERVICE_GUIDE,
                'Wi-Fi' => ServiceTypes::FIELD_SERVICE_WI_FI,
                'Трансфер' => ServiceTypes::FIELD_SERVICE_TRANSFER,
                'Сауна' => ServiceTypes::FIELD_SERVICE_SAUNA,
                'Детская игровая площадка' => ServiceTypes::FIELD_SERVICE_CHILDREN_PLAYGROUND,
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