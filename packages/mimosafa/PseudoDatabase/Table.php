<?php

namespace PseudoDatabase;

class Table
{
    /**
     * Table name
     *
     * @var string
     */
    private $name;

    /**
     * Data of table
     *
     * @var array[]
     */
    private $rows = [];

    /**
     * Total count of rows
     *
     * @var int
     */
    private $total = 0;

    /**
     * Keys of table
     *
     * @var string[]
     */
    private $keys = [];

    /**
     * Value types of key
     *
     * @see Table::validate($key, $value)
     *
     * @var array{
     *      @type string ${$key} integer|string..
     * }
     */
    private $types = [];

    /**
     * Primary key of table
     *
     * @var string
     */
    private $primary;

    /**
     * Unique keys
     *
     * @var string[]
     */
    private $uniques = [];

    /**
     * Index caches
     *
     * @var array
     */
    private $indexes = [];

    /**
     * Nullable keys
     *
     * @var string[]
     */
    private $nullables = [];

    /**
     * Requesed where
     *
     * @var array[]
     */
    private $wheres = [];

    /**
     * Requested order (by)
     *
     * @var array
     */
    private $orderBy = [];

    /**
     * Is null order
     * If 'asc', last. If 'desc', first.
     * If not set depends system.
     *
     * @var string|null
     */
    private $isNullOrder;

    /**
     * Requested limit results
     *
     * @var int
     */
    private $limit = 0;

    /**
     * Requested offset results
     *
     * @var int
     */
    private $offset = 0;

    /**
     * Constructor
     *
     * @param string $tableName
     * @param array $definitions
     */
    public function __construct(string $tableName, array $definitions)
    {
        $this->name = $tableName;

        foreach ($definitions as $key => $definition) {
            $this->keys[] = $key;

            if (isset($definition['primary']) && $definition['primary'] === true) {
                $this->primary = $key;
                $this->indexes[$key] = [];
            }
            else if (isset($definition['unique']) && $definition['unique'] === true) {
                $this->uniques[] = $key;
                $this->indexes[$key] = [];
            }
            else if (isset($definition['nullable']) && $definition['nullable'] === true) {
                $this->nullables[] = $key;
            }

            $this->types[$key] = $definition['type'] ?? 'string';
        }
    }

    /**
     * Insert
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function insert(array $data): array
    {
        $row = [];
        foreach ($this->keys as $key) {
            if ($key === $this->primary) {
                continue;
            }

            if (!isset($data[$key])) {
                if (\in_array($key, $this->nullables, true)) {
                    $value = null;
                }
                else {
                    throw new \Exception('Required value of `' . $key .'` does not exist.');
                }
            }
            else {
                $value = $this->validate($key, $data[$key]);
                if ($value === null) {
                    throw new \Exception('value of `' . $key . '` must be ' . $this->types[$key] . '. Given value: ' . var_export($data[$key], true));
                }
            }

            $row[$key] = $value;
        }

        ++$this->total;
        if (isset($this->primary)) {
            $row[$this->primary] = $this->total;
        }
        return $this->rows[] = $row;
    }

    /**
     * Where
     *
     * @param mixed $key
     * @param mixed[] $params
     * @return self
     */
    public function where($key, ...$params): self
    {
        if (empty($this->wheres)) {
            $this->wheres[] = [];
            $where =& $this->wheres[0];
        }
        else {
            $where =& $this->wheres[\count($this->wheres) - 1];
        }

        if (\in_array($key, $this->keys, true)) {
            if (\count($params) === 2) {
                $where[] = [$key, $params[0], $params[1]];
            }
            else if (\count($params) === 1) {
                $where[] = [$key, '=', $params[0]];
            }
        }

        return $this;
    }

    /**
     * Update
     *
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function update(array $data): void
    {
        if (empty($this->wheres)) {
            throw new \Exception('When using ' . __METHOD__ . ', need to limit target by `where` method.');
        }

        foreach ($this->rows as $index => &$row) {
            if ($this->execWhere($index, $row)) {
                foreach ($data as $key => $value) {
                    if ($key === $this->primary) {
                        continue;
                    }

                    if ($value === null) {
                        if (!\in_array($key, $this->nullables, true)) {
                            throw new \Exception('Required value of `' . $key .'` does not exist.');
                        }
                    }
                    else {
                        $value = $this->validate($key, $value);
                        if ($value === null) {
                            throw new \Exception('value of `' . $key . '` must be ' . $this->types[$key] . '. Given value: ' . var_export($data[$key], true));
                        }
                    }

                    $row[$key] = $value;
                }
            }
        }

        $this->initRequests();
    }

    /**
     * Validate given value
     *
     * @param string $key
     * @param mixed $value
     * @return mixed|null
     */
    private function validate(string $key, $value)
    {
        $type = $this->types[$key];
        if ($type === 'string') {
            $value = \filter_var($value);
            return $value === false ? null : (string) $value;
        }
        else if ($type === 'integer') {
            $value = \filter_var($value, \FILTER_VALIDATE_INT);
            return $value === false ? null : $value;
        }

        return null;
    }

    /**
     * Limit results
     *
     * @param int $num
     * @return self
     */
    public function limit(int $num): self
    {
        if ($num > 0) {
            $this->limit = $num;
        }
        return $this;
    }

    /**
     * Offset results
     *
     * @param int $offset
     * @return self
     */
    public function offset(int $offset): self
    {
        if ($offset > 0) {
            $this->offset = $offset;
        }
        return $this;
    }

    /**
     * Order by
     *
     * @param string $key
     * @param string[] ...$params
     * @return self
     */
    public function orderBy(string $key, ...$params): self
    {
        $arguments = \array_merge([$key], $params);
        for ($i = 0; $i < count($arguments); $i = $i + 2) {
            $key = $arguments[$i];
            $order = \strtolower($arguments[$i + 1] ?? 'asc');
            if (\in_array($key, $this->keys, true) && \in_array($order, ['asc', 'desc'], true)) {
                $this->orderBy[] = [$key, $order];
            }
        }
        return $this;
    }

    /**
     * Is null order
     *
     * @param string $order asc|desc
     * @return self
     */
    public function isNull(string $order): self
    {
        $order = \strtolower($order);
        if (\in_array($order, ['asc', 'desc'], true)) {
            $this->isNullOrder = $order;
        }
        return $this;
    }

    /**
     * Get single result
     *
     * @return array|null
     */
    public function first(): ?array
    {
        $results = $this->get();
        return $results ? $results[0] : null;
    }

    /**
     * Get
     *
     * @return array
     */
    public function get(): array
    {
        if (empty($this->wheres)) {
            $results = $this->rows;
        }
        else {
            $results = [];
            foreach ($this->rows as $index => $row) {
                if ($row = $this->rowFilter($index, $row)) {
                    $results[] = $row;
                }
            }
        }

        if (!empty($results)) {
            if (($this->offset > 0) || ($this->limit > 0)) {
                $limit = $this->limit ?: null;
                $results = \array_slice($results, $this->offset, $limit);
            }
        }

        if (!empty($results) && !empty($this->orderBy)) {
            $orderBys = \array_reverse($this->orderBy);
            foreach ($orderBys as $orderBy) {
                $key = $orderBy[0];
                $order = \strtolower($orderBy[1]);
                if (!\in_array($order, ['asc', 'desc'], true)) {
                    continue;
                }
                $valueType = $this->types[$key];
                $sortNull = \in_array($key, $this->nullables, true) && isset($this->isNullOrder);
                // var_dump(\array_column($results, $key));
                \usort($results, function ($result1, $result2) use ($key, $order, $valueType, $sortNull) {
                    $value1 = $result1[$key];
                    $value2 = $result2[$key];
                    if ($sortNull) {
                        if ($value1 === null) {
                            return $this->isNullOrder === 'asc' ? 1 : -1;
                        }
                        else if ($value2 === null) {
                            return $this->isNullOrder === 'asc' ? -1 : 1;
                        }
                    }
                    if ($valueType === 'integer') {
                        $cmp = $value1 - $value2;
                        return $order === 'asc' ? $cmp : $cmp * -1;
                    }
                    else if ($valueType === 'string') {
                        /**
                         * @todo No test yet
                         */
                        $cmp = \strnatcmp($value1, $value2);
                        return $order === 'asc' ? $cmp : $cmp * -1;
                    }
                    return 0;
                });
                // var_dump(\array_column($results, $key));
            }
        }

        $this->initRequests();

        return $results;
    }

    /**
     * Row filter
     *
     * @access private
     *
     * @param int $index
     * @param array $row
     * @return array|null
     */
    private function rowFilter(int $index, array $row): ?array
    {
        return $this->execWhere($index, $row) ? $this->columnFilter($row) : null;
    }

    /**
     * Whether row matches the request
     *
     * @access private
     *
     * @param int $index
     * @param array $row
     * @return bool
     */
    private function execWhere(int $index, array $row): bool
    {
        $last = \count($this->wheres) - 1;
        $i = 0;
        while ($i <= $last) {
            $where = $this->wheres[$i];
            foreach ($where as $condition) {
                $key     = $condition[0];
                $compare = \strtolower($condition[1]);
                $value   = $condition[2];

                if (!$this->match($compare, $value, $row[$key])) {
                    if ($i === $last) {
                        return false;
                    }
                    else {
                        break;
                    }
                }
            }
            $i++;
        }
        return true;
    }

    /**
     * Check value matches condition
     *
     * @access private
     *
     * @param string $compare
     * @param mixed $condition
     * @param mixed $value
     * @return bool
     */
    private function match(string $compare, $condition, $value): bool
    {
        if ($compare === '=') {
            return $condition === $value;
        }
        else if ($compare === 'in') {
            return \in_array($value, $condition, true);
        }
        else if ($compare === 'like') {
            return \mb_stripos($value, $condition) !== false;
        }

        return false;
    }

    /**
     * Column filter
     *
     * @access private
     *
     * @param array $row
     * @return array
     */
    private function columnFilter(array $row): array
    {
        //

        return $row;
    }

    /**
     * Refresh table
     *
     * @return void
     */
    public function refresh(): void
    {
        $this->rows = [];
        $this->total = 0;
        foreach ($this->indexes as &$indexes) {
            $indexes = [];
        }
        $this->initRequests();
    }

    /**
     * Initialize requests
     *
     * @return void
     */
    public function initRequests(): void
    {
        $this->wheres = [];
        $this->orderBy = [];
        $this->isNullOrder = null;
        $this->limit = 0;
        $this->offset = 0;
    }
}
