<?php
declare(strict_types=1);

namespace Study\Blog\Test\Unit\Model\ResourceModel\Post;

use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testCollectionIsNotUnitTestable(): void
    {
        $this->markTestSkipped(
            'Study\\Blog\\Model\\ResourceModel\\Post\\Collection only overrides the '
            . 'protected _construct() to call _init(Post::class, ResourcePost::class); '
            . 'it has no public behaviour and instantiation requires the full DB '
            . 'collection/connection stack. Covered by integration tests instead.'
        );
    }
}
