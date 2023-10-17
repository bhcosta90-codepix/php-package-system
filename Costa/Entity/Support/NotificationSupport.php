<?php

declare(strict_types=1);

namespace Costa\Entity\Support;

class NotificationSupport
{
    private array $errors = [];

    public function push(string $context, string $message): void
    {
        $this->errors[] = [
            'context' => $context,
            'message' => $message,
        ];
    }

    public function has($property = 'errors'): int
    {
        return count($this->{$property});
    }

    public function messages(string $context = '', $property = 'errors'): string
    {
        $messages = '';

        foreach ($this->{$property} as $error) {
            if ($context === '' || $error['context'] == $context) {
                $messages .= "{$error['context']}: {$error['message']},";
            }
        }

        return $messages;
    }
}