<?php
/**
 * Blog module root index controller.
 */
declare(strict_types=1);
namespace Study\Blog\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;

/**
 * Forwards requests from blog/index/index to blog/post/list,
 * making /blog/ behave as the post listing page.
 */
class Index implements HttpGetActionInterface
{
    /**
     * @param ForwardFactory $forwardFactory
     */
    public function __construct(
        private ForwardFactory $forwardFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(): Forward
    {
        $forward = $this->forwardFactory->create();
        $forward->setController('post')->forward('list');
        return $forward;
    }
}
