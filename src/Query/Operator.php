<?php

namespace spkm\Ciphr\Query;

use InvalidArgumentException;

/**
 * The conditions Ciphr's query string understands, as ?Property=[code]value.
 */
enum Operator: string
{
    case Equal = 'eq';
    case NotEqual = 'ne';
    case GreaterThan = 'gt';
    case GreaterOrEqual = 'ge';
    case LessThan = 'lt';
    case LessOrEqual = 'le';
    case In = 'in';
    case NotIn = 'nin';
    case Range = 'range';
    case StartsWith = 'sw';
    case EndsWith = 'ew';
    case Contains = 'c';
    case NotContains = 'nc';
    case Null = 'null';
    case NotNull = 'notnull';

    /**
     * The operator for a Ciphr code or a familiar symbol: '=', '!=', '<>', '>', '>=', '<', '<=', 'not in', 'between',
     * 'starts with', 'ends with', 'contains', 'not contains', 'is null' and 'is not null' are accepted too.
     */
    public static function parse(self|string $operator): self
    {
        if ($operator instanceof self) {
            return $operator;
        }

        $normalised = strtolower(trim($operator));

        return self::tryFrom($normalised) ?? match ($normalised) {
            '=', '==' => self::Equal,
            '!=', '<>' => self::NotEqual,
            '>' => self::GreaterThan,
            '>=' => self::GreaterOrEqual,
            '<' => self::LessThan,
            '<=' => self::LessOrEqual,
            'not in' => self::NotIn,
            'between' => self::Range,
            'starts with', 'startswith' => self::StartsWith,
            'ends with', 'endswith' => self::EndsWith,
            'contains' => self::Contains,
            'not contains', 'notcontains' => self::NotContains,
            'is null' => self::Null,
            'is not null' => self::NotNull,
            default => throw new InvalidArgumentException('Ciphr has no "'.$operator.'" condition. Use one of =, !=, >, >=, <, <=, in, nin, range, sw, ew, c, nc, null or notnull.'),
        };
    }

    public function takesList(): bool
    {
        return $this === self::In || $this === self::NotIn;
    }

    public function takesRange(): bool
    {
        return $this === self::Range;
    }

    public function takesNoValue(): bool
    {
        return $this === self::Null || $this === self::NotNull;
    }
}
