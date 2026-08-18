<?php

namespace Modules\AppInbox\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\AppAscend\Services\WhatsAppNotificationService;
use Modules\AppEmail\Models\Lead;

class WhatsAppMenuFlowService
{
    public const SESSION_TTL_SECONDS = 21600; // 6 hours

    public const MENU_TEXT = "Welcome to Ascend Systems — Nigeria's solar, security & automation partner.\n\n"
        . "We've powered 150,000+ homes and businesses across Nigeria with solar, CCTV, smart automation, and network infrastructure.\n\n"
        . "How can we help you today? Reply with a number:\n\n"
        . "1️⃣ Solar power for my home or business\n"
        . "2️⃣ Security systems (CCTV, access control, cybersecurity)\n"
        . "3️⃣ Smart home / building automation\n"
        . "4️⃣ Solar financing — check what I qualify for\n"
        . "5️⃣ Join our dealer network\n"
        . "6️⃣ Talk to a human\n\n"
        . "Reply \"menu\" anytime to see this again.";

    public const UNRECOGNIZED_TEXT = "Sorry, I didn't catch that. Here's the menu again:\n\n" . self::MENU_TEXT;

    public const BRANCHES = [
        'solar' => [
            'label' => 'Solar',
            'intro' => "Great — solar can be sized for your home or for a business/factory.\n\nReply:\nA) Residential (home)\nB) Commercial / Industrial",
        ],
        'solar_residential' => "We design 3kVA–30kVA systems custom-fit to Nigerian residential load profiles — built to beat grid blackouts permanently.\n\nNext step: we'll get your details across to our team for a free site audit.",
        'solar_commercial' => "Commercial & industrial solar typically cuts diesel/grid costs by 40–70%, pays back in 3–5 years, and runs 25+ years with 99.5%+ uptime.\n\nNext step: we'll get your details across to our team for a free site audit.",
        'security' => [
            'label' => 'Security',
            'intro' => "We offer three security services:\n\nA) CCTV systems (HD cameras, 24/7 monitoring, cloud storage)\nB) Access control (biometrics, keycards, mobile access)\nC) Cybersecurity (firewalls, threat detection, security audits)\n\nReply A, B, or C — or \"all\" if you'd like a full security audit.",
        ],
        'automation' => [
            'label' => 'Automation',
            'intro' => "Smart automation for your space — reply:\n\nA) Residential (smart home, security automation, energy management)\nB) Commercial (building management, access control, smart lighting/AV)",
        ],
        'automation_residential' => "Residential automation covers smart home control, automated gates/locks, and energy-efficient lighting & HVAC scheduling.\n\nNext step: we'll get your details across for a free site survey.",
        'automation_commercial' => "Commercial automation covers building management systems (BMS), access control, and occupancy-based lighting/AV for offices and enterprises.\n\nNext step: we'll get your details across for a free site survey.",
        'financing' => [
            'label' => 'Financing',
            'intro' => "Let's check what you qualify for. Are you:\n\nA) Individual\nB) Business owner\nC) Corporate entity",
        ],
        'financing_result' => "We work with Stanbic IBTC, Access Bank, and Providus Bank on solar asset financing — tenors up to 60 months, and equity requirements starting from 0% depending on the bank.\n\nNext step: we'll get your details across so our team can confirm which bank fits you best.",
        'dealer' => "Ascend Systems runs Nigeria's fastest-growing solar dealer network, with factory-direct margins on premium LithTech equipment.\n\nNext step: we'll pass your details to our partnerships team.",
        'human' => "No problem — let's get you connected to a member of our team.",
    ];

    public function __construct(
        protected WhatsAppNotificationService $notificationService,
    ) {}

    public function isMenuTrigger(string $phone, string $rawText): bool
    {
        $text = $this->normalize($rawText);
        $session = $this->getSession($phone);

        // If user already has an active qualification state (other than START or MENU), process with menu flow
        if (! empty($session['state']) && ! in_array($session['state'], ['START', 'MENU', 'DONE'], true)) {
            return true;
        }

        // Keywords that initiate menu
        if ($text === 'menu' || $text === 'hi' || $text === 'hello' || $text === 'start' || in_array($text, ['1', '2', '3', '4', '5', '6'], true)) {
            return true;
        }

        return $this->matchesAny($text, ['solar', 'security', 'cctv', 'automation', 'financ', 'loan', 'dealer', 'human', 'agent']);
    }

    public function handle(string $phone, string $rawText): array
    {
        $session = $this->getSession($phone);
        $text = $this->normalize($rawText);

        if ($text === 'menu') {
            $session['state'] = 'MENU';
            $session['data'] = [];
            $this->saveSession($phone, $session);

            return [
                'reply' => self::MENU_TEXT,
                'session' => $session,
                'lead_complete' => false,
                'handoff' => false,
            ];
        }

        $result = $this->route($session, $rawText);
        $this->saveSession($phone, $result['session']);

        if (! empty($result['lead_complete'])) {
            $lead = $this->saveLead($phone, $result['session']['data'] ?? []);
            $result['lead'] = $lead;
        }

        return $result;
    }

    protected function route(array $session, string $rawText): array
    {
        $text = $this->normalize($rawText);
        $state = $session['state'] ?? 'START';
        $data = $session['data'] ?? [];

        switch ($state) {
            case 'START':
                $session['state'] = 'MENU';

                return [
                    'reply' => self::MENU_TEXT,
                    'session' => $session,
                    'lead_complete' => false,
                    'handoff' => false,
                ];

            case 'MENU':
                if ($text === '1' || $this->matchesAny($text, ['solar'])) {
                    $session['state'] = 'SOLAR_TYPE';
                    $session['data']['interest'] = 'Solar';

                    return [
                        'reply' => self::BRANCHES['solar']['intro'],
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                if ($text === '2' || $this->matchesAny($text, ['security', 'cctv'])) {
                    $session['state'] = 'SECURITY_PICK';
                    $session['data']['interest'] = 'Security';

                    return [
                        'reply' => self::BRANCHES['security']['intro'],
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                if ($text === '3' || $this->matchesAny($text, ['automation', 'smart home'])) {
                    $session['state'] = 'AUTOMATION_TYPE';
                    $session['data']['interest'] = 'Automation';

                    return [
                        'reply' => self::BRANCHES['automation']['intro'],
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                if ($text === '4' || $this->matchesAny($text, ['financ', 'loan', 'eligib'])) {
                    $session['state'] = 'FINANCING_TYPE';
                    $session['data']['interest'] = 'Financing';

                    return [
                        'reply' => self::BRANCHES['financing']['intro'],
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                if ($text === '5' || $this->matchesAny($text, ['dealer'])) {
                    $session['state'] = 'LEAD_NAME';
                    $session['data']['interest'] = 'Dealer network';

                    return [
                        'reply' => self::BRANCHES['dealer'] . "\n\nFirst, what's your name?",
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                if ($text === '6' || $this->matchesAny($text, ['human', 'agent', 'talk'])) {
                    $session['state'] = 'LEAD_NAME';
                    $session['data']['interest'] = 'Direct human request';
                    $session['data']['priority'] = 'high';

                    return [
                        'reply' => self::BRANCHES['human'] . "\n\nFirst, what's your name?",
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => true,
                    ];
                }

                return [
                    'reply' => self::UNRECOGNIZED_TEXT,
                    'session' => $session,
                    'lead_complete' => false,
                    'handoff' => false,
                ];

            case 'SOLAR_TYPE':
                if ($this->matchesAny($text, ['a', 'residential', 'home'])) {
                    $session['data']['interest'] = 'Solar — residential';
                    $session['state'] = 'LEAD_NAME';

                    return [
                        'reply' => self::BRANCHES['solar_residential'] . "\n\nWhat's your name?",
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                if ($this->matchesAny($text, ['b', 'commercial', 'industrial'])) {
                    $session['data']['interest'] = 'Solar — commercial/industrial';
                    $session['state'] = 'LEAD_NAME';

                    return [
                        'reply' => self::BRANCHES['solar_commercial'] . "\n\nWhat's your name?",
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                return [
                    'reply' => "Please reply A for Residential or B for Commercial/Industrial.",
                    'session' => $session,
                    'lead_complete' => false,
                    'handoff' => false,
                ];

            case 'SECURITY_PICK':
                if ($this->matchesAny($text, ['a', 'cctv'])) {
                    $session['data']['interest'] = 'Security — CCTV';
                } elseif ($this->matchesAny($text, ['b', 'access'])) {
                    $session['data']['interest'] = 'Security — Access control';
                } elseif ($this->matchesAny($text, ['c', 'cyber'])) {
                    $session['data']['interest'] = 'Security — Cybersecurity';
                } elseif ($this->matchesAny($text, ['all'])) {
                    $session['data']['interest'] = 'Security — Full audit';
                } else {
                    return [
                        'reply' => "Please reply A, B, C, or \"all\".",
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                $session['state'] = 'LEAD_NAME';

                return [
                    'reply' => "Got it. Let's get you a free quote — what's your name?",
                    'session' => $session,
                    'lead_complete' => false,
                    'handoff' => false,
                ];

            case 'AUTOMATION_TYPE':
                if ($this->matchesAny($text, ['a', 'residential', 'home'])) {
                    $session['data']['interest'] = 'Automation — residential';
                    $session['state'] = 'LEAD_NAME';

                    return [
                        'reply' => self::BRANCHES['automation_residential'] . "\n\nWhat's your name?",
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                if ($this->matchesAny($text, ['b', 'commercial'])) {
                    $session['data']['interest'] = 'Automation — commercial';
                    $session['state'] = 'LEAD_NAME';

                    return [
                        'reply' => self::BRANCHES['automation_commercial'] . "\n\nWhat's your name?",
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                return [
                    'reply' => "Please reply A for Residential or B for Commercial.",
                    'session' => $session,
                    'lead_complete' => false,
                    'handoff' => false,
                ];

            case 'FINANCING_TYPE':
                if ($this->matchesAny($text, ['a', 'individual'])) {
                    $session['data']['buyerType'] = 'Individual';
                } elseif ($this->matchesAny($text, ['b', 'business'])) {
                    $session['data']['buyerType'] = 'Business owner';
                } elseif ($this->matchesAny($text, ['c', 'corporate'])) {
                    $session['data']['buyerType'] = 'Corporate entity';
                } else {
                    return [
                        'reply' => "Please reply A, B, or C.",
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                $session['state'] = 'LEAD_NAME';

                return [
                    'reply' => self::BRANCHES['financing_result'] . "\n\nWhat's your name?",
                    'session' => $session,
                    'lead_complete' => false,
                    'handoff' => false,
                ];

            case 'LEAD_NAME':
                $session['data']['name'] = trim($rawText);
                if (! empty($session['data']['buyerType'])) {
                    $session['state'] = 'LEAD_LOCATION';

                    return [
                        'reply' => "Thanks {$session['data']['name']}. What city/area are you in?",
                        'session' => $session,
                        'lead_complete' => false,
                        'handoff' => false,
                    ];
                }

                $session['state'] = 'LEAD_TYPE';

                return [
                    'reply' => "Thanks {$session['data']['name']}. Are you reaching out as an Individual, Business owner, or Corporate entity?",
                    'session' => $session,
                    'lead_complete' => false,
                    'handoff' => false,
                ];

            case 'LEAD_TYPE':
                $session['data']['buyerType'] = trim($rawText);
                $session['state'] = 'LEAD_LOCATION';

                return [
                    'reply' => "Got it. What city/area are you in?",
                    'session' => $session,
                    'lead_complete' => false,
                    'handoff' => false,
                ];

            case 'LEAD_LOCATION':
                $session['data']['location'] = trim($rawText);
                $session['state'] = 'DONE';
                $interest = $session['data']['interest'] ?? 'our services';
                $name = $session['data']['name'] ?? 'there';

                return [
                    'reply' => "Perfect — thanks {$name}. A member of our team will reach out shortly about {$interest}. You can reply \"menu\" anytime to explore something else.",
                    'session' => $session,
                    'lead_complete' => true,
                    'handoff' => ! empty($session['data']['priority']) && $session['data']['priority'] === 'high',
                ];

            case 'DONE':
            default:
                $session['state'] = 'MENU';

                return [
                    'reply' => self::MENU_TEXT,
                    'session' => $session,
                    'lead_complete' => false,
                    'handoff' => false,
                ];
        }
    }

    public function getSession(string $phone): array
    {
        $cleanPhone = $this->cleanPhone($phone);
        $key = "whatsapp_flow_session:{$cleanPhone}";

        return Cache::get($key, [
            'state' => 'START',
            'data' => [],
            'updated_at' => now()->timestamp,
        ]);
    }

    public function saveSession(string $phone, array $session): void
    {
        $cleanPhone = $this->cleanPhone($phone);
        $session['updated_at'] = now()->timestamp;
        Cache::put("whatsapp_flow_session:{$cleanPhone}", $session, self::SESSION_TTL_SECONDS);
    }

    public function resetSession(string $phone): void
    {
        $cleanPhone = $this->cleanPhone($phone);
        Cache::forget("whatsapp_flow_session:{$cleanPhone}");
    }

    public function saveLead(string $phone, array $leadData): ?Lead
    {
        try {
            $name = $leadData['name'] ?? 'WhatsApp Prospect';
            $buyerType = $leadData['buyerType'] ?? 'General';
            $location = $leadData['location'] ?? 'Nigeria';
            $interest = $leadData['interest'] ?? 'General Inquiry';

            $lead = Lead::create([
                'name' => $name,
                'phone' => $phone,
                'source' => 'whatsapp_bot',
                'status' => 'new',
                'score' => 80,
                'company_name' => Str::contains(strtolower($buyerType), ['business', 'corporate']) ? $name : null,
                'metadata' => [
                    'interest' => $interest,
                    'buyer_type' => $buyerType,
                    'location' => $location,
                    'priority' => $leadData['priority'] ?? 'normal',
                    'captured_at' => now()->toIso8601String(),
                ],
            ]);

            return $lead;
        } catch (\Throwable $e) {
            Log::error("Failed to save WhatsApp lead: " . $e->getMessage(), [
                'phone' => $phone,
                'data' => $leadData,
            ]);

            return null;
        }
    }

    protected function cleanPhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone) ?: $phone;
    }

    protected function normalize(?string $text): string
    {
        return trim(strtolower((string) $text));
    }

    protected function matchesAny(string $text, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
