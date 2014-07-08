<?php

namespace Appsco\Accounts\ApiBundle\Tests\Security\Core\Authentication\Token;

use Appsco\Accounts\ApiBundle\Model\Directory;
use Appsco\Accounts\ApiBundle\Model\Profile;
use Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken;
use Symfony\Component\Security\Core\User\User;

class AppscoTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldExtendAbstractToken()
    {
        $this->assertTrue(is_subclass_of(
            'Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken',
            'Symfony\Component\Security\Core\Authentication\Token\AbstractToken'
        ));
    }

    /**
     * @test
     */
    public function shouldDeserialize()
    {
        $profile = new Profile();
        $profile
            ->setId($expectedProfileId = 123)
            ->setEmail($expectedEmail = 'email@example.com')
            ->setFirstName($expectedFirstName = 'first_name')
            ->setLastName($expectedLastName = 'last_name')
            ->setDirectory($expectedDirectory = ((new Directory())
                ->setId($expectedDirId = 456)
                ->setName($expectedDirName = 'dir_name'))
            )
        ;

        $expectedRoles = array('ROLE_USER', 'ROLE_CUSTOM');
        $expectedAccessToken = 'access_token';
        $expectedIdToken = 'id_token';

        $user =  new User($expectedUsername = 'username', '', $expectedRoles);

        $token = new AppscoToken($user, $expectedRoles, $profile, $expectedAccessToken, $expectedIdToken);

        /** @var AppscoToken $other */
        $other = unserialize(serialize($token));

        $this->assertInstanceOf('Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken', $other);
        $this->assertNotNull($other->getUser());
        $this->assertEquals($expectedUsername, $other->getUser()->getUsername());
        $this->assertEquals($expectedUsername, $other->getUsername());

        $this->assertEquals($expectedAccessToken, $other->getAccessToken());
        $this->assertEquals($expectedIdToken, $other->getIdToken());

        $this->assertInstanceOf('Appsco\Accounts\ApiBundle\Model\Profile', $other->getProfile());
        $this->assertEquals($expectedProfileId, $other->getProfile()->getId());
        $this->assertEquals($expectedEmail, $other->getProfile()->getEmail());
        $this->assertEquals($expectedFirstName, $other->getProfile()->getFirstName());
        $this->assertEquals($expectedLastName, $other->getProfile()->getLastName());

        $this->assertInstanceOf('Appsco\Accounts\ApiBundle\Model\Directory', $other->getProfile()->getDirectory());
        $this->assertEquals($expectedDirId, $other->getProfile()->getDirectory()->getId());
        $this->assertEquals($expectedDirName, $other->getProfile()->getDirectory()->getName());
    }

}