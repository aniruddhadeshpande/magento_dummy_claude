<?php
/**
 * Blog post list controller action.
 */

namespace Study\Blog\Controller\Post;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Renders the blog post listing page at blog/post/list.
 */
class ListAction implements HttpGetActionInterface
{

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        private PageFactory $pageFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute():Page
    {
        return $this->pageFactory->create();
    }
}
