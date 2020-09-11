<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Image;

interface UserImageServiceInterface
{
    /** @var string */
    public const DEFAULT_AVATAR_PATH = '/public/static/balticrest/images/icons/avatar.png';

    /**
     * @param int|null $userId
     *
     * @return resource
     */
    public function getUserAvatarContent(int $userId = null);
}