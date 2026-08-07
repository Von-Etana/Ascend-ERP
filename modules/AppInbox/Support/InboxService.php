<?php

namespace Modules\AppInbox\Support;

use Illuminate\Support\Str;

class InboxService
{
    public function seededConversations(): array
    {
        return [
            ['id' => 1, 'provider' => 'messenger', 'name' => 'Sarah Ahmed', 'handle' => '@sarah.ahmed', 'initials' => 'SA', 'color' => '#8b2cc9', 'preview' => 'Do you still have the iPhone 15 in stock?', 'time' => '01:51 PM', 'unread' => 20, 'status' => 'open', 'mode' => 'ai', 'assigned' => 'AI Autoresponder', 'messages' => [['from' => 'contact', 'body' => 'Hello,', 'time' => '01:48 PM'], ['from' => 'contact', 'body' => 'Do you still have the iPhone 15 in stock?', 'time' => '01:48 PM'], ['from' => 'ai', 'body' => 'Yes! We have it in black and blue. Would you like me to reserve one for you?', 'time' => '01:48 PM'], ['from' => 'contact', 'body' => 'Black please.\n\nAnd how do I pay?', 'time' => '01:49 PM'], ['from' => 'ai', 'body' => "AI Auto-Reply · Great choice! I\'ve reserved a black iPhone 15. You can pay via bank or card — here\'s your secure link.", 'time' => '01:49 PM'], ['from' => 'human', 'body' => 'Most Welcome Sarah 👋', 'time' => '01:51 PM'], ['from' => 'human', 'body' => 'Have a Nice Day.', 'time' => '01:51 PM']]],
            ['id' => 2, 'provider' => 'whatsapp', 'name' => 'Hello', 'handle' => '+234 801 234 5678', 'initials' => 'H', 'color' => '#168f62', 'preview' => 'Hey Dear, I hope you are doing well. I\'d like to...', 'time' => 'Jun 21', 'unread' => 0, 'status' => 'open', 'mode' => 'human', 'assigned' => 'Michael Chen', 'messages' => [['from' => 'contact', 'body' => 'Hey Dear, I hope you are doing well. I\'d like to ask about your services.', 'time' => 'Jun 21']]],
            ['id' => 3, 'provider' => 'instagram', 'name' => 'Azmir Shabib Refun', 'handle' => '@azmir.refun', 'initials' => 'AS', 'color' => '#b0279f', 'preview' => 'Can you help me choose the right package?', 'time' => 'Jun 21', 'unread' => 0, 'status' => 'pending', 'mode' => 'human', 'assigned' => 'Sarah Williams', 'messages' => [['from' => 'contact', 'body' => 'Can you help me choose the right package?', 'time' => 'Jun 21']]],
            ['id' => 4, 'provider' => 'telegram', 'name' => 'Hello', 'handle' => '@hello_customer', 'initials' => 'H', 'color' => '#2795d9', 'preview' => 'I would like to know if delivery is available.', 'time' => 'Jun 21', 'unread' => 0, 'status' => 'open', 'mode' => 'ai', 'assigned' => 'AI Autoresponder', 'messages' => [['from' => 'contact', 'body' => 'I would like to know if delivery is available.', 'time' => 'Jun 21']]],
            ['id' => 5, 'provider' => 'whatsapp', 'name' => 'Hello', 'handle' => '+234 809 987 1234', 'initials' => 'H', 'color' => '#168f62', 'preview' => 'Thanks for the quick response.', 'time' => 'Jun 21', 'unread' => 0, 'status' => 'closed', 'mode' => 'human', 'assigned' => 'Emily Davis', 'messages' => [['from' => 'contact', 'body' => 'Thanks for the quick response.', 'time' => 'Jun 21']]],
            ['id' => 6, 'provider' => 'email', 'name' => 'Brighton Labs', 'handle' => 'hello@brightonlabs.com', 'initials' => 'BL', 'color' => '#475569', 'preview' => 'Re: Your proposal and next steps', 'time' => '12:42 PM', 'unread' => 3, 'status' => 'pending', 'mode' => 'human', 'assigned' => 'Sarah Williams', 'messages' => [['from' => 'contact', 'body' => 'Hi Sarah,\n\nThanks for sending the proposal. Can we schedule a call to discuss next steps?', 'time' => '12:42 PM']]],
        ];
    }

    public function providerLabel(string $key): string
    {
        return Str::headline($key);
    }
}
