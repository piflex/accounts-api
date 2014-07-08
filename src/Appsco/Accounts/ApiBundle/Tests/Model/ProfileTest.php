<?php

namespace Appsco\Accounts\ApiBundle\Tests\Model;

use Appsco\Accounts\ApiBundle\Model\Profile;

class ProfileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldImplementSerializable()
    {
        $this->assertTrue(is_subclass_of(
            'Appsco\Accounts\ApiBundle\Model\Profile',
            'Serializable'
        ));
    }


    /**
     * @test
     */
    public function shouldDeserialize()
    {
        $profile = new Profile();
        $profile->setId($expectedId = 123)
            ->setEmail($expectedEmail = 'email@example.com')
            ->setFirstName($expectedFirstName = 'first name')
            ->setLastName($expectedLastName = 'last name')
            ->setLocale($expectedLocale = 'no')
            ->setCountry($expectedCountry = 'Norway')
            ->setPictureUrl($expectedPicture = 'http://placehold.it/50x50')
        ;

        /** @var Profile $other */
        $other = unserialize(serialize($profile));

        $this->assertInstanceOf('Appsco\Accounts\ApiBundle\Model\Profile', $other);
        $this->assertEquals($expectedId, $other->getId());
        $this->assertEquals($expectedEmail, $other->getEmail());
        $this->assertEquals($expectedFirstName, $other->getFirstName());
        $this->assertEquals($expectedLastName, $other->getLastName());
        $this->assertEquals($expectedLocale, $other->getLocale());
        $this->assertEquals($expectedCountry, $other->getCountry());
        $this->assertEquals($expectedPicture, $other->getPictureUrl());
    }

} 