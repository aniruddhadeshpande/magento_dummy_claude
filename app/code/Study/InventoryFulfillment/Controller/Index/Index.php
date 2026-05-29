<?php

/**
 * GET action that renders the Shipping Plan page.
 */

namespace Study\InventoryFulfillment\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Renders the inventory fulfillment shipping plan page at /inventory-fulfillment/index/index.
 */
class Index implements HttpGetActionInterface
{

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        private PageFactory $pageFactory,
    ){}

    /**
     * Renders the Shipping Plan page with the title set to "Shipping Plan".
     */
    public function execute(): Page
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set(__('Shipping Plan'));
        return $page;
    }
}
