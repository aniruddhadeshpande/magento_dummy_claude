<?php
/**
 * Data patch: seeds the initial blog post.
 */
declare(strict_types=1);

namespace Study\Blog\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;
use Study\Blog\Api\PostRepositoryInterface;
use Study\Blog\Model\PostFactory;

/**
 * Inserts the first sample blog post ("An awesome post") during module installation.
 */
class PopulateBlogPosts implements DataPatchInterface
{

    /**
     * @param ModuleDataSetupInterface $setup
     * @param PostFactory $postFactory
     * @param PostRepositoryInterface $postRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private ModuleDataSetupInterface $setup,
        private PostFactory $postFactory,
        private PostRepositoryInterface $postRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->setup->startSetup();
        $post = $this->postFactory->create();
        $post->setData(
            [
                'title' => 'An awesome post',
                'content' => 'This is totally awesome!',
            ]
        );
        try {
            $this->postRepository->save($post);
        } catch (LocalizedException $exception) {
            $this->logger->error($exception->getMessage());
        }
        $this->setup->endSetup();
    }
}
