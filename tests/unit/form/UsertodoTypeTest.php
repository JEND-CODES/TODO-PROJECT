<?php

namespace Tests\Unit\Form;

use App\Entity\Usertodo;
use App\Form\UsertodoType;
use Symfony\Component\Form\Test\TypeTestCase;

class UsertodoTypeTest extends TypeTestCase
{

    public function testUsertodoTypeForm()
    {
        $username = 'Nicolas';

        $email = 'test@test.com';

        $password = 'testtest';

        $role = 'ROLE_USER';
        
        $formData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ];

        $usertodo = new Usertodo();

        $usertodo->setUsername($username);

        $usertodo->setEmail($email);

        $usertodo->setPassword($password);

        $usertodo->setRole($role);

        $form = $this->factory->create(UsertodoType::class, $usertodo); 

        $form->submit($formData);

        $this->assertEquals($usertodo->getUsername(), $username);

        $this->assertEquals($usertodo->getEmail(), $email);

        $this->assertEquals($usertodo->getPassword(), $password);

        $this->assertEquals($usertodo->getRole(), $role);
        
    }

}
