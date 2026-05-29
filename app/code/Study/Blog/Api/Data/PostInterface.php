<?php
declare(strict_types=1);
namespace Study\Blog\Api\Data;

/**
 * Blog post interface.
 * @api
 * @since 1.0.0
 */
interface PostInterface
{
    public const ID = 'id';
    public const TITLE = 'title';
    public const CONTENT = 'content';
    public const CREATED_AT = 'created_at';

    /**
     *  Get primary key of blog
     *
     * @return int
     */
    public function getId();

    /**
     *  Set primary key of blog
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     *  Get blog title
     *
     * @return string
     */
    public function getTitle();

    /**
     *  Set blog title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     *  Get blog content
     *
     * @return string
     */
    public function getContent();

    /**
     *  Set  blog content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     *  Get created at timestamp
     *
     * @return string
     */
    public function getCreatedAt();
}
