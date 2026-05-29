<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Repository\MembreGroupeRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class PostVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    public const DELETE = 'POST_DELETE';
    public const CREATE = 'POST_CREATE';

    private $membreRepo;

    public function __construct(MembreGroupeRepository $membreRepo){
      $this->membreRepo=$membreRepo;
    }

    //this might become a trait
    private function isMembre($user,$groupe):bool{
       $mem = $this->membreRepo->findOneBy([
            'user_id' => $user,
            'groupe_d' => $groupe,
         ]);
         return $mem !== null;
    }


    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW,self::DELETE,self::CREATE])
            && $subject instanceof \App\Entity\Post;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            $vote?->addReason('The user must be logged in to access this resource.');

            return false;
        }
        if (in_array('ROLE_ADMIN',$user->getRoles())) return true;

        $post = $subject;
        $groupe = $post->getGroupId();

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
            case self::DELETE:
                // logic to determine if the user can EDIT
                // return true or false
                return $post->getAuteurId() === $user;

            //for both of these the user must be part of the groupe
            case self::VIEW:
            case self::CREATE:
                // logic to determine if the user can VIEW
                // return true or false
                return $this->isMembre($user,$groupe);
                break;
        }

        return false;
    }
}
