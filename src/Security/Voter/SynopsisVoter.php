<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Synopsis;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SynopsisVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW])
            && $subject instanceof \App\Entity\Synopsis;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                return $this->canHandleSynopsis($subject, $user);

            case self::EDIT:
                return $this->canHandleSynopsis($subject, $user);

            case self::DELETE:
                return $this->canHandleSynopsis($subject, $user);
        }

        return false;
    }

    public function canHandleSynopsis(Synopsis $synopsis, User $user): bool
    {
        
        return $synopsis->getAuthor()->getId() === $user->getId();
    }
}
