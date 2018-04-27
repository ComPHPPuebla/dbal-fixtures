<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures;

use ComPHPPuebla\Fixtures\Database\DBALConnection;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

class CustomTypeTest extends TestCase
{
    /** @test */
    function it_loads_a_fixture_with_a_custom_type()
    {
        $pathToCustomTypeTable = __DIR__ . '/../../data/mysql-custom-type-database.sql';
        $dbalConnection = (new MySQLConnectionFactory())->connect($pathToCustomTypeTable);
        $connection = new DBALConnection($dbalConnection);
        Type::addType('point', PointType::class);
        $connection->registerPlatformType('point', 'point');
        $database = new TestDatabase($dbalConnection);
        $fixtures = new Fixture($connection);

        $fixtures->load(__DIR__ . '/../../data/fixture-custom-type.yml');

        $location = $database->findLocationWithId(1);
        $coordinates = unpack('x/x/x/x/corder/Ltype/dlat/dlon', $location['coordinates']);
        $this->assertCount(2, $location);
        $this->assertInternalType('float', $coordinates['lat']);
        $this->assertInternalType('float', $coordinates['lon']);
    }
}
