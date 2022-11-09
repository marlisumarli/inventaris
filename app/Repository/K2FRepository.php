<?php

namespace Subjig\Report\Repository;

use Subjig\Report\Entity\K2F;

class K2FRepository
{
    const TYPE = 'k2f';

    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(K2F $k2f): K2F
    {
        $statement = $this->connection->prepare("INSERT INTO k2f(k2f_id, k2f_name, k2f_qty) VALUES (?,?,?)");
        $statement->execute([$k2f->k2f_id, $k2f->k2f_name, $k2f->k2f_qty]);
        return $k2f;
    }

    public function update(K2F $k2f): K2F
    {
        $statement = $this->connection
            ->prepare("UPDATE k2f SET k2f_name = ?, k2f_qty = ? WHERE  k2f_id = ?");
        $statement->execute([$k2f->k2f_name, $k2f->k2f_qty, $k2f->k2f_id]);
        return $k2f;
    }

    public function findById(string $id): ?K2F
    {
        $statement = $this->connection->prepare("SELECT k2f_id, k2f_name, k2f_qty FROM k2f WHERE k2f_id = ?");
        $statement->execute([$id]);

        try {
            if ($row = $statement->fetch()) {
                $category = new K2F();
                $category->k2f_id = $row['k2f_id'];
                $category->k2f_name = $row['k2f_name'];
                $category->k2f_qty = $row['k2f_qty'];
                return $category;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function findByName(string $name): ?K2F
    {
        $statement = $this->connection->prepare("SELECT k2f_id, k2f_name, k2f_qty FROM k2f WHERE k2f_name = ?");
        $statement->execute([$name]);

        try {
            if ($row = $statement->fetch()) {
                $category = new K2F();
                $category->k2f_id = $row['k2f_id'];
                $category->k2f_name = $row['k2f_name'];
                $category->k2f_qty = $row['k2f_qty'];
                return $category;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteById(string $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM k2f WHERE k2f_id = ?");
        $statement->execute([$id]);
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM k2f");
    }

    public function findAll(): array
    {
        $sql = "SELECT k2f_id, k2f_name, k2f_qty FROM k2f";
        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $result = [];

        $k2f = $statement->fetchAll();

        foreach ($k2f as $row) {
            $data = new K2F();
            $data->setK2fId($row['k2f_id']);
            $data->setK2fName($row['k2f_name']);
            $data->setK2fQty($row['k2f_qty']);

            $result[] = $data;
        }
        return $result;
    }
}