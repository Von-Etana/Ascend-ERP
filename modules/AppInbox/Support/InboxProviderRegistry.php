<?php

namespace Modules\AppInbox\Support;

use InvalidArgumentException;
use Modules\AppInbox\Contracts\InboxProvider;
use Modules\AppInbox\Providers\EmailProvider;
use Modules\AppInbox\Providers\InstagramProvider;
use Modules\AppInbox\Providers\MessengerProvider;
use Modules\AppInbox\Providers\TelegramProvider;
use Modules\AppInbox\Providers\WhatsAppProvider;

class InboxProviderRegistry
{
    /** @var array<string, InboxProvider> */
    protected array $providers = [];

    public function register(InboxProvider $provider): void
    {
        $this->providers[$provider->key()] = $provider;
    }

    public function registerDefaults(): void
    {
        foreach ([new WhatsAppProvider, new InstagramProvider, new MessengerProvider, new TelegramProvider, new EmailProvider] as $provider) {
            $this->register($provider);
        }
    }

    public function get(string $key): InboxProvider
    {
        if (! isset($this->providers[$key])) {
            throw new InvalidArgumentException('Unsupported inbox provider: '.$key);
        }

        return $this->providers[$key];
    }

    public function all(): array
    {
        return $this->providers;
    }
}
