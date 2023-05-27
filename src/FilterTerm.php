<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;


abstract class FilterTerm
{
    public abstract function isIdOnly(): bool;
    public abstract function __invoke(EntryRef $entry): string;
    public abstract function propNames(): array;

    public static function value(string $value): FilterTerm
    {
        return new class($value) extends FilterTerm
        {
            public function __construct(private string $value)
            {
            }
            public function isIdOnly(): bool
            {
                return true;
            }
            public function __invoke(EntryRef $entry): string
            {
                return $this->value;
            }
            public function propNames(): array
            {
                return [];
            }
        };
    }
    public static function property(string $propName): FilterTerm
    {
        return new class($propName) extends FilterTerm
        {
            public function __construct(private string $propName)
            {
            }
            public function isIdOnly(): bool
            {
                return true;
            }
            public function __invoke(EntryRef $entry): string
            {
                return $entryRef->get($this->propName);
            }
            public function propNames(): array
            {
                return [$this->propName];
            }
        };
    }
}
