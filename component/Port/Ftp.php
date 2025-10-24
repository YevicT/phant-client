<?php

declare(strict_types=1);

namespace Phant\Client\Port;

interface Ftp
{
    public function listFiles(string $path): array;

    /**
     * @param string $remotePath The path of the file on the FTP server
     * @param string|null $localDirectory The local directory to save the downloaded file. If null, a default temp directory will be used.
     * @return string Local file path of the downloaded file
     *
     * @throws \RuntimeException
     */
    public function download(string $remotePath, ?string $localDirectory = null): string;

    public function delete(string $remotePath): bool;
}
