<?php

require_once __DIR__ . '/DatabaseTestCase.php';

class UsersModelTest extends DatabaseTestCase
{
    public function testGetUserByIdReturnsPublicFields()
    {
        $id = $this->seedUser('Alice', 'alice@example.com', 'secret-hash', 1);

        $user = getUserById($id);

        $this->assertSame('Alice', $user['name']);
        $this->assertSame('alice@example.com', $user['email']);
        $this->assertSame(1, (int) $user['is_admin']);
        // getUserById ne doit pas exposer le mot de passe
        $this->assertArrayNotHasKey('password', $user);
    }

    public function testGetUserByIdReturnsFalseWhenMissing()
    {
        $this->assertFalse(getUserById(999));
    }

    public function testGetUserPasswordHashReturnsHash()
    {
        $id = $this->seedUser('Bob', 'bob@example.com', 'mon-hash');

        $this->assertSame('mon-hash', getUserPasswordHash($id));
    }

    public function testGetUserPasswordHashReturnsNullWhenMissing()
    {
        $this->assertNull(getUserPasswordHash(999));
    }

    public function testEmailTakenByOtherDetectsConflict()
    {
        $alice = $this->seedUser('Alice', 'alice@example.com');
        $bob = $this->seedUser('Bob', 'bob@example.com');

        // L'email de Bob est pris par un autre utilisateur du point de vue d'Alice
        $this->assertTrue(emailTakenByOther('bob@example.com', $alice));
    }

    public function testEmailTakenByOtherIgnoresSameUser()
    {
        $alice = $this->seedUser('Alice', 'alice@example.com');

        // Son propre email ne doit pas être considéré comme "pris par un autre"
        $this->assertFalse(emailTakenByOther('alice@example.com', $alice));
    }

    public function testEmailTakenByOtherFalseForUnusedEmail()
    {
        $alice = $this->seedUser('Alice', 'alice@example.com');

        $this->assertFalse(emailTakenByOther('libre@example.com', $alice));
    }

    public function testUpdateUserProfile()
    {
        $id = $this->seedUser('Alice', 'alice@example.com');

        updateUserProfile($id, 'Alice Martin', 'alice.martin@example.com');

        $user = getUserById($id);
        $this->assertSame('Alice Martin', $user['name']);
        $this->assertSame('alice.martin@example.com', $user['email']);
    }

    public function testUpdateUserPassword()
    {
        $id = $this->seedUser('Alice', 'alice@example.com', 'ancien-hash');

        updateUserPassword($id, 'nouveau-hash');

        $this->assertSame('nouveau-hash', getUserPasswordHash($id));
    }
}
