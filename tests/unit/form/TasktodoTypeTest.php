<?php

namespace Tests\Unit\Form;

use App\Entity\Tasktodo;
use App\Form\TasktodoType;
use Symfony\Component\Form\Test\TypeTestCase;

class TasktodoTypeTest extends TypeTestCase
{
    
    public function testTasktodoTypeForm()
    {
        $title = 'Nouvelle tâche';

        $content = 'Description de la tâche';
        
        $formData = [
            'title' => $title,
            'content' => $content
        ];

        $tasktodo = new Tasktodo();

        $tasktodo->setTitle($title);

        $tasktodo->setContent($content);

        $form = $this->factory->create(TasktodoType::class, $tasktodo); 

        $form->submit($formData);

        $this->assertEquals($tasktodo->getTitle(), $title);

        $this->assertEquals($tasktodo->getContent(), $content);
        
    }

}
