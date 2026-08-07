<?php

namespace Modules\AppEmail\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;
use Modules\AppEmail\Models\Lead;

#[Title('Leads - Ascend Systems')]
class Leads extends Component
{
    public string $search = '';

    public string $status = 'all';

    public string $notice = '';

    public bool $showCreate = false;

    public string $newLeadName = '';

    public string $newLeadEmail = '';

    public string $newLeadSource = 'manual';

    public function createLead(): void
    {
        $this->showCreate = true;
    }

    public function saveLead(): void
    {
        $this->validate(['newLeadName' => ['required', 'string', 'max:160'], 'newLeadEmail' => ['nullable', 'email', 'max:255'], 'newLeadSource' => ['required', 'string', 'max:50']]);
        Lead::create(['name' => $this->newLeadName, 'email' => $this->newLeadEmail ?: null, 'source' => $this->newLeadSource, 'status' => 'new', 'score' => 0]);
        $this->reset(['newLeadName', 'newLeadEmail']);
        $this->newLeadSource = 'manual';
        $this->showCreate = false;
        $this->notice = __('Lead added to Ascend CRM.');
    }

    public function render(): View
    {
        $seededLeads = collect([
            ['name' => 'Brighton Labs', 'email' => 'hello@brightonlabs.com', 'source' => 'Email', 'status' => 'qualified', 'score' => 92, 'owner' => 'Sarah Williams'],
            ['name' => 'Horizon Media', 'email' => 'team@horizonmedia.co', 'source' => 'Instagram', 'status' => 'new', 'score' => 78, 'owner' => 'Unassigned'],
            ['name' => 'Northbridge Ltd', 'email' => 'procurement@northbridge.com', 'source' => 'Website form', 'status' => 'contacted', 'score' => 71, 'owner' => 'Michael Chen'],
            ['name' => 'Summit Retail', 'email' => 'ops@summitretail.com', 'source' => 'WhatsApp', 'status' => 'qualified', 'score' => 86, 'owner' => 'Emily Davis'],
        ]);
        $leads = collect();
        try {
            $leads = Lead::query()->latest()->get()->map(fn (Lead $lead): array => ['name' => $lead->name, 'email' => $lead->email ?: '—', 'source' => Str::headline($lead->source), 'status' => $lead->status, 'score' => $lead->score, 'owner' => $lead->owner?->name ?: 'Unassigned']);
        } catch (\Throwable) {
            $leads = collect();
        }
        if ($leads->isEmpty()) {
            $leads = $seededLeads;
        }
        $leads = $leads->when($this->status !== 'all', fn ($items) => $items->where('status', $this->status))->when(trim($this->search) !== '', function ($items): mixed {
            $term = Str::lower(trim($this->search));

            return $items->filter(fn (array $lead): bool => Str::contains(Str::lower($lead['name'].' '.$lead['email'].' '.$lead['source']), $term));
        })->values();

        return view('appemail::livewire.leads', ['leads' => $leads])->layout(theme_view('layouts.app', 'app'), ['title' => __('Leads - Ascend Systems')]);
    }
}
