<?php

namespace Appsco\Accounts\ApiBundle\Security\Http\Firewall\RelyingParty;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

interface RelyingPartyInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function supports(Request $request);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \InvalidArgumentException if cannot manage the Request
     * @return \Symfony\Component\HttpFoundation\Response|TokenInterface|null
     */
    public function manage(Request $request);

} 