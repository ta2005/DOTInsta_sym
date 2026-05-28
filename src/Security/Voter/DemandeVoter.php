<?php

namespace App\Security\Voter;

user App\Enum\RequeteEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class DemandeVoter extends Voter
{
    public const EDIT = 'DEMANDE_EDIT';
    public const VIEW = 'DEMANDE_VIEW';
    public const DELETE = 'DEMANDE_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW,self::DELETE])
            && $subject instanceof \App\Entity\Demande;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            $vote?->addReason('The user must be logged in to access this resource.');

            return false;
        }
        $demande = $subject;

        if (in_array('ROLE_ADMIN',$user->getRoles())) return true;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                // logic to determine if the user can EDIT
                // return true or false
                return $user === $demande->getUserId();
                break;
            case self::EDIT:
            case self::DELETE:
                return $user === $demande->getUserId() && $demande->getStatut()===RequeteEnum::EN_ATTENTE;
        }

        return false;
    }
}
