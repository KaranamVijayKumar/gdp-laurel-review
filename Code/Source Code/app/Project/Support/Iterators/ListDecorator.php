<?php
/**
 * File: ListDecorator.php
 * Created: 06-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Iterators;

/**
 * Class ListDecorator
 *
 * @package Project\Support\Iterators
 */
class ListDecorator
{

    /**
     * @var RecursiveListIterator
     */
    private $iterator;

    /**
     * @param RecursiveListIterator $iterator
     * @param array                 $selected
     */
    public function __construct(RecursiveListIterator $iterator, $selected = array())
    {

        $this->iterator = $iterator;
        $this->selected = $selected;
    }

    /**
     *
     */
    public function beginChildren()
    {

        echo '<ul class="nav nav--submenu nav--stacked">';
    }

    /**
     *
     */
    public function beginElement()
    {

        $depth = $this->iterator->getDepth();
        $attributes = ' class="';
        if (!$depth) {
            $attributes .= 'nav-item';
        }


        $id = $this->iterator->current()->getId();


        if (in_array($id, $this->selected)) {
            if ($this->iterator->current()->hasChildren() && !$depth && count($this->selected) > 1) {
                $attributes .= ' sub-selected';
            } else {
                $attributes .= ' selected';
            }
        }

        $attributes .= '"';
        echo "<li{$attributes}>";
    }

    /**
     *
     */
    public function beginIteration()
    {

    }

    /**
     *
     */
    public function endChildren()
    {

        echo "</ul>";
    }

    /**
     *
     */
    public function endElement()
    {

        echo "</li>";
    }

    /**
     *
     */
    public function endIteration()
    {

    }

    /**
     * @param int $add
     *
     * @return string
     */
    public function inset($add = 0)
    {

        return str_repeat('  ', $this->iterator->getDepth() * 2 + $add);
    }
}
