<?php

declare(strict_types=1);

namespace Phant\Client\Service;

use FTP\Connection;

class Ftp implements \Phant\Client\Port\Ftp
{
    private Connection $connection;

    public function __construct(
        string $host,
        string $username,
        string $password,
        int $port = 21
    ) {
        $this->connection = $this->connect(
            host: $host,
            username: $username,
            password: $password,
            port: $port
        );
    }

    private function connect(string $host, string $username, string $password, int $port): Connection
    {
        $ftp = ftp_connect($host, $port);
        if (!$ftp) {
            throw new \RuntimeException("Failed to connect to FTP server: {$host}");
        }

        $login = ftp_login($ftp, $username, $password);
        if (!$login) {
            throw new \RuntimeException("Failed to log in to FTP server: {$host}");
        }

        return $ftp;
    }

    public function listFiles(string $path): array
    {
        $files = ftp_nlist($this->connection, $path);
        if (false === $files) {
            return [];
        }

        return $files;
    }

    public function download(string $remotePath, ?string $localDirectory = null): string
    {
        if ($localDirectory) {
            $localFilePath = rtrim($localDirectory, '/') . '/' . basename($remotePath);
        } else {
            $localFilePath = sys_get_temp_dir() . '/' . basename($remotePath);
        }

        if (!ftp_get($this->connection, $localFilePath, $remotePath, FTP_BINARY)) {
            throw new \RuntimeException("Failed to download file from FTP: {$remotePath}");
        }

        return $localFilePath;
    }

    public function delete(string $path): bool
    {
        if (!ftp_delete($this->connection, $path)) {
            throw new \RuntimeException("Failed to delete file from FTP: {$path}");
        }

        return true;
    }

    public function __destruct()
    {
        ftp_close($this->connection);
    }
}
