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
     * Table definitions
     *
     * @var array{
     *      @type array{
     *          @type string $type   string|integer
     *          @type bool $primary  (optional)
     *          @type bool $unique   (optional)
     *          @type bool $nullable (optional)
     *      }[] ${...$column}
     * }
     */
    private $definitions;

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
     *      @type string ${...$key} integer|string
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
     * @var array[
     *      @type string [0]  key of here
     *      @type string [1]  compare here and there
     *      @type mixed  [2]  key(s) of there
     * ][]
     */
    private $wheres = [];

    /**
     * Requested select
     *
     * @var array{
     *      @type string ${...$keyAlias}  Exists key in $keys
     * }
     */
    private $selects = [];

    /**
     * Requested order (by)
     *
     * @var array[
     *      @type string [0]  Key used for sort order
     *      @type string [1]  asc|desc
     * ][]
     */
    private $orderBy = [];

    /**
     * Is null order
     * If 'asc', last. If 'desc', first.
     * If not set depends system.
     *
     * @var array{
     *      @type string ${...$key}
     * }
     */
    private $isNullOrder = [];

    /**
     * Temporary parameters to sort rows
     *
     * @var array{
     *      @type strin $key
     *      @type strin $order
     *      @type strin $type
     *      @type int|null $null_value_compare
     * }[]
     */
    private $tempParamsForSort = [];

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
     * Names of table to join
     *
     * @var array{
     *      @type array{
     *          @type Table  $table
     *          @type string $join  inner|left|right
     *          @type string $here
     *          @type string $compare
     *          @type string $there
     *      } ${...tableName}
     * }
     */
    private $tablesToJoin = [];

    /**
     * Acceptable compare string
     *
     * @static
     * @var string[]
     */
    private static $acceptableCompareStrings = [
        '=', 'in', 'like',
    ];

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

        $this->definitions = $definitions;
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

        if (\is_string($key) && \in_array($key, $this->keys, true)) {
            if (\count($params) === 2) {
                $compare = \strtolower($params[0]);
                if (\in_array($compare, self::$acceptableCompareStrings, true)) {
                    $where[] = [$key, $params[0], $params[1]];
                }
            }
            else if (\count($params) === 1) {
                $where[] = [$key, '=', $params[0]];
            }
        }

        return $this;
    }

    /**
     * Select
     *
     * @param string[] ...$columns
     * @return self
     */
    public function select(string ...$columns): self
    {
        foreach ($columns as $column) {
            if (\strpos($column, ' as ')) {
                [$column, $key] = \explode(' as ', $column);
            }
            else {
                $key = $column;
            }
            if (isset($this->selects[$key])) {
                throw new \Exception('Invalid argument: cannot select duplicated key `' . $key . '`');
            }
            $this->selects[$key] = $column;
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
            throw new \Exception('To exec ' . __METHOD__ . ', need to limit target by `where` method.');
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
            if (\in_array($order, ['asc', 'desc'], true)) {
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
        if (!empty($this->orderBy) && $key = $this->orderBy[count($this->orderBy) - 1][0]) {
            $order = \strtolower($order);
            if (\in_array($order, ['asc', 'desc'], true)) {
                $this->isNullOrder[$key] = $order;
            }
        }
        return $this;
    }

    /**
     * Join table
     *
     * @param string|self $table
     * @param string $here
     * @param string $compare
     * @param mixed  $there
     * @return self
     */
    public function join($table, string $here, string $compare, $there): self
    {
        return $this->innerJoinTables($table)->on($here, $compare, $there);
    }

    /**
     * Inner join tables
     *
     * @param string[]|self[] ...$tables
     * @return self
     */
    public function innerJoinTables(...$tables): self
    {
        static $emptyArray = [
            'table'   => null,
            'join'    => 'inner',
            'here'    => null,
            'compare' => null,
            'there'   => null
        ];

        foreach ($tables as $table) {
            if ($table instanceof self) {
                $this->tablesToJoin[$table->name] = $emptyArray;
                $this->tablesToJoin[$table->name]['table'] = $table;
            }
            else if (\is_string($table) && Database::exists($table)) {
                $this->tablesToJoin[$table] = $emptyArray;
                $this->tablesToJoin[$table]['table'] = Database::table($table);
            }
        }

        return $this;
    }

    /**
     * Where on joined table
     *
     * @param string $here
     * @param string $compare
     * @param string $thereKey
     * @return self
     */
    public function on(string $here, string $compare, string $there): self
    {
        $theres = explode('.', $there);
        if (\in_array($here, $this->keys, true) && \in_array($compare, self::$acceptableCompareStrings, true) && count($theres) === 2) {
            [$name, $key] = $theres;
            if (isset($this->tablesToJoin[$name]) && $table = $this->tablesToJoin[$name]['table']) {
                if (\in_array($key, $table->keys, true)) {
                    $this->tablesToJoin[$name]['here']    = $here;
                    /**
                     * @todo Supported only `=`
                     */
                    $this->tablesToJoin[$name]['compare'] = $compare;
                    $this->tablesToJoin[$name]['there'] = $key;
                }
            }
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
     * Get results
     *
     * @return array
     */
    public function get(): array
    {
        if (!empty($this->tablesToJoin)) {
            return $this->joinedTable()->get();
        }

        $results = [];
        foreach ($this->rows as $index => $row) {
            if ($row = $this->rowFilter($index, $row)) {
                $results[] = $row;
            }
        }

        if (!empty($results)) {

            if ($this->initTempParamsForSort()) {

                $prev = '';

                while (!empty($this->tempParamsForSort)) {
                    $param = \array_shift($this->tempParamsForSort);

                    $key     = $param['key'];
                    $asc     = $param['order'] === 'asc';
                    $type    = $param['type'];
                    $nullCmp = $param['null_value_compare'];

                    self::sortRows($results, $key, $type, $asc, $nullCmp, $prev);

                    $prev = $key;
                }

            }

            if (($this->offset > 0) || ($this->limit > 0)) {
                $limit = $this->limit ?: null;
                $results = \array_slice($results, $this->offset, $limit);
            }
        }

        $results = \array_map(function ($row) {
            return $this->columnFilter($row);
        }, $results);

        $this->initRequests();

        return $results;
    }

    /**
     * Sort rows
     *
     * @static
     * @access private
     *
     * @param array[]  &$rows
     * @param string   $key
     * @param string   $type
     * @param bool     $asc
     * @param int|null $nullCmp
     * @param string   $prev
     * @return void
     */
    private static function sortRows(array &$rows, string $key, string $type, bool $asc, ?int $nullCmp, string $prev = ''): void
    {
        $rowsCache = $rows;
        $rows = [];

        $values = \array_column($rowsCache, $key);

        \uasort($values, function ($val1, $val2) use ($type, $asc, $nullCmp) {
            $cmp = 0;

            if ($val1 === $val2) {
                return $cmp;
            }

            if (\is_null($val1) && isset($nullCmp)) {
                return $asc ? $nullCmp : $nullCmp * -1;
            }
            if (\is_null($val2) && isset($nullCmp)) {
                return $asc ? $nullCmp * -1 : $nullCmp;
            }

            if ($type === 'integer') {
                $cmp = $val1 - $val2;
            }
            else if ($type === 'string') {
                /**
                 * @todo No tests yet
                 */
                $cmp = \strnatcmp($val1, $val2);
            }

            return $asc ? $cmp : $cmp * -1;
        });

        if ($prev) {
            $index = [];

            $valueKeys = \array_keys($values);

            $prevValues = \array_column($rowsCache, $prev);
            $lastIOfPrevValue = \count($prevValues) - 1;

            $prevValueCache = null;
            $iCache = [];

            for ($iOfPrevValue = 0; $iOfPrevValue <= $lastIOfPrevValue; $iOfPrevValue++) {
                $prevValue = $prevValues[$iOfPrevValue];

                if (($iOfPrevValue > 0) && (($prevValue !== $prevValueCache) || ($iOfPrevValue === $lastIOfPrevValue))) {
                    if ($iOfPrevValue === $lastIOfPrevValue) {
                        $iCache[] = $iOfPrevValue;
                    }
                    if (\count($iCache) > 1) {
                        foreach ($valueKeys as $iOfValueKey => $valueKey) {
                            if (\in_array($valueKey, $iCache, true)) {
                                $index[] = $valueKey;
                                unset($valueKeys[$iOfValueKey]);
                            }
                        }
                    }
                    else {
                        $index[] = $iCache[0];
                    }

                    $iCache = [];
                }

                $iCache[] = $iOfPrevValue;
                $prevValueCache = $prevValue;
            }
        }
        else {
            $index = \array_keys($values);
        }

        foreach ($index as $i) {
            $rows[] = $rowsCache[$i];
        }
    }

    /**
     * Initialize temporary parameters for sort
     *
     * @access private
     *
     * @return bool
     */
    private function initTempParamsForSort(): bool
    {
        if (!empty($this->orderBy)) {
            foreach ($this->orderBy as $orderBy) {
                $key = $orderBy[0];
                if (!\in_array($key, $this->keys, true) && !$key = $this->selects[$key] ?? false) {
                    continue;
                }
                $order = $orderBy[1];
                if (!\in_array($order, ['asc', 'desc'], true)) {
                    continue;
                }
                $type = $this->types[$key];
                $nullValueCompare = null;
                if (isset($this->isNullOrder[$key])) {
                    if ($order === $this->isNullOrder[$key]) {
                        $nullValueCompare = 1;
                    }
                    else {
                        $nullValueCompare = -1;
                    }
                }
                $this->tempParamsForSort[] = [
                    'key' => $key,
                    'order' => $order,
                    'type' => $type,
                    'null_value_compare' => $nullValueCompare
                ];
            }
        }

        return !empty($this->tempParamsForSort);
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
        if (empty($this->wheres)) {
            return $row;
        }
        return $this->execWhere($index, $row) ? $row : null;
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
        if (empty($this->selects)) {
            return $row;
        }
        $filteredRow = [];
        foreach ($this->selects as $key => $column) {
            $filteredRow[$key] = !isset($this->keys[$column]) ? $row[$column] : null;
        }
        return $filteredRow;
    }

    /**
     * Get joined table instance
     *
     * @access private
     *
     * @return self
     */
    private function joinedTable(): self
    {
        $name = $this->name;
        $definitions = $this->definitions;
        $rows = $this->rows;

        $joinedRows = [];

        foreach ($this->tablesToJoin as $tableName => $array) {
            $table = $array['table'];

            $hereKey = $array['here'];
            $compare = $array['compare'];
            $thereKey = $array['there'];

            if (\is_null($hereKey) || \is_null($compare) || \is_null($thereKey)) {
                continue;
            }

            if ($compare !== '=') {
                /**
                 * @todo
                 */
                continue;
            }

            $tableKeys = $table->keys;
            $tableDefinitions = $table->definitions;

            $name = "{$name}.{$tableName}";

            $joinedKeys = [];
            foreach ($tableKeys as $tableKey) {
                $joinedKey = "{$tableName}.{$tableKey}";
                $definitions[$joinedKey] = $tableDefinitions[$tableKey];
                $joinedKeys[] = $joinedKey;
            }

            $join = $array['join'];
            $tableRows = $table->rows;
            $tableIndexes = \array_column($tableRows, $thereKey);

            if ($join === 'inner') {
                foreach ($rows as $row) {
                    $hereValue = $row[$hereKey];
                    if ($compare === '=') {
                        $tableIndex = \array_search($hereValue, $tableIndexes, true);
                        if ($tableIndex !== false) {
                            $tableRow = $tableRows[$tableIndex];
                            $joinedRow = $row;
                            foreach ($tableKeys as $i => $tableKey) {
                                $joinedRow[$joinedKeys[$i]] = $tableRow[$tableKey];
                            }
                            $joinedRows[] = $joinedRow;
                        }
                    }
                    else {
                        /** @todo */
                    }
                }
            }
        }

        $joinedTable = new self($name, $definitions);
        // Recover primary key
        $joinedTable->primary = $this->primary;
        $joinedTable->rows = $joinedRows;
        $joinedTable->total = count($joinedTable->rows);
        $joinedTable->wheres = $this->wheres;
        $joinedTable->selects = $this->selects;
        $joinedTable->orderBy = $this->orderBy;
        $joinedTable->isNullOrder = $this->isNullOrder;
        $joinedTable->limit = $this->limit;
        $joinedTable->offset = $this->offset;

        $this->initRequests();

        # var_dump($joinedTable);

        return $joinedTable;
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
        $this->selects = [];
        $this->orderBy = [];
        $this->isNullOrder = [];
        $this->limit = 0;
        $this->offset = 0;
        $this->tablesToJoin = [];
    }
}
