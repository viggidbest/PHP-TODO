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
        $fetched = $this->repo->get($todo['id']);
        $this->assertSame($todo['id'], $fetched['id']);
    }

    // public function testThisShouldFail(): void
    // {
    //     // This test is designed to fail on purpose
    //     $expected = 42;
    //     $actual = 43;
    //     $this->assertSame($expected, $actual, 'This test intentionally fails for CI verification.');
    // }

}
