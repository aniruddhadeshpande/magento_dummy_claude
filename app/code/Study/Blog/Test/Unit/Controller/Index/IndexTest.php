<?php
declare(strict_types=1);

namespace Study\Blog\Test\Unit\Controller\Index;

use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\ForwardFactory;
use PHPUnit\Framework\TestCase;
use Study\Blog\Controller\Index\Index;

class IndexTest extends TestCase
{
    public function testExecuteForwardsToPostList(): void
    {
        $forward = $this->createMock(Forward::class);
        $forward->expects($this->once())->method('setController')->with('post')->willReturnSelf();
        $forward->expects($this->once())->method('forward')->with('list')->willReturnSelf();

        $forwardFactory = $this->getMockBuilder(ForwardFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $forwardFactory->method('create')->willReturn($forward);

        $controller = new Index($forwardFactory);
        $this->assertSame($forward, $controller->execute());
    }
}
