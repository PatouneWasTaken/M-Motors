<?php

require_once __DIR__ . '/DatabaseTestCase.php';

class ApplicationsModelTest extends DatabaseTestCase
{
    private int $userId;
    private int $vehicleId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userId = $this->seedUser('Alice', 'alice@example.com');
        $this->vehicleId = $this->seedVehicle($this->userId, 'Renault', 'Clio', 10000, 'sale');
    }

    public function testGetAllApplicationsJoinsAndOrders()
    {
        if (!$this->hasConcat) {
            $this->markTestSkipped('CONCAT non disponible sur ce build SQLite.');
        }

        $this->seedApplication($this->userId, $this->vehicleId, 'Alice', 'alice@example.com', 'd1.pdf', 'pending', '2024-01-01 10:00:00');
        $this->seedApplication($this->userId, $this->vehicleId, 'Alice', 'alice@example.com', 'd2.pdf', 'accepted', '2024-02-01 10:00:00');

        $apps = getAllApplications();

        $this->assertCount(2, $apps);
        // ORDER BY created_at DESC : le plus récent en premier
        $this->assertSame('d2.pdf', $apps[0]['document']);
        // Champs issus des jointures
        $this->assertSame('Renault Clio', $apps[0]['vehicle_name']);
        $this->assertSame('Alice', $apps[0]['user_name']);
        $this->assertSame('sale', $apps[0]['type']);
    }

    public function testGetApplicationByIdReturnsRow()
    {
        $id = $this->seedApplication($this->userId, $this->vehicleId);

        $app = getApplicationById($id);

        $this->assertSame($this->vehicleId, (int) $app['vehicle_id']);
        $this->assertSame('pending', $app['status']);
    }

    public function testGetApplicationByIdReturnsFalseWhenMissing()
    {
        $this->assertFalse(getApplicationById(999));
    }

    public function testUpdateApplicationStatus()
    {
        $id = $this->seedApplication($this->userId, $this->vehicleId, 'Alice', 'alice@example.com', 'd.pdf', 'pending');

        updateApplicationStatus($id, 'accepted');

        $app = getApplicationById($id);
        $this->assertSame('accepted', $app['status']);
    }

    public function testDeleteApplication()
    {
        $id = $this->seedApplication($this->userId, $this->vehicleId);

        deleteApplication($id);

        $this->assertFalse(getApplicationById($id));
    }
}
