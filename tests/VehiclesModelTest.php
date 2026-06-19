<?php

require_once __DIR__ . '/DatabaseTestCase.php';

class VehiclesModelTest extends DatabaseTestCase
{
    private int $userId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userId = $this->seedUser();
    }

    public function testGetVehiclesReturnsAllOrderedByIdDesc()
    {
        $this->seedVehicle($this->userId, 'Renault', 'Clio');
        $this->seedVehicle($this->userId, 'Peugeot', '208');

        $vehicles = getVehicles();

        $this->assertCount(2, $vehicles);
        // ORDER BY id DESC : le dernier inséré arrive en premier
        $this->assertSame('Peugeot', $vehicles[0]['brand']);
        $this->assertSame('Renault', $vehicles[1]['brand']);
    }

    public function testGetVehiclesFiltersByType()
    {
        $this->seedVehicle($this->userId, 'Renault', 'Clio', 10000, 'sale');
        $this->seedVehicle($this->userId, 'BMW', 'Serie 1', 20000, 'rent');

        $rent = getVehicles('rent');

        $this->assertCount(1, $rent);
        $this->assertSame('BMW', $rent[0]['brand']);
    }

    public function testGetVehiclesFiltersByPriceRange()
    {
        $this->seedVehicle($this->userId, 'A', 'x', 5000);
        $this->seedVehicle($this->userId, 'B', 'y', 15000);
        $this->seedVehicle($this->userId, 'C', 'z', 25000);

        $result = getVehicles(null, 10000, 20000);

        $this->assertCount(1, $result);
        $this->assertSame('B', $result[0]['brand']);
    }

    public function testGetVehiclesFiltersByBrand()
    {
        $this->seedVehicle($this->userId, 'Renault', 'Clio');
        $this->seedVehicle($this->userId, 'Peugeot', '208');

        $result = getVehicles(null, null, null, 'Peugeot');

        $this->assertCount(1, $result);
        $this->assertSame('208', $result[0]['model']);
    }

    public function testGetVehiclesPagination()
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->seedVehicle($this->userId, 'Marque' . $i, 'M' . $i);
        }

        $page1 = getVehicles(null, null, null, null, 1, 2);
        $page2 = getVehicles(null, null, null, null, 2, 2);

        $this->assertCount(2, $page1);
        $this->assertCount(2, $page2);
        // Les pages ne se chevauchent pas
        $this->assertNotSame($page1[0]['id'], $page2[0]['id']);
    }

    public function testGetVehicleByIdReturnsRow()
    {
        $id = $this->seedVehicle($this->userId, 'Audi', 'A3');

        $vehicle = getVehicleById($id);

        $this->assertSame('Audi', $vehicle['brand']);
        $this->assertSame('A3', $vehicle['model']);
    }

    public function testGetVehicleByIdReturnsFalseWhenMissing()
    {
        $this->assertFalse(getVehicleById(999));
    }

    public function testGetBrandsReturnsDistinctSorted()
    {
        $this->seedVehicle($this->userId, 'Renault', 'Clio');
        $this->seedVehicle($this->userId, 'Audi', 'A3');
        $this->seedVehicle($this->userId, 'Renault', 'Megane');

        $brands = getBrands();

        $this->assertSame(['Audi', 'Renault'], $brands);
    }

    public function testCountVehicles()
    {
        $this->seedVehicle($this->userId, 'Renault', 'Clio', 10000, 'sale');
        $this->seedVehicle($this->userId, 'BMW', 'Serie 1', 20000, 'rent');

        $this->assertSame(2, (int) countVehicles());
        $this->assertSame(1, (int) countVehicles('rent'));
    }

    public function testUpdateVehicleWithoutPhotoKeepsPhoto()
    {
        $id = $this->seedVehicle($this->userId, 'Renault', 'Clio', 10000, 'sale', 50000, 'desc', 'old.png');

        updateVehicle($id, 'Renault', 'Clio 5', 'rent', 12000, 'nouvelle desc', 80000);

        $vehicle = getVehicleById($id);
        $this->assertSame('Clio 5', $vehicle['model']);
        $this->assertSame('rent', $vehicle['type']);
        $this->assertSame(12000, (int) $vehicle['price']);
        $this->assertSame(80000, (int) $vehicle['kms']);
        // La photo n'a pas été fournie : elle est conservée
        $this->assertSame('old.png', $vehicle['photo']);
    }

    public function testUpdateVehicleWithPhotoReplacesPhoto()
    {
        $id = $this->seedVehicle($this->userId, 'Renault', 'Clio', 10000, 'sale', 50000, 'desc', 'old.png');

        updateVehicle($id, 'Renault', 'Clio', 'sale', 10000, 'desc', 50000, 'new.png');

        $vehicle = getVehicleById($id);
        $this->assertSame('new.png', $vehicle['photo']);
    }

    public function testDeleteVehicle()
    {
        $id = $this->seedVehicle($this->userId, 'Renault', 'Clio');

        deleteVehicle($id);

        $this->assertFalse(getVehicleById($id));
        $this->assertSame(0, (int) countVehicles());
    }
}
