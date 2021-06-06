<?php

namespace Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Usertodo;
use App\Entity\Tasktodo;

class UsertodoTest extends TestCase
{

    public function testId()
	{
		$usertodo = new Usertodo();

		$id = null;

		$this->assertEquals($id, $usertodo->getId());
	}

    public function testUsername()
	{
		$usertodo = new Usertodo();

		$name = "Manager";

		$usertodo->setUsername($name);

		$this->assertEquals($name, $usertodo->getUsername());
	}

    public function testPassword()
    {
        $usertodo = new Usertodo();

        $usertodo->setPassword('testtest');

        $this->assertEquals($usertodo->getPassword(), 'testtest');
    }

    public function testEmail()
	{
		$usertodo = new Usertodo();

		$email = "test@test.com";

		$usertodo->setEmail($email);

		$this->assertEquals($email, $usertodo->getEmail());
	}

    public function testAddTasktodo()
	{
		$usertodo = new Usertodo();

		$tasktodo = new Tasktodo();

		$usertodo->addTasktodo($tasktodo);

		$this->assertEquals($tasktodo, $usertodo->getTasktodos()[0]);
	}

    public function testRemoveTask()
	{
		$usertodo = new Usertodo();

		$tasktodo = new Tasktodo();

		$usertodo->addTasktodo($tasktodo);

		$this->assertEquals($tasktodo, $usertodo->getTasktodos()[0]);

		$usertodo->removeTasktodo($tasktodo);

		$this->assertEquals([], $usertodo->getTasktodos()->toArray());
	}

	public function testCreatedAt()
    {
        $usertodo = new Usertodo();

        $usertodo->setCreatedAt(new \DateTime);

        $this->assertInstanceOf(\DateTime::class, $usertodo->getCreatedAt());
    }

    public function testFreshDate()
    {
        $usertodo = new Usertodo();

        $usertodo->setFreshDate(new \DateTime);

        $this->assertInstanceOf(\DateTime::class, $usertodo->getFreshDate());
    }

	public function testRole()
	{
		$usertodo = new Usertodo();

		$role = '["ROLE_SUPER_ADMIN"]';

		$usertodo->setRole($role);
		
		$this->assertEquals($role, $usertodo->getRole());
	}

	public function testEraseCredentials()
    {
        $usertodo = new Usertodo();

        $this->assertEquals($usertodo->eraseCredentials(), null);
    }
    
}
