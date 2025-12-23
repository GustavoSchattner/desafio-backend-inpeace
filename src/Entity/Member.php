<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MemberRepository;
use App\Validator as AppAssert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: '`member`')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: false)]
class Member
{
    use SoftDeleteableEntity;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'O logradouro é obrigatório.')]
    private ?string $address = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: 'A data de nascimento é obrigatória.')]
    #[Assert\LessThan('today', message: 'A data deve ser no passado.')]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Church $church = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'A cidade é obrigatória.')]
    private ?string $city = null;

    #[ORM\Column(length: 14)]
    #[Assert\NotBlank(message: 'O CPF é obrigatório.')]
    #[AppAssert\Cpf]
    private ?string $cpf = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'O e-mail é obrigatório.')]
    #[Assert\Email(message: 'O e-mail {{ value }} não é válido.')]
    private ?string $email = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'O nome é obrigatório.')]
    #[Assert\Length(min: 3, minMessage: 'O nome deve ter pelo menos 3 caracteres.')]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'O telefone é obrigatório.')]
    #[Assert\Length(min: 10, minMessage: 'Telefone incompleto.')]
    private ?string $phone = null;

    #[ORM\Column(length: 2)]
    #[Assert\NotBlank(message: 'O estado (UF) é obrigatório.')]
    #[Assert\Length(min: 2, max: 2, exactMessage: 'Use a sigla do estado com 2 letras (Ex: ES).')]
    private ?string $state = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return static
     */
    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTimeInterface $birthDate
     * @return static
     */
    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return Church|null
     */
    public function getChurch(): ?Church
    {
        return $this->church;
    }

    /**
     * @param Church|null $church
     * @return static
     */
    public function setChurch(?Church $church): static
    {
        $this->church = $church;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return static
     */
    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     * @return static
     */
    public function setCpf(string $cpf): static
    {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return static
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return static
     */
    public function setState(string $state): static
    {
        $this->state = $state;
        return $this;
    }
}
