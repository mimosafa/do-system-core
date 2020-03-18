<?php

namespace PseudoDatabase;

class Database
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
     * Primary key of table
     *
     * @var string
     */
    private $primary;

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
     * Tables
     *
     * @var self[]
     */
    private static $tables = [];

    /**
     * Constructor
     *
     * @access private
     *
     * @param string $tableName
     * @param array $definitions
     */
    private function __construct(string $tableName, array $definitions)
    {
        $this->name = $tableName;

        foreach ($definitions as $key => $definition) {
            $this->keys[] = $key;

            if (isset($definition['primary_key']) && $definition['primary_key'] === true) {
                $this->primary = $key;
            }

            if (isset($definition['nullable']) && $definition['nullable'] === true) {
                $this->nullables[] = $key;
            }
        }
    }

    /**
     * Check existence of table
     *
     * @param string $tableName
     * @return bool
     */
    public static function exists(string $tableName): bool
    {
        return isset(self::$tables[$tableName]);
    }

    /**
     * Create table
     *
     * @param string $tableName
     * @param array $definitions
     * @return void
     * @throws \Exception
     */
    public static function create(string $tableName, array $definitions = []): void
    {
        if (isset(self::$tables[$tableName])) {
            throw new \Exception('Table named `' . $tableName . '` is already exists.');
        }
        self::$tables[$tableName] = new self($tableName, $definitions);
    }

    /**
     * Get table (singleton)
     *
     * @param string $tableName
     * @return self|null
     */
    public static function table(string $tableName): ?self
    {
        return self::$tables[$tableName] ?? null;
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
            $row[$key] = null;

            if ($key === $this->primary) {
                continue;
            }

            if (!isset($data[$key]) && !\in_array($key, $this->nullables, true)) {
                throw new \Exception('Required value of `' . $key .'` does not exist.');
            }

            $row[$key] = $data[$key];
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

                    if (is_null($value) && !\in_array($key, $this->nullables, true)) {
                        throw new \Exception('Required value of `' . $key .'` does not exist.');
                    }

                    $row[$key] = $value;
                }
            }
        }
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
            return $this->rows;
        }

        $results = [];
        foreach ($this->rows as $index => $row) {
            if ($row = $this->rowFilter($index, $row)) {
                $results[] = $row;
            }
        }
        $this->wheres = []; // Initialize where parameters

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
        $this->wheres = [];
    }
}
