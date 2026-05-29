<?php
declare(strict_types=1);

namespace Study\InventoryFulfillment\Test\Unit\Controller\Index;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use PHPUnit\Framework\TestCase;
use Study\InventoryFulfillment\Controller\Index\Post;

class PostTest extends TestCase
{
    public function testExecuteReturnsSuccessJson(): void
    {
        $json = $this->createMock(Json::class);
        $json->expects($this->once())
            ->method('setData')
            ->with(['success' => true])
            ->willReturnSelf();

        $jsonFactory = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $jsonFactory->expects($this->once())->method('create')->willReturn($json);

        $controller = new Post($jsonFactory);

        $this->assertSame($json, $controller->execute());
    }
}
