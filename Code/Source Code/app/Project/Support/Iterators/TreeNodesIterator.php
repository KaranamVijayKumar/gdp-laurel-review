<?php
/**
 * File: TreeNodesIterator.php
 * Created: 06-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Iterators;

use ArrayIterator;
use RecursiveIterator;

/**
 * Class TreeNodesIterator
 *
 * @package Project\Support\Iterators
 */
class TreeNodesIterator implements RecursiveIterator
{

    /**
     * @var ArrayIterator
     */
    private $nodes;

    /**
     * @param array $nodes
     */
    public function __construct(array $nodes)
    {

        $this->nodes = new ArrayIterator($nodes);
    }

    /**
     * @return mixed
     */
    public function current()
    {

        return $this->nodes->current();
    }

    /**
     * @return TreeNodesIterator
     */
    public function getChildren()
    {

        return new TreeNodesIterator($this->nodes->current()->getChildren());
    }

    /**
     * @return ArrayIterator
     */
    public function getInnerIterator()
    {

        return $this->nodes;
    }

    /**
     * @return mixed
     */
    public function hasChildren()
    {

        return $this->nodes->current()->hasChildren();
    }

    /**
     * @return mixed
     */
    public function key()
    {

        return $this->nodes->key();
    }

    /**
     *
     */
    public function next()
    {

        $this->nodes->next();
    }

    /**
     *
     */
    public function rewind()
    {

        $this->nodes->rewind();
    }

    /**
     * @return bool
     */
    public function valid()
    {

        return $this->nodes->valid();
    }
}
