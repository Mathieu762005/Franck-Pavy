<?php

namespace App\Models;

use App\Models\DataBase;

use PDO;
use PDOException;

class AdminUser
{
    private PDO $db;

    public function __construct()
    {
        // Connexion à la base via ta classe DataBase
        $this->db = DataBase::createInstancePDO();
    }


    // Récupérer tous les utilisateurs
    public function findAll()
    {
        try {
            $sql = "SELECT * FROM users";
            $stmt = $this->db->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }
}
