<?php

namespace AlexKassel\ScraperCore\DTOs;

use AlexKassel\ScraperCore\Enums\SpiderResultStatus;
use AlexKassel\ScraperCore\Enums\ItemMissingCause;
use AlexKassel\ScraperCore\Exceptions\ContractViolationException;

readonly class SpiderResult
{
    public function __construct(
        public string $externalId,
        public SpiderResultStatus $status,
        public ?array $payload = null,
        public ?string $contentType = null,
        public ?ItemMissingCause $missingCause = null,
        public ?string $errorMessage = null,
        public array $metadata = []
    ) {
        $this->validateInvariants();
    }

    private function validateInvariants(): void
    {
        if (trim($this->externalId) === '') {
            throw new ContractViolationException("SpiderResult externalId must not be empty.");
        }

        if ($this->payload === null && $this->contentType !== null) {
            throw new ContractViolationException("contentType must be null when payload is null.");
        }

        if ($this->status === SpiderResultStatus::Missing && $this->missingCause === null) {
            throw new ContractViolationException("missingCause must be provided when SpiderResultStatus is Missing.");
        }
    }
}
