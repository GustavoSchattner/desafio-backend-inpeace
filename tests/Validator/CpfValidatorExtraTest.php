<?php

namespace App\Tests\Validator;

use App\Validator\Cpf;
use App\Validator\CpfValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class CpfValidatorExtraTest extends TestCase
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

    public function testShortCpfAddsSingleViolation(): void
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->context->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $builder->expects($this->once())->method('setParameter')->willReturnSelf();
        $builder->expects($this->once())->method('addViolation');

        $this->validator->validate('123', new Cpf());
    }

    public function testInvalidChecksumAddsSingleViolation(): void
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->context->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $builder->expects($this->once())->method('setParameter')->willReturnSelf();
        $builder->expects($this->once())->method('addViolation');

        // a 11-digit CPF with invalid checksum
        $this->validator->validate('12345678900', new Cpf());
    }
}
