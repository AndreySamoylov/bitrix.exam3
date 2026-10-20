<?php

namespace Academy\InvestmentProject\History;

use ArrayIterator;
use IteratorAggregate;

/**
 * @template-implements IteratorAggregate<int, Entry>
 */
final class Collection implements IteratorAggregate
{
    private array $items = [];

    public function __construct(Entry ...$items)
    {
        foreach ($items as $item) {
            $this->insert($item);
        }
    }

    public function insert(Entry $item): void
    {
        $this->items[$item->id] = $item;
    }

    public function map(callable $mapper): array
    {
        return array_map($mapper, $this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}