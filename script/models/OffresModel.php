<?php
class OffresModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getPaginatedOffres(int $limit, int $offset, array $filters = []): array {
        $whereClause = '';
        $params = [
            ':limit' => $limit,
            ':offset' => $offset
        ];

        // Gestion des filtres
        if (!empty($filters)) {
            $conditions = [];
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'categorie_id':
                        $conditions[] = 'o.categorie_id = :categorie_id';
                        $params[':categorie_id'] = (int)$value;
                        break;
                    case 'search':
                        $conditions[] = '(o.titre LIKE :search OR o.description LIKE :search OR o.entreprise LIKE :search)';
                        $params[':search'] = '%' . $value . '%';
                        break;
                    case 'date_min':
                        $conditions[] = 'o.date_publication >= :date_min';
                        $params[':date_min'] = $value;
                        break;
                }
            }
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }

        $query = "
            SELECT o.*, c.nom AS categorie 
            FROM offres o
            LEFT JOIN categories c ON o.categorie_id = c.id
            $whereClause
            ORDER BY o.date_publication DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllOffres(array $filters = []): int {
        $whereClause = '';
        $params = [];

        if (!empty($filters)) {
            $conditions = [];
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'categorie_id':
                        $conditions[] = 'categorie_id = :categorie_id';
                        $params[':categorie_id'] = (int)$value;
                        break;
                    case 'search':
                        $conditions[] = '(titre LIKE :search OR description LIKE :search OR entreprise LIKE :search)';
                        $params[':search'] = '%' . $value . '%';
                        break;
                }
            }
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }

        $query = "SELECT COUNT(*) FROM offres $whereClause";
        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }
        $stmt->execute();
        
        return (int)$stmt->fetchColumn();
    }

    public function getOffreById(int $id): ?array {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.*, c.nom AS categorie 
                FROM offres o
                LEFT JOIN categories c ON o.categorie_id = c.id
                WHERE o.id = :id
            ");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'offre: " . $e->getMessage());
            return null;
        }
    }

    public function offreExists(int $id): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM offres WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }

    public function createOffre(array $offreData): int {
        try {
            // Validation des données requises
            $requiredFields = ['titre', 'entreprise', 'description', 'lieu'];
            foreach ($requiredFields as $field) {
                if (empty($offreData[$field])) {
                    throw new InvalidArgumentException("Le champ $field est requis");
                }
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO offres 
                (titre, entreprise, description, lieu, date_publication, categorie_id, salaire, duree)
                VALUES 
                (:titre, :entreprise, :description, :lieu, :date_publication, :categorie_id, :salaire, :duree)
            ");
            
            $stmt->execute([
                ':titre' => htmlspecialchars(strip_tags($offreData['titre'])),
                ':entreprise' => htmlspecialchars(strip_tags($offreData['entreprise'])),
                ':description' => $offreData['description'],
                ':lieu' => htmlspecialchars(strip_tags($offreData['lieu'])),
                ':date_publication' => $offreData['date_publication'] ?? date('Y-m-d H:i:s'),
                ':categorie_id' => $offreData['categorie_id'] ?? null,
                ':salaire' => $offreData['salaire'] ?? null,
                ':duree' => $offreData['duree'] ?? null
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur création offre: " . $e->getMessage());
            throw new RuntimeException("Erreur lors de la création de l'offre");
        }
    }

    public function updateOffre(int $id, array $offreData): bool {
        try {
            // Récupère les champs à mettre à jour
            $fields = [];
            $params = [':id' => $id];
            
            $allowedFields = ['titre', 'entreprise', 'description', 'lieu', 'date_publication', 'categorie_id', 'salaire', 'duree'];
            
            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $offreData)) {
                    $fields[] = "$field = :$field";
                    $params[":$field"] = $field === 'description' 
                        ? $offreData[$field] 
                        : htmlspecialchars(strip_tags($offreData[$field]));
                }
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $query = "UPDATE offres SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur mise à jour offre: " . $e->getMessage());
            return false;
        }
    }

    public function deleteOffre(int $id): bool {
        try {
            $this->pdo->beginTransaction();
            
            // Supprime d'abord les dépendances (ex: wishlist)
            $stmt = $this->pdo->prepare("DELETE FROM wishlist WHERE offre_id = :id");
            $stmt->execute([':id' => $id]);
            
            // Puis supprime l'offre
            $stmt = $this->pdo->prepare("DELETE FROM offres WHERE id = :id");
            $result = $stmt->execute([':id' => $id]);
            
            $this->pdo->commit();
            return $result;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur suppression offre: " . $e->getMessage());
            return false;
        }
    }

    public function getSuggestedOffres(int $offreId, int $limit = 3): array {
        try {
            // Récupère d'abord la catégorie de l'offre actuelle
            $stmt = $this->pdo->prepare("SELECT categorie_id FROM offres WHERE id = :id");
            $stmt->execute([':id' => $offreId]);
            $categorieId = $stmt->fetchColumn();
            
            // Récupère les offres similaires
            $stmt = $this->pdo->prepare("
                SELECT o.*, c.nom AS categorie 
                FROM offres o
                LEFT JOIN categories c ON o.categorie_id = c.id
                WHERE o.id != :id AND o.categorie_id = :categorie_id
                ORDER BY o.date_publication DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':id', $offreId, PDO::PARAM_INT);
            $stmt->bindValue(':categorie_id', $categorieId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur récupération offres suggérées: " . $e->getMessage());
            return [];
        }
    }
}