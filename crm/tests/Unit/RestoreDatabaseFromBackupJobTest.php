<?php

namespace Tests\Unit;

use App\Jobs\RestoreDatabaseFromBackupJob;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * Unit tests for RestoreDatabaseFromBackupJob.
 *
 * These tests exercise pure logic helpers that do not require a database or
 * queue connection, so they extend the base PHPUnit TestCase directly.
 */
class RestoreDatabaseFromBackupJobTest extends TestCase
{
    private function callShouldSkip(string $stmt): bool
    {
        // shouldSkipStatement is protected; access it via reflection.
        $job = new RestoreDatabaseFromBackupJob(1, 2);
        $ref = new ReflectionMethod($job, 'shouldSkipStatement');
        $ref->setAccessible(true);
        return $ref->invoke($job, $stmt);
    }

    /**
     * LOCK TABLES statements (produced by mysqldump) must be skipped so that
     * the MySQL session is never locked, allowing progress updates to the
     * `backups` table (prevents SQLSTATE HY000 / error 1100).
     *
     * @dataProvider lockTableStatementsProvider
     */
    public function test_should_skip_lock_and_unlock_tables_statements(string $stmt): void
    {
        $this->assertTrue(
            $this->callShouldSkip($stmt),
            "Expected shouldSkipStatement() to return true for: {$stmt}"
        );
    }

    public static function lockTableStatementsProvider(): array
    {
        return [
            'uppercase LOCK TABLES'              => ['LOCK TABLES `users` WRITE;'],
            'lowercase lock tables'              => ['lock tables `orders` write;'],
            'mixed case Lock Tables'             => ['Lock Tables `t` WRITE;'],
            'LOCK TABLES read lock'              => ['LOCK TABLES `products` READ;'],
            'LOCK TABLES multiple tables'        => ['LOCK TABLES `a` WRITE, `b` READ;'],
            'UNLOCK TABLES uppercase'            => ['UNLOCK TABLES;'],
            'unlock tables lowercase'            => ['unlock tables;'],
            'leading whitespace LOCK TABLES'     => ['   LOCK TABLES `t` WRITE;'],
            'leading whitespace UNLOCK TABLES'   => ["\t  UNLOCK TABLES;"],
        ];
    }

    /**
     * Normal SQL statements must NOT be skipped.
     *
     * @dataProvider normalSqlStatementsProvider
     */
    public function test_should_not_skip_normal_sql_statements(string $stmt): void
    {
        $this->assertFalse(
            $this->callShouldSkip($stmt),
            "Expected shouldSkipStatement() to return false for: {$stmt}"
        );
    }

    public static function normalSqlStatementsProvider(): array
    {
        return [
            'INSERT statement'                       => ['INSERT INTO `users` VALUES (1);'],
            'DROP TABLE'                             => ['DROP TABLE IF EXISTS `users`;'],
            'CREATE TABLE'                           => ['CREATE TABLE `users` (`id` INT);'],
            'mysqldump ALTER TABLE DISABLE KEYS'     => ['/*!40000 ALTER TABLE `users` DISABLE KEYS */;'],
            'mysqldump ALTER TABLE ENABLE KEYS'      => ['/*!40000 ALTER TABLE `users` ENABLE KEYS */;'],
            'SET FOREIGN_KEY_CHECKS'                 => ['SET FOREIGN_KEY_CHECKS=0;'],
            'UPDATE statement'                       => ['UPDATE `backups` SET progress_percent=50;'],
            'SELECT statement'                       => ['SELECT 1;'],
        ];
    }
}
