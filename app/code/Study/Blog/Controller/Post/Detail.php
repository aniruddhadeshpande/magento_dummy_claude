<?php
/**
 * Blog post detail controller action.
 */
declare(strict_types=1);

namespace Study\Blog\Controller\Post;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Renders a single blog post detail page at blog/post/detail and fires
 * the blog_study_post_detail event so observers (e.g. analytics) can react.
 */
class Detail implements HttpGetActionInterface
{

    /**
     * @param PageFactory $pageFactory
     * @param RequestInterface $request
     * @param EventManagerInterface $manager
     */
    public function __construct(
        private PageFactory $pageFactory,
        private RequestInterface $request,
        private EventManagerInterface $manager,
    ) {
    }
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->manager->dispatch(
            'blog_study_post_detail',
            ['request' => $this->request]
        );

        return $this->pageFactory->create();
    }
}
