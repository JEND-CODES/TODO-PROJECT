<?php

namespace Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Usertodo;
use App\Entity\Tasktodo;

class TasktodoTest extends TestCase
{
    
    public function testId()
	{
		$tasktodo = new Tasktodo();

		$id = null;

		$this->assertEquals($id, $tasktodo->getId());
	}

    public function testTitle()
    {
        $tasktodo = new Tasktodo();

        $tasktodo->setTitle('Nouvelle tâche');

        $this->assertEquals($tasktodo->getTitle(), 'Nouvelle tâche');
    }

    public function testContent()
    {
        $tasktodo = new Tasktodo();

        $tasktodo->setContent('Description de la tâche');

        $this->assertEquals($tasktodo->getContent(), 'Description de la tâche');
    }

    public function testCreatedAt()
    {
        $tasktodo = new Tasktodo();

        $tasktodo->setCreatedAt(new \DateTime);

        $this->assertInstanceOf(\DateTime::class, $tasktodo->getCreatedAt());
    }

    public function testFreshDate()
    {
        $tasktodo = new Tasktodo();

        $tasktodo->setFreshDate(new \DateTime);

        $this->assertInstanceOf(\DateTime::class, $tasktodo->getFreshDate());
    }

    public function testSetUsertodo()
    {
        $tasktodo = new Tasktodo();

        $tasktodo->setUsertodo(new Usertodo);

        $this->assertInstanceOf(Usertodo::class, $tasktodo->getUsertodo());
    }

    public function testIsDone()
    {
        $tasktodo = new Tasktodo();

        $tasktodo->setIsDone(true);

        $this->assertEquals($tasktodo->getIsDone(), true);
    }

    public function testToggle()
    {
        $tasktodo = new Tasktodo();

        $tasktodo->toggle(true);

        $this->assertEquals($tasktodo->isDone(), true);
    }
    
}
