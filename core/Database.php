<?php

namespace core;

use app\exceptions\DBException;
use core\QueryLogger;

class Database
{
    private $table;
    private $columns = "*";
    private $whereValues = [];
    private $setValues = [];
    private $set = [];
    private $where = [];
    private $joins = [];
    private $orderBy = "";
    private $groupBy = "";
    private $limit = "";
    private $connection;
    private $groupNestingLevel = 0;
    private $lastWhereConnector = null;



    // Construtor para aceitar a conexão MySQLi
    public function __construct($mysqli)
    {
        $this->connection = $mysqli;
    }

    public function last_id()
    {
        return $this->connection->insert_id;
    }

    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    public function select($columns = "*")
    {
        $this->columns = is_array($columns) ? implode(",", $columns) : $columns;
        return $this;
    }

    public function join($table, $on, $type = "INNER")
    {
        $this->joins[] = "$type JOIN $table ON $on";
        return $this;
    }
    public function update($table = null)
    {
        if ($table) {
            $this->table = $table;
        }

        if (empty($this->set)) {
            throw new \Exception("Nenhum valor definido para atualização. Use set(\$data) antes de update().");
        }

        if (empty($this->where)) {
            throw new \Exception("Atualização sem cláusula WHERE não é permitida por segurança.");
        }

        $setClauses = [];
        $setValues = [];
        foreach ($this->set as $column => $value) {
            $setClauses[] = "$column = ?";
            $setValues[] = $value;
        }

        $query = "UPDATE $this->table SET " . implode(", ", $setClauses);
        $query .= " WHERE " . implode(" AND ", $this->where);

        $params = array_merge($setValues, $this->whereValues);

        $this->reset();
        return $this->query($query, $params);
    }


    public function insert($table)
    {
        if ($table) {
            $this->table = $table;
        }

        if (empty($this->set)) {
            throw new \Exception("Nenhum valor definido para inserção. Use set(\$data) antes de insert().");
        }

        $columns = implode(", ", array_keys($this->set));
        $placeholders = implode(", ", array_fill(0, count($this->set), '?'));
        $this->setValues = array_values($this->set);

        $query = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";

        $params = $this->setValues;

        $this->reset();
        return $this->query($query, $params);
    }

    public function delete($table)
    {
        $query = "DELETE FROM $table";

        if (!empty($this->where)) {
            $query .= " WHERE " . implode(" ", $this->where);
        }

        $params = $this->whereValues;

        $this->reset();

        return $this->query($query, $params);
    }

    public function set($set)
    {
        $this->set = $set;
        return $this;
    }

    public function group_start($type = 'AND')
    {
        $type = strtoupper($type);

        if (!in_array($type, ['AND', 'OR'])) {
            throw new \InvalidArgumentException("Tipo de agrupamento inválido. Use 'AND' ou 'OR'.");
        }

        $prefix = '';

        if (!empty($this->where)) {
            $prefix = "$type ";
        }

        $this->where[] = "{$prefix}(";
        $this->groupNestingLevel++;
        $this->lastWhereConnector = null;

        return $this;
    }




    public function group_end()
    {
        if ($this->groupNestingLevel <= 0) {
            throw new \LogicException("Nenhum grupo iniciado para ser fechado com group_end().");
        }

        $this->where[] = ")";
        $this->groupNestingLevel--;
        return $this;
    }



    public function where($column, $operator, $value)
    {
        $connector = $this->buildConnector('AND');
        $this->where[] = "$connector$column $operator ?";
        $this->whereValues[] = $value;
        return $this;
    }


    public function orWhere($column, $operator, $value)
    {
        $connector = $this->buildConnector('OR');
        $this->where[] = "$connector$column $operator ?";
        $this->whereValues[] = $value;
        return $this;
    }

    private function buildConnector(string $type): string
    {
        if (empty($this->where)) {
            return '';
        }

        $last = end($this->where);
        $trimmedLast = trim($last);

        if ($trimmedLast === '(' || substr($trimmedLast, -1) === '(') {
            return '';
        }

        return "$type ";
    }



    public function whereIn($column, $values)
    {
        if (!is_array($values)) {
            throw new \InvalidArgumentException("Os valores devem ser um array.");
        }
        if (empty($values)) {
            throw new \InvalidArgumentException("Os valores não podem estar vazios.");
        }
        if (count($values) > 1000) {
            throw new \InvalidArgumentException("O número máximo de valores é 1000.");
        }
        $connector = $this->buildConnector('AND');
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->where[] = "$connector$column IN ($placeholders)";
        $this->whereValues = array_merge($this->whereValues, $values);
        return $this;
    }

    public function whereNotIn($column, $values)
    {
        if (!is_array($values)) {
            throw new \InvalidArgumentException("Os valores devem ser um array.");
        }
        if (empty($values)) {
            throw new \InvalidArgumentException("Os valores não podem estar vazios.");
        }
        if (count($values) > 1000) {
            throw new \InvalidArgumentException("O número máximo de valores é 1000.");
        }
        $connector = $this->buildConnector('AND');
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->where[] = "$connector$column NOT IN ($placeholders)";
        $this->whereValues = array_merge($this->whereValues, $values);
        return $this;
    }

    public function whereNull($column)
    {
        $connector = $this->buildConnector('AND');
        $this->where[] = "$connector$column IS NULL";
        return $this;
    }

    public function whereNotNull($column)
    {
        $connector = $this->buildConnector('AND');

        $this->where[] = "$connector$column IS NOT NULL";
        return $this;
    }

    public function orderBy($columns)
    {
        $order = "ORDER BY ";

        $orderCriteria = [];
        foreach ($columns as $column => $direction) {
            $orderCriteria[] = "$column $direction";
        }

        $order .= implode(', ', $orderCriteria);

        $this->orderBy = $order;

        return $this;
    }

    public function groupBy($columns)
    {
        if (is_array($columns)) {
            $this->groupBy = "GROUP BY " . implode(", ", $columns);
        } else {
            $this->groupBy = "GROUP BY $columns";
        }

        return $this;
    }


    public function limit($limit)
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function get()
    {
        $query = "SELECT $this->columns FROM $this->table";
        if (!empty($this->joins)) {
            $query .= " " . implode(" ", $this->joins);
        }
        if ($this->where) {
            $query .= " WHERE " . implode(" ", $this->where);
        }
        if ($this->groupBy) {
            $query .= " " . $this->groupBy;
        }
        if ($this->orderBy) {
            $query .= " " . $this->orderBy;
        }
        if ($this->limit) {
            $query .= " " . $this->limit;
        }

        $params = array_merge($this->setValues, $this->whereValues);
        $this->reset();

        return $this->query($query, $params);
    }

    public function query($sql, $params = [], $debug = false)
    {
        $stmt = $this->connection->prepare($sql);

        if (!$stmt) {
            throw new DBException("Erro na preparação da consulta: " . $this->connection->error, $sql, $params, 0, 500);
        }

        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        if ($debug) {
            $debugSql = $sql;
            foreach ($params as $param) {
                $safeParam = is_string($param) ? "'$param'" : $param;
                $debugSql = preg_replace('/\?/', $safeParam, $debugSql, 1);
            }

            echo '<pre>';
            echo "SQL montado:\n" . $debugSql . "\n\n";
            var_dump([
                'raw_sql' => $sql,
                'params' => $params,
                'types' => $types ?? '',
            ]);
            exit();
        }
        $startTime = microtime(true);

        $stmt->execute();
        $executionTime = number_format(microtime(true) - $startTime, 2, '.', '') . " segundos";

        if ($stmt->error) {
            throw new DBException("Erro na execução da consulta: " . $stmt->error, $sql, $params, $executionTime, 500);
        }

        if ($stmt->field_count > 0) {
            do {
                $result = $stmt->get_result();
                if ($result) {
                    $results[] = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free();
                }
            } while ($stmt->more_results() && $stmt->next_result());

            QueryLogger::logQuery($sql, $params, $executionTime);
            return count($results) === 1 ? $results[0] : $results;
        }

        $affectedRows = $stmt->affected_rows;
        if ($affectedRows === -1) {
            throw new DBException("Erro na execução da consulta. Nenhuma linha foi afetada: " . $stmt->error, $sql, $params, $executionTime, 500);
        }

        QueryLogger::logQuery($sql, $params, $executionTime);

        return $affectedRows;
    }

    public function callProcedure(string $procedureName, array $params = [], bool $debug = false)
    {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL {$procedureName}($placeholders)";

        return $this->query($sql, $params, $debug);
    }

    public function insertBatch($table, array $data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException("Os dados para inserção em lote não podem estar vazios.");
        }

        $this->table = $table;
        $columns = array_keys($data[0]);
        $placeholders = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';
        $allPlaceholders = implode(',', array_fill(0, count($data), $placeholders));
        $flatValues = [];

        foreach ($data as $row) {
            if (array_keys($row) !== $columns) {
                throw new \InvalidArgumentException("Todas as linhas devem ter as mesmas colunas.");
            }
            $flatValues = array_merge($flatValues, array_values($row));
        }

        $query = "INSERT INTO $this->table (" . implode(', ', $columns) . ") VALUES $allPlaceholders";

        $this->reset();
        return $this->query($query, $flatValues);
    }

    public function updateBatch($table, array $data, string $indexColumn)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException("Os dados para atualização em lote não podem estar vazios.");
        }

        if (!isset($data[0][$indexColumn])) {
            throw new \InvalidArgumentException("A coluna de índice '$indexColumn' deve estar presente em todas as linhas.");
        }

        $this->table = $table;

        $columns = array_keys($data[0]);
        $columns = array_filter($columns, function ($col) use ($indexColumn) {
            return $col !== $indexColumn;
        });
        $cases = [];
        $params = [];
        $ids = [];

        foreach ($columns as $col) {
            $case = "$col = CASE $indexColumn ";
            foreach ($data as $row) {
                if (!isset($row[$indexColumn])) {
                    throw new \InvalidArgumentException("Cada linha deve conter o índice '$indexColumn'.");
                }
                $case .= "WHEN ? THEN ? ";
                $params[] = $row[$indexColumn];
                $params[] = $row[$col];
                $ids[] = $row[$indexColumn];
            }
            $case .= "ELSE $col END";
            $cases[] = $case;
        }

        $idsPlaceholders = implode(',', array_fill(0, count($ids), '?'));
        $params = array_merge($params, $ids); // para o WHERE IN
        $query = "UPDATE $this->table SET " . implode(', ', $cases) . " WHERE $indexColumn IN ($idsPlaceholders)";

        $this->reset();
        return $this->query($query, $params);
    }

    public function beginTransaction()
    {
        $this->connection->begin_transaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollback()
    {
        $this->connection->rollback();
    }

    private function reset()
    {
        $this->table = null;
        $this->columns = "*";
        $this->setValues = [];
        $this->whereValues = [];
        $this->set = [];
        $this->where = [];
        $this->joins = [];
        $this->orderBy = "";
        $this->limit = "";
        $this->groupBy = "";
        $this->lastWhereConnector = null;
        $this->groupNestingLevel = 0;
    }
}
