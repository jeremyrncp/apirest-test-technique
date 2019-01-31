<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Tests\Migrations;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class MigrationsTest extends KernelTestCase
{
    public function setUp()
    {
        $kernel = self::bootKernel();
    }
    public function testExecuteCommandsBeforeTests()
    {
        $application = new Application(static::$kernel);
        $application->setAutoExit(false);
        $this->assertEquals(0, $application->run(new ArrayInput([
            'command' => 'doctrine:schema:drop',
            '--force' => true
        ])));
        $this->assertEquals(0,$application->run(new ArrayInput([
            'command' => 'doctrine:schema:create'
        ])));
        $this->assertEquals(0,$application->run(new ArrayInput([
            'command' => 'hautelook:fixtures:load',
            '--no-interaction'
        ])));
    }
}