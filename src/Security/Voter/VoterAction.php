<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;

final class VoterAction
{
    public const VIEW = 'VIEW';

    public const EDIT = 'EDIT';

    public const DELETE = 'DELETE';

    public static function isAuthor(mixed $entity, User $user): bool
    {
        return $entity->getAuthor()->getId() === $user->getId();
    }
}
