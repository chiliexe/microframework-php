<?php

namespace Core;

use PDO;
use PDOStatement;

abstract class Model
{
    private PDO $pdo;
    protected string $table;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findAll(string $order = "id")
    {
        $type =  Utils::starts_with($order, "-") ? "DESC" : "ASC";
        $order = Utils::starts_with($order, "-") ? substr($order, 1) : $order;
        $query = "SELECT * FROM {$this->table} ORDER BY {$order} {$type}";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }

    public function findById(int $id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }

    public function create(array $data): bool
    {
        $bind = $this->bindInsert($data);
        $query = "INSERT INTO {$this->table} ({$bind['strKeys']}) VALUES ({$bind['strBinds']})";
        $stmt = $this->pdo->prepare($query);
        $stmt = $this->bindParams($stmt, $data);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }

    public function update($data, int $id): bool
    {
        $bind = $this->bindUpdate($data);
        $query = "UPDATE {$this->table} SET {$bind} WHERE id = {$id}";
        $stmt = $this->pdo->prepare($query);
        $stmt = $this->bindParams($stmt, $data);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }

    public function delete($id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }

    //=======================================================================//

    private function bindInsert(array $data): array
    {
        $strKeys = "";
        $strBind = "";
        $index = 1;
        foreach ($data as $key => $value)
        {
            $strKeys .= count($data) > $index ? "$key, " : "$key" ;
            $strBind .= count($data) > $index ? ":$key, " : ":$key";

            $index++;
        }
        return ["strKeys" => $strKeys, "strBinds" => $strBind];
    }
    private function bindUpdate($data)
    {
        $query = "";
        foreach ($data as $key => $value)
        {
            if ($key == "id") continue;

            $query .= ", $key = :$key";
        }

        return substr($query, 1);
    }

    private function bindParams(PDOStatement $stmt, $data ): PDOStatement
    {
        foreach ($data as $key => $value)
        {
            $this->bindParam($stmt, ":$key", $value);
        }
        return $stmt;
    }
    private function bindParam($stmt, $key, $value)
    {
        return $stmt->bindValue($key, $value);
    }
}