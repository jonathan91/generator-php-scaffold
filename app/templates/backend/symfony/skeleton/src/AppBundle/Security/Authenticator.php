<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;

class Authenticator implements SimpleFormAuthenticatorInterface
{
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface::createToken()
	 */
	public function createToken(Request $request, $username, $password, $providerKey)
	{
		
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\Authentication\SimpleAuthenticatorInterface::authenticateToken()
	 */
	public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
	{
		
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\Authentication\SimpleAuthenticatorInterface::supportsToken()
	 */
	public function supportsToken(TokenInterface $token, $providerKey)
	{
		
	}
}
