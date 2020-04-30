<?php
/**
 * File: RecursiveListIterator.php
 * Created: 06-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Iterators;

use RecursiveIteratorIterator;

/**
 * Class RecursiveListIterator
 *
 * @package Project\Support\Iterators
 */
class RecursiveListIterator extends RecursiveIteratorIterator
{

    /**
     * @var ListDecorator
     */
    private $decorator;

    /**
     * @var
     */
    private $elements;

    /**
     * @param \Traversable $iterator
     * @param int          $mode
     * @param int          $flags
     */
    public function __construct($iterator, $mode = RecursiveIteratorIterator::SELF_FIRST, $flags = 0)
    {

        parent::__construct($iterator, $mode, $flags);
    }

    /**
     * @param ListDecorator $decorator
     */
    public function addDecorator(ListDecorator $decorator)
    {

        $this->decorator = $decorator;
    }

    /**
     *
     */
    public function beginChildren()
    {

        $this->event('beginChildren');
    }

    /**
     * @param $name
     */
    private function event($name)
    {

        $callback = array($this->decorator, $name);
        is_callable($callback) && call_user_func($callback);
    }

    /**
     *
     */
    public function beginElement()
    {

        $this->event('beginElement');
    }

    /**
     *
     */
    public function beginIteration()
    {

        $this->event('beginIteration');
    }

    /**
     *
     */
    public function endChildren()
    {

        $this->testEndElement();
        $this->event('endChildren');
    }

    /**
     * @param int $depthOffset
     */
    private function testEndElement($depthOffset = 0)
    {

        $depth = $this->getDepth() + $depthOffset;
        if (!isset($this->elements[$depth])) {
            $this->elements[$depth] = 0;
        }
        $this->elements[$depth] && $this->event('endElement');

    }

    /**
     *
     */
    public function endIteration()
    {

        $this->testEndElement();
        $this->event('endIteration');
    }

    /**
     *
     */
    public function nextElement()
    {

        $this->testEndElement();
        $this->event('{nextElement}');
        $this->event('beginElement');
        $this->elements[$this->getDepth()] = 1;
    }
}
