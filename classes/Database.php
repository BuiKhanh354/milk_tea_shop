<?php
// classes/Database.php — class làm việc với MySQL bằng MySQLi theo chuẩn minishop
class Database
{
    private $connection;

    public function __construct($config)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->connection = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database'],
            $config['port'] ?? 3306
        );

        $this->connection->set_charset($config['charset'] ?? 'utf8mb4');
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function run($sql, $params = array())
    {
        $statement = $this->connection->prepare($sql);
        if ($statement === false) {
            throw new Exception("Lỗi prepare SQL: " . $this->connection->error);
        }

        if (!empty($params)) {
            $types = '';
            $values = array();
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_null($param)) {
                    $types .= 's';
                } else {
                    $types .= 's';
                }
                $values[] = $param;
            }
            $statement->bind_param($types, ...$values);
        }

        $statement->execute();
        return $statement;
    }

    public function fetchAll($sql, $params = array())
    {
        $stmt = $this->run($sql, $params);
        $result = $stmt->get_result();
        if ($result === false) {
            return array();
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchOne($sql, $params = array())
    {
        $stmt = $this->run($sql, $params);
        $result = $stmt->get_result();
        if ($result === false) {
            return null;
        }
        $row = $result->fetch_assoc();
        return $row ?: null;
    }

    public function execute($sql, $params = array())
    {
        return $this->run($sql, $params)->affected_rows;
    }

    public function insertId()
    {
        return $this->connection->insert_id;
    }
}

