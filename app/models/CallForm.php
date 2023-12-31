<?php

namespace App\Models;

use App\Dto\FileDto;
use App\Dto\UploadFileDto;
use App\Exceptions\FileIsAlreadyExistsException;
use App\Interfaces\CallFormFileDeleteInterface;
use App\Interfaces\CallFormFileUploadInterface;
use App\Utils\Email;
use App\Utils\Phone;

class CallForm {
    public function __construct(
        private readonly string $id,
        private string $comment,
        private string $fullName,
        private string $companyName,
        private int $employeeNumber,
        private Phone $phone,
        private Email $email,
        private array $files = []
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getEmployeeNumber(): int
    {
        return $this->employeeNumber;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @throws FileIsAlreadyExistsException
     */
    public function addFile(
        FileDto $dto,
        CallFormFileUploadInterface $storage
    ): void {
        $storage->upload(new UploadFileDto(
            $dto->tmpName,
            $dto->name,
            $this->getId()
        ));

        $this->files []= $dto->name;
    }

    public function removeAllFiles(
        CallFormFileDeleteInterface $storage
    ): void {
        $storage->deleteAll($this->getId());

        $this->files = [];
    }
}
