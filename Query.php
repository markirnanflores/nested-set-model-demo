<?php

namespace App;

use PDO;

class Query
{
    /**
     * Static function to make a select to the database
     * @return array
     */
    public static function select(PDO $connection, string $query, array $parameters, bool $ismultiple = true)
    {
        try {
            $statement = $connection->prepare($query);
            $statement->execute($parameters);
            return $ismultiple ? $statement->fetchAll(PDO::FETCH_ASSOC) : $statement->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }
}
