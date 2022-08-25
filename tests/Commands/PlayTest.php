<?php

namespace tvthu\StormsshPhp\Tests\Commands;

use tvthu\StormsshPhp\Commands\Search;
use tvthu\StormsshPhp\Services\GetSshSetting;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class PlayTest extends TestCase
{

    private $command;

    protected function setUp(): void
    {
        $fileGetContentsWrapper = $this->createMock( GetSshSetting::class );


        $someSimulatedJson = 'Host asif.foundation
        hostname 54.251.31.69
        user ubuntu
        port 22
        identityfile "~/.ssh/asif-live/LightsailDefaultKey-ap-southeast-1.pem"';
        $fileGetContentsWrapper->method( 'fileGetContents' )->willReturn( $someSimulatedJson );

        $this->command = new Search($fileGetContentsWrapper);
        
        parent::setUp();
    }

    public function testItDoesNotCrash()
    {
        $tester = new CommandTester($this->command);

        $tester->execute([
            'name' => 'asif'
        ]);

        $tester->assertCommandIsSuccessful();
    }
}
