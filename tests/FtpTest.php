<?php

declare(strict_types=1);

namespace Tests;

use Phant\Client\Service\Ftp;
use PHPUnit\Framework\TestCase;

class FtpTest extends TestCase
{
    private Ftp $ftp;

    protected function setUp(): void
    {
        $this->ftp = new Ftp(
            'localhost',
            'user',
            'pass',
            2121
        );
    }

    public function testListFiles(): void
    {
        $files = $this->ftp->listFiles('/');
        $this->assertIsArray($files);
        $this->assertNotEmpty($files);
        $this->assertContains('testfile.json', $files);
    }
}
