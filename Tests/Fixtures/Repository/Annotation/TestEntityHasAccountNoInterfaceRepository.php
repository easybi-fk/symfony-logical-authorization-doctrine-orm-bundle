<?php

namespace Ordermind\LogicalAuthorizationDoctrineORMBundle\Tests\Fixtures\Repository\Annotation;

/**
 * TestEntityHasAccountNoInterfaceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TestEntityHasAccountNoInterfaceRepository extends \Doctrine\ORM\EntityRepository
{
    public function customMethod()
    {
        return $this->findAll();
    }
}
