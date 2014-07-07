<?php

namespace Appsco\Accounts\ApiBundle\Security\Http\Firewall\RelyingParty;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CompositeRelyingParty implements RelyingPartyInterface
{
    /** @var RelyingPartyInterface[] */
    protected $children = array();


    /**
     * @param RelyingPartyInterface $relyingParty
     * @return $this|CompositeRelyingParty
     */
    public function add(RelyingPartyInterface $relyingParty)
    {
        $this->children[] = $relyingParty;

        return $this;
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        foreach ($this->children as $relyingParty) {
            if ($relyingParty->supports($request)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \InvalidArgumentException if cannot manage the Request
     * @return \Symfony\Component\HttpFoundation\Response|TokenInterface|null
     */
    public function manage(Request $request)
    {
        foreach ($this->children as $relyingParty) {
            if ($relyingParty->supports($request)) {

                return $relyingParty->manage($request);
            }
        }

        throw new \InvalidArgumentException('Unsupported request');
    }

}