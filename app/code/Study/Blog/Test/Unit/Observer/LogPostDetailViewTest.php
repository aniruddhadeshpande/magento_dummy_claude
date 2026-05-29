<?php
declare(strict_types=1);

namespace Study\Blog\Test\Unit\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Study\Blog\Observer\LogPostDetailView;

class LogPostDetailViewTest extends TestCase
{
    public function testExecuteLogsRequestParams(): void
    {
        $params = ['id' => '7'];

        $request = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $request->method('getParams')->willReturn($params);

        $observer = $this->createMock(Observer::class);
        $observer->method('getData')->with('request')->willReturn($request);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info')
            ->with('blog post detail viewed', ['params' => $params]);

        $observer2 = new LogPostDetailView($logger);
        $observer2->execute($observer);
    }
}
