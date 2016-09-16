<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Persister;

use Xpmock\TestCase;

class ConnectionPersisterTest extends TestCase
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @var \ComPHPPuebla\Doctrine\DBAL\Fixture\Persister\ForeignKeyParser
     */
    protected $parser;

    protected function setUp()
    {
        $this->connection = $this->mock('\Doctrine\DBAL\Connection');
        $this->parser = $this->mock('\ComPHPPuebla\DBAL\Fixture\Persister\ForeignKeyParser');
    }

    public function testCanPersistFixtures()
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

        $this->expectsThatPersisterSavesTwoRecords();
        $this->expectsThatPersisterGetTheTwoRecordsInsertedIds();

        $persister = new ConnectionPersister($this->connection->new(), $this->parser->new());
        $persister->persist($gasStations);
    }

    protected function expectsThatPersisterSavesTwoRecords()
    {
        $station1 = [
            'name' => 'CASMEN GASOL',
            'social_reason' => 'CASMEN SA CV',
            'address_line_1' => '23 PTE NO 711',
            'address_line_2' => 'EL CARMEN',
            'location' => 'PUEBLA PUE',
            'latitude' => 19.03817,
            'longitude' => -98.20737,
            'created_at' => '2013-10-06 00:00:00',
            'last_updated_at' => '2013-10-06 00:00:00',
        ];
        $station2 = [
            'name' => 'COMBUSTIBLES JV',
            'social_reason' => 'COMBUSTIBLES JV SA CV',
            'address_line_1' => '24 SUR NO 507',
            'address_line_2' => 'CENTRO',
            'location' => 'PUEBLA PUE',
            'latitude' => 19.03492,
            'longitude' => -98.18554,
            'created_at' => '2013-10-06 00:00:00',
            'last_updated_at' => '2013-10-06 00:00:00',
        ];

        $this->connection->insert(['stations', $station1], null, $this->at(0))
                         ->insert(['stations', $station2], null, $this->at(2));
    }

    protected function expectsThatPersisterGetTheTwoRecordsInsertedIds()
    {
        $this->connection->lastInsertId([], 1, $this->at(1));
        $this->connection->lastInsertId([], 2, $this->at(3));
    }

    public function testCanQuoteIdentifiers()
    {
        $gasStation = [
            'stations' => [
                'station_1' => [
                    'name' => 'CASMEN GASOL',
                    'social_reason' => 'CASMEN SA CV',
                ],
            ]
        ];

        $this->expectsThatPersisterSavesOneRecord();
        $this->expectsThatPersisterGetTheRecordsInsertedId();
        $this->expectsThatConnectionQuotesAllColumnIdentifiers();

        $persister = new ConnectionPersister(
            $this->connection->new(), $this->parser->new(), $quote = true
        );
        $persister->persist($gasStation);
    }

    protected function expectsThatPersisterSavesOneRecord()
    {
        $station = [
            '`name`' => 'CASMEN GASOL',
            '`social_reason`' => 'CASMEN SA CV',
        ];

        $this->connection->insert(['stations', $station], null, $this->at(2));
    }

    protected function expectsThatPersisterGetTheRecordsInsertedId()
    {
        $this->connection->lastInsertId([], 1, $this->at(3));
    }

    protected function expectsThatConnectionQuotesAllColumnIdentifiers()
    {
        $this->connection->quoteIdentifier(['name'], '`name`', $this->at(0));
        $this->connection->quoteIdentifier(['social_reason'], '`social_reason`', $this->at(1));
    }
}
