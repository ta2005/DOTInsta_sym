<?php

namespace App\Security\Voter;

use App\Entity\Reclamation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\User\UserInterface;

final class ReclamationVoter extends Voter
{
    public const EDIT = 'RECLAMATION_EDIT';
    public const VIEW = 'RECLAMATION_VIEW';
    public const DELETE = 'RECLAMATION_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Reclamation;
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

        /** @var Reclamation $reclamation */
        $reclamation = $subject;
        $etudiant = $reclamation->getControleId()->getEtudiantId();
        $professeur = $reclamation->getControleId()->getEnseignementId()->getProfesseurId();

        switch ($attribute) {
            case self::VIEW:
                return $user === $etudiant || $user === $professeur;
            case self::EDIT:
                return $user === $etudiant || $user === $professeur;
            case self::DELETE:
                return $user === $etudiant;
        }

        return false;
    }
}
