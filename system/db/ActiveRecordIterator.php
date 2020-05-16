<?php


namespace system\db;


use Iterator;

/**
 * Class ActiveRecordIterator
 * @package system\db
 */
class ActiveRecordIterator implements Iterator
{
    /**
     * @var string
     */
    private string $class;

    /**
     * @var array
     */
    private array $condition;

    /**
     * @var int
     */
    private int $key = 0;

    private ?ActiveRecord $currentModel;

    /**
     * ActiveRecordIterator constructor.
     * @param string $class
     * @param array $condition
     */
    public function __construct(string $class, array $condition)
    {
        $this->class = $class;
        $this->condition = $condition;
        $this->key = 0;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->currentModel;
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $currentModel = $this->currentModel;
        $condition = $this->condition;
        $condition['expressions'][] = $currentModel->getNamePrimaryKey() . ' > ' . $currentModel->{$currentModel->getNamePrimaryKey()};
        $this->currentModel = $this->class::find($condition);
        $this->key++;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->current() !== null;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->currentModel = $this->class::find($this->condition);
    }
}