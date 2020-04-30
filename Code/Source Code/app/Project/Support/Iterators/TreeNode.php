<?php
/**
 * File: TreeNode.php
 * Created: 06-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Iterators;

/**
 * Class TreeNode
 *
 * @package Project\Support\Iterators
 */
class TreeNode
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $element
     */
    public function __construct(array $element)
    {

        $this->data = $element;

        // filter out the children that have no access
        if ($this->hasChildren()) {
            foreach ($this->data['children'] as $key => $item) {

                if (array_key_exists('access', $item) && !has_access($item['access'])) {
                    unset($this->data['children'][$key]);
                }
            }
        }
    }

    /**
     * @return array of child TreeNode elements
     */
    public function getChildren()
    {

        $children = $this->hasChildren() ? $this->data['children'] : array();

        foreach ($children as $key => &$element) {

            $element = new TreeNode($element);
        }
        unset($element);

        return $children;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {

        return isset($this->data['children']) && count($this->data['children']);
    }

    /**
     * @return mixed
     */
    public function getName()
    {

        return $this->data['name'];
    }

    /**
     * @return mixed
     */
    public function getId()
    {

        return $this->data['id'];
    }
}
