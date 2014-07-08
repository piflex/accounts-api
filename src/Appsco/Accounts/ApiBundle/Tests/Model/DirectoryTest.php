<?php

namespace Appsco\Accounts\ApiBundle\Tests\Model;

use Appsco\Accounts\ApiBundle\Model\Directory;

class DirectoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldImplementSerializable()
    {
        $this->assertTrue(is_subclass_of(
            'Appsco\Accounts\ApiBundle\Model\Directory',
            'Serializable'
        ));
    }

    /**
     * @test
     */
    public function shouldDeserialize()
    {
        $directory = new Directory();
        $directory->setId($expectedId = 123)
            ->setName($expectedName = 'directory name');

        /** @var Directory $other */
        $other = unserialize(serialize($directory));

        $this->assertInstanceOf('Appsco\Accounts\ApiBundle\Model\Directory', $other);
        $this->assertEquals($expectedId, $other->getId());
        $this->assertEquals($expectedName, $other->getName());
    }

} 