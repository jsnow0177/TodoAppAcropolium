<?php
namespace Puppy\Tests\DI;

use PHPUnit\Framework\TestCase;
use Puppy\DI\Container;
use Puppy\DI\FreshInstanceException;
use Puppy\DI\NotFoundException;
use Puppy\DI\OverrideExistentDefinitionException;

class ClassForTestingContainer{

    public $a;
    public $b;
    public $c;

    public function __construct($a, $b, $c){
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

}

class ContainerTest extends TestCase{

    /**
     * @var Container
     */
    private $container;

    public function setUp(): void
    {
        $this->container = new Container();
    }

    public function testWillContainerReturnSameObject(){
        $stdObj = new \stdClass();

        $this->container->Set('test', $stdObj);

        $this->assertSame($stdObj, $this->container->Get('test'));
    }

    public function testContainerWillThrowExceptionWhenTryingToGetNonExistentObject(){
        $this->expectException(NotFoundException::class);
        $this->container->Get('test');
    }

    public function testContainerWillThrowExceptionWhenTryingToGetSingleInstanceDefinitionObject(){
        $stdObj = new \stdClass();
        $this->container->Set('test', $stdObj);

        $this->expectException(FreshInstanceException::class);
        $fresh = $this->container->Get('test', false);
    }

    public function testContainerWillMakeInstanceByCallable(){
        $stdObject = new \stdClass();
        $this->container->Set('test', function($container) use($stdObject){
            return $stdObject;
        });

        $fresh = $this->container->Get('test');

        $this->assertSame($stdObject, $fresh);
    }

    public function testContainerWillReturnSameSharedInstanceOnConsequenceCalls(){
        $this->container->Set('test', function($container){
            return new \stdClass();
        });

        $object1 = $this->container->Get('test');
        $object2 = $this->container->Get('test');

        $this->assertSame($object1, $object2);
    }

    public function testContainerWillReturnNewInstanceWhenTryingToGetNotSharedObjectOnConsequenceCalls(){
        $this->container->Set('test', function($container){
            return new \stdClass();
        });

        $object1 = $this->container->Get('test', false);
        $object2 = $this->container->Get('test', false);

        $this->assertNotSame($object1, $object2);
    }

    public function testContainerWillConstructNewClassObjectAndPassArgumentsToConstructor(){
        $this->container->Set('test', ClassForTestingContainer::class, [
            1, 2, 3
        ]);

        $object1 = $this->container->Get('test');

        $this->assertSame(1, $object1->a);
        $this->assertSame(2, $object1->b);
        $this->assertSame(3, $object1->c);
    }

    public function testContainerHas(){
        $this->container->Set('test', ClassForTestingContainer::class, [1, 2, 3]);

        $this->assertTrue($this->container->Has('test'));
        $this->assertFalse($this->container->Has('test2'));
    }

    public function testContainerWillThrowExceptionWhenTryingToAddExistentDefinitionAndSetOverrideToFalse(){
        $this->expectException(OverrideExistentDefinitionException::class);
        $this->container->Set('test', ClassForTestingContainer::class, [1,2,3]);
        $this->container->Set('test', ClassForTestingContainer::class, [1,2,3]);
    }

    public function testContainerWillThrowExceptionWhenSpecifiedInvalidDefinition(){
        $this->expectException(\InvalidArgumentException::class);
        $this->container->Set('test', 123);
    }

}