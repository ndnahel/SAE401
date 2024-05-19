<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleListener
{
	/**
	 * @var Security
	 */
	private Security $security;
	
	/**
	 * @param Security $security
	 */
	public function __construct(Security $security)
	{
		$this->security = $security;
	}
	
	public function onKernelRequest(RequestEvent $event): void
	{
		$request = $event->getRequest();
		
		/** @var User|null $user */
		$user = $this->security->getUser();
		
		if ($user) {
			$request->setLocale($user->getLang());
		}
	}
}
