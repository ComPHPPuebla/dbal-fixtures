<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\DBAL\Fixture\Persister;

use ComPHPPuebla\DBAL\Fixture\Loader\YamlLoader;
use Doctrine\DBAL\Connection;
use PHPUnit_Framework_TestCase as TestCase;

class ConnectionPersisterIntegrationTest extends TestCase
{
    /** @var string */
    protected $path;

    /** @var array */
    protected $gasStations;

    /** @var Connection */
    protected $connection;

    /** @var int */
    protected $stationId;

    protected function setUp()
    {
        $this->path = __DIR__ . '/../../../../data/fixture.yml';
        $this->gasStations = [
            'stations' => [
                'station_1' => [
                    '`name`' => 'CASMEN GASOL',
                    '`social_reason`' => 'CASMEN SA CV',
                    '`address_line_1`' => '23 PTE NO 711',
                    '`address_line_2`' => 'EL CARMEN',
                    '`location`' => 'PUEBLA PUE',
                    '`latitude`' => 19.03817,
                    '`longitude`' => -98.20737,
                    '`created_at`' => '2013-10-06 00:00:00',
                    '`last_updated_at`' => '2013-10-06 00:00:00',
                ],
                'station_2' => [
                    '`name`' => 'COMBUSTIBLES JV',
                    '`social_reason`' => 'COMBUSTIBLES JV SA CV',
                    '`address_line_1`' => '24 SUR NO 507',
                    '`address_line_2`' => 'CENTRO',
                    '`location`' => 'PUEBLA PUE',
                    '`latitude`' => 19.03492,
                    '`longitude`' => -98.18554,
                    '`created_at`' => '2013-10-06 00:00:00',
                    '`last_updated_at`' => '2013-10-06 00:00:00',
                ],
            ],
            'reviews' => [
                'review_1' => [
                   '`comment`' => 'El servicio es excelente',
                   '`stars`' => 5,
                   '`station_id`' => '@station_1',
                ],
                'review_2' => [
                    '`comment`' => 'El servicio es pésimo',
                    '`stars`' => 1,
                    '`station_id`' => '@station_1',
                ],
            ],
        ];
        $this->stationId = 1;
        $this->connection = $this->prophesize(Connection::class);
    }

    /** @test */
    public function it_persists_fixtures_with_references()
    {
        $this->connectionWillQuoteIdentifiers();
        $this->expectConnectionSavesFourRecords();
        $this->connectionWillReturnFourInsertedIds();
        $persister = new ConnectionPersister(
            $this->connection->reveal(), new ForeignKeyParser()
        );

        $rows = (new YamlLoader($this->path))->load();
        $persister->persist($rows);
    }

    private function expectConnectionSavesFourRecords()
    {
        $station1 = $this->gasStations['stations']['station_1'];
        $station2 = $this->gasStations['stations']['station_2'];
        $review1 = $this->gasStations['reviews']['review_1'];
        $review1['`station_id`'] = $this->stationId;
        $review2 = $this->gasStations['reviews']['review_2'];
        $review2['`station_id`'] = $this->stationId;

        $this->connection->insert('stations', $station1)->shouldBeCalled();
        $this->connection->insert('stations', $station2)->shouldBeCalled();
        $this->connection->insert('reviews', $review1)->shouldBeCalled();
        $this->connection->insert('reviews', $review2)->shouldBeCalled();
    }

    private function connectionWillReturnFourInsertedIds()
    {
        $this->connection->lastInsertId()->willReturn(
            $this->stationId,
            2,
            1,
            2
        );
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
        $this->connection->quoteIdentifier('comment')->willReturn('`comment`');
        $this->connection->quoteIdentifier('stars')->willReturn('`stars`');
        $this->connection->quoteIdentifier('station_id')->willReturn('`station_id`');
    }
}
