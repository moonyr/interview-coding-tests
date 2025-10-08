<?php

declare(strict_types=1);

namespace App\Domain\Store\Entity;

use App\Core\Exception\DomainException;
use DateTimeImmutable;

class Store
{
    public function __construct(
        private ?string $identifiant,
        private string $name,
        private string $address,
        private string $postalCode,
        private string $city,
        private string $country,
        private string $phoneNumber,
        private readonly \DateTimeImmutable $createdAt,
    ) {
        $this->validate();
    }

    public function update(
        ?string $name = null,
        ?string $address = null,
        ?string $postalCode = null,
        ?string $city = null,
        ?string $country = null,
        ?string $phoneNumber = null
    ): void {
        $fields = [
            'name' => $name,
            'address' => $address,
            'postalCode' => $postalCode,
            'city' => $city,
            'country' => $country,
            'phoneNumber' => $phoneNumber,
        ];

        foreach ($fields as $prop => $value) {
            if ($value !== null) {
                $this->$prop = $value;
            }
        }

        $this->validate();
    }

    public function validate(): void
    {
        if ($this->name === '') {
            throw new DomainException('Store name cannot be empty.');
        }

        if ($this->address === '') {
            throw new DomainException('Address cannot be empty.');
        }

        if ($this->postalCode === '') {
            throw new DomainException('Postal code cannot be empty.');
        }

        if ($this->city === '') {
            throw new DomainException('City cannot be empty.');
        }

        if ($this->country === '') {
            throw new DomainException('Country cannot be empty.');
        }

        if (!preg_match('/^\+?[0-9]{7,15}$/', $this->phoneNumber)) {
            throw new DomainException('Invalid phone number format.');
        }
    }

    public function getId(): string|null
    {
        return $this->identifiant;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
