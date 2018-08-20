<?php
declare(strict_types=1);

namespace App\User\Projection;

use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\AbstractReadModel;

class UserReadModelRepo extends AbstractReadModel
{
    private const TABLE = 'users';

    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->connection->setFetchMode(\PDO::FETCH_ASSOC);
    }

    public function init(): void
    {
        $tableName = self::TABLE;

        $sql = <<<EOT
CREATE TABLE `$tableName` (
  `id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
EOT;

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    public function isInitialized(): bool
    {
        $tableName = self::TABLE;

        $sql = "SHOW TABLES LIKE '${tableName}';";

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetch();

        return !(false === $result);
    }

    public function reset(): void
    {
        $tableName = self::TABLE;

        $sql = "TRUNCATE TABLE ${tableName};";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    public function delete(): void
    {
        $tableName = self::TABLE;

        $sql = "DROP TABLE ${tableName}";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    protected function add(UserReadModel $readModel): UserReadModel
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $readModel->id,
                'name' => $readModel->name,
                'email' => $readModel->email,
            ]
        );

        return $readModel;
    }

    /**
     * @return UserReadModel[]
     */
    public function findAll(): array
    {
        $tableName = self::TABLE;

        $arrayUsers = $this->connection->fetchAll("SELECT * FROM ${tableName}");

        $users = [];
        foreach ($arrayUsers as $item) {
            $users[] = UserReadModel::fromArray($item);
        }

        return $users;
    }

    /**
     * @param string $userId
     *
     * @return UserReadModel|null
     */
    public function findById(string $userId): ?UserReadModel
    {
        $table = self::TABLE;

        $stmt = $this->connection->prepare("SELECT * FROM ${table} WHERE id = :user_id LIMIT 1");
        $stmt->bindValue('user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch();

        if (false === $result) {
            return null;
        }

        return UserReadModel::fromArray($result);
    }

    /**
     * @param string $emailAddress
     *
     * @return UserReadModel|null
     */
    public function findOneByEmailAddress(string $emailAddress): ?UserReadModel
    {
        $table = self::TABLE;

        $stmt = $this->connection->prepare("SELECT * FROM ${table} WHERE email = :email LIMIT 1");
        $stmt->bindValue('email', $emailAddress);
        $stmt->execute();

        $result = $stmt->fetch();

        if (false === $result) {
            return null;
        }

        return UserReadModel::fromArray($result);
    }
}
