<?php

namespace Academy\InvestmentProject\Integration\Intranet\Employee;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @template-implements IteratorAggregate<int, Employee>
 */
final class Collection implements IteratorAggregate
{
    private array $items = [];

    public function __construct(Employee ...$items)
    {
        foreach ($items as $item) {
            $this->insert($item);
        }
    }

    public function insert(Employee $item): void
    {
        $this->items[$item->id] = $item;
    }

    public function has(int $id): bool
    {
        return isset($this->items[$id]);
    }

    public function get(int $id): ?Employee
    {
        return $this->items[$id] ?? null;
    }

    public function filter(callable $filter): Collection
    {
        $collection = new Collection();
        $collection->items = array_filter($this->items, $filter);

        return $collection;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}