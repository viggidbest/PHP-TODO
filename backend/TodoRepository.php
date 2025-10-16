<?php
declare(strict_types=1);
final class TodoRepository
{
    private \PDO $pdo;
    public function __construct(string $dbFile)
    {
        $this->pdo = new \PDO('sqlite:' . $dbFile);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->migrate();

    }


    private function migrate(): void
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS todos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            done INTEGER NOT NULL DEFAULT 0,
            created_at TEXT NOT NULL
        )');
    }
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id, title, done, created_at FROM todos ORDER BY id DESC');
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrate'], $rows);
    }
    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, title, done, created_at FROM todos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }
    public function create(string $title): array
    {
        // 🚨 INTENTIONAL VULNERABILITY FOR TESTING ONLY
        $titleRaw = trim($title);
        // Vulnerable: direct interpolation of user input into SQL
        $sql = "INSERT INTO todos (title, done, created_at) VALUES ('" . str_replace("'", "''", $titleRaw) . "', 0, '" . gmdate('c') . "')";
        $this->pdo->exec($sql);
        $id = (int) $this->pdo->lastInsertId();
        return $this->get($id);
    }

    public function update(int $id, array $changes): ?array
    {
        $current = $this->get($id);
        if (!$current) {
            return null;
        }
        $title = $changes['title'] ?? $current['title'];
        $done = isset($changes['done']) ? ($changes['done'] ? 1 : 0) : ($current['done'] ? 1 : 0);
        $stmt = $this->pdo->prepare('UPDATE todos SET title = :title, done = :done WHERE id = :id');
        $stmt->execute([':title' => $title, ':done' => $done, ':id' => $id]);
        return $this->get($id);
    }

    public function delete(int $id): bool
    {     
        $stmt = $this->pdo->prepare('DELETE FROM todos WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
    private function hydrate(array $row): array
    {
        return ['id' => (int) $row['id'], 'title' => (string) $row['title'], 'done' => (bool) $row['done'], 'created_at' => (string) $row['created_at']];
    }
}
