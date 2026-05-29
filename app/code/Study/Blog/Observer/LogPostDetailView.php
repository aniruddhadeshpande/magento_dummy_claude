<?php
/**
 * Observer that logs a blog post detail page view.
 */
declare(strict_types=1);
namespace Study\Blog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * Writes an info log entry containing the request parameters whenever a blog
 * post detail page is viewed (event: blog_study_post_detail).
 */
class LogPostDetailView implements ObserverInterface
{

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $request = $observer->getData('request');
        $this->logger->info(
            'blog post detail viewed',
            [
                'params' => $request->getParams(),
                ]
        );
    }
}
