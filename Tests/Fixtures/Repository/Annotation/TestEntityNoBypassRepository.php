<?php

namespace Ordermind\LogicalAuthorizationDoctrineORMBundle\Tests\Fixtures\Repository\Annotation;

/**
 * TestEntityNoBypassRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TestEntityNoBypassRepository extends \Doctrine\ORM\EntityRepository
{
    public function customMethod()
    {
        return $this->findAll();
    }
}
