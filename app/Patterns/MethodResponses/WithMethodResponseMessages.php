<?php

namespace App\Patterns\MethodResponses;

use Symfony\Component\Translation\TranslatableMessage;

/**
 * Trait WithMethodResponseMessages
 */
trait WithMethodResponseMessages
{
    /**
     * @var TranslatableMessage[]
     */
    private array $messages;

    /**
     * @param string $messagePath
     * @return bool
     */
    public function hasMessage(string $messagePath): bool
    {
        if (empty($this->messages)) {
            return false;
        }

        foreach ($this->messages as $validationMessage) {
            if ($messagePath === $validationMessage->getMessage()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasMessages(): bool
    {
        return !empty($this->messages);
    }

    /**
     * @return TranslatableMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     * @return $this
     */
    public function setMessages(array $messages): static
    {
        $this->messages = $messages;
        return $this;
    }
}
