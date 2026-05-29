<?php
declare(strict_types=1);

namespace Study\Blog\Test\Unit\Model\ResourceModel;

use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testResourceModelIsNotUnitTestable(): void
    {
        $this->markTestSkipped(
            'Study\\Blog\\Model\\ResourceModel\\Post only overrides the protected '
            . '_construct() to call _init(MAIN_TABLE, ID_FIELD_NAME); it has no public '
            . 'behaviour and instantiation requires the DB resource/connection stack. '
            . 'Covered by integration tests instead.'
        );
    }
}
