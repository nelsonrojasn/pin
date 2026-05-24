<?php
/**
 * QueryBuilder
 * Pequeño constructor de consultas SELECT para uso opcional.
 */
class QueryBuilder
{
    protected string $_table;
    protected string $_columns = '*';
    protected string $_conditions = '';
    protected string $_joins = '';
    protected string $_group = '';
    protected string $_having = '';
    protected string $_orderBy = '';
    protected string $_limit = '';

    public function __construct(string $table)
    {
        $this->_table = $table;
    }

    public function columns(string $columns): self
    {
        $this->_columns = $columns;
        return $this;
    }

    public function where(string $condition): self
    {
        return $this->appendCondition('AND', $condition);
    }

    public function orWhere(string $condition): self
    {
        return $this->appendCondition('OR', $condition);
    }

    protected function appendCondition(string $operator, string $condition): self
    {
        if ($this->_conditions === '') {
            $this->_conditions = $condition;
        } else {
            $this->_conditions .= ' ' . $operator . ' ' . $condition;
        }

        return $this;
    }

    public function join(string $join): self
    {
        $this->_joins .= $join . ' ';
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->_limit = 'LIMIT ' . $limit;
        return $this;
    }

    public function group(string $group): self
    {
        $this->_group = 'GROUP BY ' . $group;
        return $this;
    }

    public function having(string $having): self
    {
        $this->_having = 'HAVING ' . $having;
        return $this;
    }

    public function orderBy(string $orderBy): self
    {
        $this->_orderBy = 'ORDER BY ' . $orderBy;
        return $this;
    }

    public function __toString(): string
    {
        $sql = 'SELECT ' . $this->_columns . ' FROM ' . $this->_table;

        if ($this->_conditions !== '') {
            $sql .= ' WHERE ' . $this->_conditions;
        }

        if ($this->_joins !== '') {
            $sql .= ' ' . trim($this->_joins);
        }
        if ($this->_group !== '') {
            $sql .= ' ' . $this->_group;
        }
        if ($this->_having !== '') {
            $sql .= ' ' . $this->_having;
        }
        if ($this->_orderBy !== '') {
            $sql .= ' ' . $this->_orderBy;
        }
        if ($this->_limit !== '') {
            $sql .= ' ' . $this->_limit;
        }

        return $sql;
    }
}
