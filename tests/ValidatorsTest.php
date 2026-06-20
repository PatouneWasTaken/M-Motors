<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../toolbox/validators.php';

class ValidatorsTest extends TestCase
{
    // Registration form

    public function testValidRegistrationPasses()
    {
        $data = [
            'firstname' => 'Jean',
            'lastname'  => 'Dupont',
            'email'     => 'jean@example.com',
            'password'  => 'password1',
        ];

        $this->assertSame([], validateRegistration($data));
    }

    public function testRegistrationRejectsMissingFields()
    {
        $data = [
            'firstname' => '',
            'lastname'  => 'Dupont',
            'email'     => 'jean@example.com',
            'password'  => 'password1',
        ];

        $this->assertContains("Tous les champs sont obligatoires", validateRegistration($data));
    }

    public function testRegistrationRejectsInvalidEmail()
    {
        $data = [
            'firstname' => 'Jean',
            'lastname'  => 'Dupont',
            'email'     => 'not-an-email',
            'password'  => 'password1',
        ];

        $this->assertContains("Email invalide", validateRegistration($data));
    }

    public function testRegistrationRejectsWeakPassword()
    {
        // Trop court et sans chiffre
        $data = [
            'firstname' => 'Jean',
            'lastname'  => 'Dupont',
            'email'     => 'jean@example.com',
            'password'  => 'abc',
        ];

        $this->assertNotEmpty(validateRegistration($data));
    }

    public function testRegistrationRejectsPasswordWithoutDigit()
    {
        $data = [
            'firstname' => 'Jean',
            'lastname'  => 'Dupont',
            'email'     => 'jean@example.com',
            'password'  => 'onlyletters',
        ];

        $this->assertNotEmpty(validateRegistration($data));
    }

    // Login form

    public function testValidLoginPasses()
    {
        $data = ['email' => 'jean@example.com', 'password' => 'whatever'];

        $this->assertSame([], validateLogin($data));
    }

    public function testLoginRejectsInvalidEmail()
    {
        $data = ['email' => 'bad', 'password' => 'whatever'];

        $this->assertContains("Email invalide", validateLogin($data));
    }

    public function testLoginRejectsEmptyPassword()
    {
        $data = ['email' => 'jean@example.com', 'password' => ''];

        $this->assertContains("Tous les champs sont obligatoires", validateLogin($data));
    }

    // Vehicle form (add / edit)

    public function testValidVehiclePasses()
    {
        $data = [
            'brand' => 'Renault',
            'model' => 'Clio',
            'type'  => 'sale',
            'price' => '8990',
            'kms'   => '50000',
        ];

        $this->assertSame([], validateVehicle($data));
    }

    public function testVehicleRejectsInvalidKms()
    {
        $data = [
            'brand' => 'Renault',
            'model' => 'Clio',
            'type'  => 'sale',
            'price' => '8990',
            'kms'   => 'abc',
        ];

        $this->assertContains("Kilométrage invalide", validateVehicle($data));
    }

    public function testVehicleRejectsBadType()
    {
        $data = [
            'brand' => 'Renault',
            'model' => 'Clio',
            'type'  => 'gift',
            'price' => '8990',
            'kms'   => '50000',
        ];

        $this->assertContains("Type invalide", validateVehicle($data));
    }

    public function testVehicleRejectsNonNumericPrice()
    {
        $data = [
            'brand' => 'Renault',
            'model' => 'Clio',
            'type'  => 'rent',
            'price' => 'abc',
            'kms'   => '50000',
        ];

        $this->assertContains("Prix invalide", validateVehicle($data));
    }

    public function testVehicleRejectsEmptyBrandOrModel()
    {
        $data = [
            'brand' => '',
            'model' => '',
            'type'  => 'sale',
            'price' => '1000',
            'kms'   => '50000',
        ];

        $this->assertContains("Marque et modèle obligatoires", validateVehicle($data));
    }

    // Application form (dossier)

    public function testValidApplicationPasses()
    {
        $data = [
            'vehicle_id' => '3',
            'name'       => 'Jean Dupont',
            'email'      => 'jean@example.com',
        ];

        $this->assertSame([], validateApplication($data));
    }

    public function testApplicationRejectsMissingVehicle()
    {
        $data = [
            'vehicle_id' => '0',
            'name'       => 'Jean Dupont',
            'email'      => 'jean@example.com',
        ];

        $this->assertContains("Véhicule invalide", validateApplication($data));
    }

    public function testApplicationRejectsInvalidEmail()
    {
        $data = [
            'vehicle_id' => '3',
            'name'       => 'Jean Dupont',
            'email'      => 'bad',
        ];

        $this->assertContains("Email invalide", validateApplication($data));
    }

    // File upload helpers

    public function testAllowsJpgImage()
    {
        $this->assertTrue(isAllowedImage('photo.jpg', 500000));
    }

    public function testRejectsTooLargeImage()
    {
        $this->assertFalse(isAllowedImage('photo.jpg', 5 * 1024 * 1024));
    }

    public function testRejectsDisallowedImageExtension()
    {
        $this->assertFalse(isAllowedImage('virus.exe', 1000));
    }

    public function testAcceptsPdfMimeType()
    {
        $this->assertTrue(isPdfMimeType('application/pdf'));
    }

    public function testRejectsNonPdfMimeType()
    {
        $this->assertFalse(isPdfMimeType('image/png'));
    }

    public function testRejectsTooLargePdf()
    {
        $this->assertFalse(isWithinMaxSize(6 * 1024 * 1024, 5 * 1024 * 1024));
    }

    public function testAcceptsPdfWithinSize()
    {
        $this->assertTrue(isWithinMaxSize(1024, 5 * 1024 * 1024));
    }
}