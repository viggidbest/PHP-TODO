<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../TodoRepository.php';

final class TodoRepositoryTest extends TestCase
{
    private string $db;
    private TodoRepository $repo;

    protected function setUp(): void
    {
        $this->db = sys_get_temp_dir() . '/todo_test_' . uniqid() . '.sqlite3';
        $this->repo = new TodoRepository($this->db);
    }

    public function testCreateAndGet(): void
    {
        $todo = $this->repo->create('Write tests');
        $this->assertSame('Write tests', $todo['title']);
        $this->assertFalse($todo['done']);
        $this->assertArrayHasKey('created_at', $todo);

        $fetched = $this->repo->get($todo['id']);
        $this->assertSame($todo['id'], $fetched['id']);
        $this->assertSame($todo['title'], $fetched['title']);
    }
    
    
    public function testAllReturnsAllTodosInDescendingOrder(): void
    {
        $first = $this->repo->create('First task');
        sleep(1); // ensure timestamp difference
        $second = $this->repo->create('Second task');

        $all = $this->repo->all();

        // Two items, second created should appear first (descending id)
        $this->assertCount(2, $all);
        $this->assertSame($second['id'], $all[0]['id']);
        $this->assertSame($first['id'], $all[1]['id']);
    }

    public function testUpdateChangesTitleAndDone(): void
    {
        $todo = $this->repo->create('Original');
        $updated = $this->repo->update($todo['id'], ['title' => 'Updated', 'done' => true]);

        $this->assertSame('Updated', $updated['title']);
        $this->assertTrue($updated['done']);

        // Fetch again and confirm persistence
        $fetched = $this->repo->get($todo['id']);
        $this->assertSame('Updated', $fetched['title']);
        $this->assertTrue($fetched['done']);
    }

    public function testUpdateNonExistentReturnsNull(): void
    {
        $result = $this->repo->update(9999, ['title' => 'Nothing']);
        $this->assertNull($result);
    }

    public function testHydrateCastsFieldsCorrectly(): void
    {
        $reflection = new ReflectionClass($this->repo);
        $method = $reflection->getMethod('hydrate');
        $method->setAccessible(true);

        $row = [
            'id' => '1',
            'title' => 'Task',
            'done' => '0',
            'created_at' => '2024-01-01T00:00:00Z'
        ];

        $hydrated = $method->invoke($this->repo, $row);
        $this->assertIsInt($hydrated['id']);
        $this->assertIsString($hydrated['title']);
        $this->assertIsBool($hydrated['done']);
        $this->assertIsString($hydrated['created_at']);
    } 



    /*public function testDeleteNonExistentReturnsTrueButDoesNothing2(): void
    {
        // SQLite's execute() returns true even if no rows affected
        //$result = $this->repo->delete(12345);
        //$this->assertTrue($result);
        //console.log
    }*/
}
