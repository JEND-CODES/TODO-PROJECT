<?php

namespace App\Security\Voter;

use App\Entity\Tasktodo;
use App\Entity\Usertodo;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
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
		if (!in_array($attribute, [self::DELETE])) {
			return false;
		}

		if (!$subject instanceof Tasktodo) {
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

        /** @var Tasktodo $task */
		$task = $subject;

		switch ($attribute) {
			case self::DELETE:
				return $this->canDelete($task);
		}

		throw new \LogicException('This code should not be reached!');
	}

	private function canDelete(Tasktodo $task): bool
	{
        if($this->security->getUser() === $task->getUsertodo()) {

            return true;

        } else {
			
            if($this->security->isGranted('ROLE_ADMIN')){

                return true;

            }

        }

		return false;

	}
    

}
