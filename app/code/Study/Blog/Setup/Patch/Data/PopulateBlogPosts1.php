<?php
/**
 * Data patch: seeds additional sample blog posts.
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
 * Inserts two additional sample blog posts ("Today is sunny", "My movie review")
 * as a follow-up to PopulateBlogPosts.
 */
class PopulateBlogPosts1 implements DataPatchInterface
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
        $posts = [
            [
                'title' => 'Today is sunny',
                'content' => 'The weather has been great all week.',
            ],
            [
                'title' => 'My movie review',
                'content' => 'I give this movie 5 out of 5 stars!',
            ],
        ];
        foreach ($posts as $postData) {
            $post = $this->postFactory->create();
            $post->setData(
                $postData
            );
            try {
                $this->postRepository->save($post);
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        $this->setup->endSetup();
    }
}
