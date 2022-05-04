<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserEntityTest extends KernelTestCase
{
    private const EMAIL_CONSTRAINTE_MESSAGE = 'Veuillez inclure "@" dans l\'adresse e-mail. Il manque un symbole "@" dans "testgmail.fr".';
    
    private const Not_Blank_Message = 'Veuillez renseigner ce champ.';

    private const INVALIDE_EMAIL_VALUE = "testgmail.fr";

    private const VALIDE_EMAIL_VALUE = "test@gmail.fr";

    private const PASSWORD_COMPARE = "Les mots de passe ne sont pas identique";

    private const VALIDE_PASSWORD = "azerty";

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->validator = $kernel->getContainer()->get('validator'); 
    }

    public function testUserEntityIsValide(): void
    {
        $user = new User();

        $user
            ->setEmail(self::VALIDE_EMAIL_VALUE)
            ->setPassword(self::VALIDE_PASSWORD);

            $this->getValidationErrors($user, 0);
    }

    private function getValidationErrors(User $user, int $numberOfExpectedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($user);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }

    
    
}
