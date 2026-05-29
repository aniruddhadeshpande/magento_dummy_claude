<?php

/**
 * POST action that accepts the shipping plan form submission.
 */

declare(strict_types=1);
namespace Study\InventoryFulfillment\Controller\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Accepts the fulfillment form POST and returns a JSON acknowledgement.
 *
 * The current implementation always responds with {"success": true}; business
 * logic for persisting the submission has not yet been implemented.
 */
class Post  implements HttpPostActionInterface
{
    /**
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        private readonly JsonFactory $jsonFactory
    ){}

    /**
     * Returns a JSON response acknowledging receipt of the shipping plan.
     *
     * TODO: behavior unclear — actual persistence/processing of posted box configurations
     * not yet implemented; verify with module owner.
     */
    public function execute(): Json
    {
        $json = $this->jsonFactory->create();
       $json->setData(['success' => true]);
       return $json;
    }
}
