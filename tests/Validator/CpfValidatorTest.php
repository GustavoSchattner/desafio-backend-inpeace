<?php

namespace App\Tests\Validator;

use App\Validator\Cpf;
use App\Validator\CpfValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class CpfValidatorTest extends TestCase
{
    private CpfValidator $validator;

    /** @var ExecutionContextInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $context;

    protected function setUp(): void
    {
        $this->validator = new CpfValidator();
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator->initialize($this->context);
    }

    /**
     * @dataProvider validCpfProvider
     */
    public function testValidateValidCpf(string $cpf): void
    {
        $this->context->expects($this->never())->method('buildViolation');
        $this->validator->validate($cpf, new Cpf());
    }

    /**
     * @dataProvider invalidCpfProvider
     */
    public function testValidateInvalidCpf(string $cpf): void
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->context->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $builder->expects($this->once())->method('setParameter')->willReturnSelf();
        $builder->expects($this->once())->method('addViolation');

        $this->validator->validate($cpf, new Cpf());
    }

    public function testValidateStopsAtFirstError(): void
    {
        $cpfComDoisDigitosErrados = '111.111.111-88'; 

        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->context->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $builder->expects($this->once())->method('setParameter')->willReturnSelf();
        $builder->expects($this->once())->method('addViolation');

        $this->validator->validate($cpfComDoisDigitosErrados, new Cpf());
    }

    public function testIgnoreNullOrEmpty(): void
    {
        $this->context->expects($this->never())->method('buildViolation');
        $this->validator->validate(null, new Cpf());
        $this->validator->validate('', new Cpf());
    }

    public static function validCpfProvider(): array
    {
        return [
            ['52998224725'],
            ['529.982.247-25'],
            [' 52998224725 '],
            ['00000000191'],
        ];
    }

    public static function invalidCpfProvider(): array
    {
        return [
            ['11111111111'], 
            ['12345678900'], 
            ['123'],        
            ['abcdefghijk'], 
        ];
    }
}