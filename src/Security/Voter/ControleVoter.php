<?php

namespace App\Security\Voter;

use App\Entity\Controle;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\User\UserInterface;

final class ControleVoter extends Voter
{
    public const EDIT = 'CONTROLE_EDIT';
    public const VIEW = 'CONTROLE_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Controle;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        /** @var Controle $controle */
        $controle = $subject;

        switch ($attribute) {
            case self::EDIT:
                return in_array('ROLE_ENSEIGNANT', $user->getRoles()) && $controle->getEnseignementId()->getProfesseurId() === $user;
            case self::VIEW:
                if (in_array('ROLE_ENSEIGNANT', $user->getRoles()) && $controle->getEnseignementId()->getProfesseurId() === $user) {
                    return true;
                }
                if ($controle->getEtudiantId() === $user) {
                    return true;
                }
                return false;
        }

        return false;
    }
}
