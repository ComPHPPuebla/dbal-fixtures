<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Persister;

use Doctrine\DBAL\Connection;
use PHPUnit_Framework_TestCase as TestCase;

class ConnectionPersisterTest extends TestCase
{
    /** Connection */
    protected $connection;

    /** @var ForeignKeyParser */
    protected $parser;

    protected function setUp()
    {
        $this->connection = $this->prophesize(Connection::class);
    }

    /** @test */
    public function it_persists_fixtures()
    {
        $gasStations = [
            'stations' => [
                'station_1' => [
                    'name' => 'CASMEN GASOL',
                    'social_reason' => 'CASMEN SA CV',
                    'address_line_1' => '23 PTE NO 711',
                    'address_line_2' => 'EL CARMEN',
                    'location' => 'PUEBLA PUE',
                    'latitude' => 19.03817,
                    'longitude' => -98.20737,
                    'created_at' => '2013-10-06 00:00:00',
                    'last_updated_at' => '2013-10-06 00:00:00',
                ],
                'station_2' => [
                    'name' => 'COMBUSTIBLES JV',
                    'social_reason' => 'COMBUSTIBLES JV SA CV',
                    'address_line_1' => '24 SUR NO 507',
                    'address_line_2' => 'CENTRO',
                    'location' => 'PUEBLA PUE',
                    'latitude' => 19.03492,
                    'longitude' => -98.18554,
                    'created_at' => '2013-10-06 00:00:00',
                    'last_updated_at' => '2013-10-06 00:00:00',
                ]
            ]
        ];

        $this->expectsConnectionSavesTwoRecords();
        $this->connectionWillQuoteIdentifiers();
        $this->connectionWillReturnTwoIds();

        $persister = new ConnectionPersister(
            $this->connection->reveal(), new ForeignKeyParser()
        );
        $persister->persist($gasStations);
    }

    protected function expectsConnectionSavesTwoRecords()
    {
        $station1 = [
            '`name`' => 'CASMEN GASOL',
            '`social_reason`' => 'CASMEN SA CV',
            '`address_line_1`' => '23 PTE NO 711',
            '`address_line_2`' => 'EL CARMEN',
            '`location`' => 'PUEBLA PUE',
            '`latitude`' => 19.03817,
            '`longitude`' => -98.20737,
            '`created_at`' => '2013-10-06 00:00:00',
            '`last_updated_at`' => '2013-10-06 00:00:00',
        ];
        $station2 = [
            '`name`' => 'COMBUSTIBLES JV',
            '`social_reason`' => 'COMBUSTIBLES JV SA CV',
            '`address_line_1`' => '24 SUR NO 507',
            '`address_line_2`' => 'CENTRO',
            '`location`' => 'PUEBLA PUE',
            '`latitude`' => 19.03492,
            '`longitude`' => -98.18554,
            '`created_at`' => '2013-10-06 00:00:00',
            '`last_updated_at`' => '2013-10-06 00:00:00',
        ];

        $this->connection->insert('stations', $station1)->shouldBeCalled();
        $this->connection->insert('stations', $station2)->shouldBeCalled();
    }

    protected function connectionWillReturnTwoIds()
    {
        $this->connection->lastInsertId()->willReturn(1);
        $this->connection->lastInsertId()->willReturn(2);
    }

    protected function connectionWillQuoteIdentifiers()
    {
        $this->connection->quoteIdentifier('name')->willReturn('`name`');
        $this->connection->quoteIdentifier('social_reason')->willReturn('`social_reason`');
        $this->connection->quoteIdentifier('address_line_1')->willReturn('`address_line_1`');
        $this->connection->quoteIdentifier('address_line_2')->willReturn('`address_line_2`');
        $this->connection->quoteIdentifier('location')->willReturn('`location`');
        $this->connection->quoteIdentifier('latitude')->willReturn('`latitude`');
        $this->connection->quoteIdentifier('longitude')->willReturn('`longitude`');
        $this->connection->quoteIdentifier('created_at')->willReturn('`created_at`');
        $this->connection->quoteIdentifier('last_updated_at')->willReturn('`last_updated_at`');
    }
}
