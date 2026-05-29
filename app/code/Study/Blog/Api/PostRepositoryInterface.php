<?php
declare(strict_types=1);
namespace Study\Blog\Api;

use Study\Blog\Api;
use Study\Blog\Api\Data\PostInterface;

/**
 * Blog post CRUD interface.
 * @api
 * @since 1.0.0
 */
interface PostRepositoryInterface
{
    /**
     *  Get post by blog id
     *
     * @param int $id
     * @return \Study\Blog\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $id): PostInterface;

    /**
     *  Save blog post
     *
     * @param \Study\Blog\Api\Data\PostInterface $post
     * @return \Study\Blog\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(PostInterface $post): PostInterface;

    /**
     *  Delete post by id
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool;
}
