<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Chapter;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ChapterVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [VoterAction::EDIT, VoterAction::DELETE])
            && $subject instanceof \App\Entity\Chapter;
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
            case VoterAction::EDIT:
                return $this->canHandle($subject, $user);

            case VoterAction::DELETE:
                return $this->canHandle($subject, $user);
        }

        return false;
    }

    private function canHandle(Chapter $chapter, User $user): bool
    {
        return VoterAction::isAuthor($chapter->getSynopsis(), $user);
    }
}
