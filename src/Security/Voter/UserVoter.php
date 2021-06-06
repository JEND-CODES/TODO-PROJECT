<?php

namespace App\Security\Voter;

use App\Entity\Usertodo;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    const UPDATE = 'update';

	const DELETE = 'delete';

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

	protected function supports($attribute, $subject): bool
	{
		if (!in_array($attribute, [self::UPDATE, self::DELETE])) {
			return false;
		}

		if (!$subject instanceof Usertodo) {
			return false;
		}

		return true;
	}

	protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
	{
		$user = $token->getUser();

		if (!$user instanceof Usertodo) {
			return false;
		}

        /** @var Usertodo $user */
		$user = $subject;

		switch ($attribute) {
            case self::UPDATE:
                return $this->checkAuthorization($user);
			case self::DELETE:
                return $this->checkAuthorization($user);
		}

		throw new \LogicException('This code should not be reached!');
	}

    private function checkAuthorization(Usertodo $user): bool
	{
        if (($this->security->getUser()->getId() === $user->getId()) || 
            (!$this->security->isGranted('ROLE_SUPER_ADMIN') && $user->getRole() === 'ROLE_ANONYMOUS') || 
            (!$this->security->isGranted('ROLE_SUPER_ADMIN') && $user->getRole() === 'ROLE_SUPER_ADMIN'))
        {
            return false;

        } 

        return true;


	}
    

}
