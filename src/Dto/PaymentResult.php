<?php
declare(strict_types=1);

namespace Xamin\App\Dto;

class PaymentResult
{
    /**
     * @var bool
     */
    private $success;
    /**
     * @var string[]
     */
    private $errors;

    /**
     * @param bool $success
     * @param string[] $errors
     */
    public function __construct(bool $success, array $errors)
    {
        $this->success = $success;
        $this->errors = $errors;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}