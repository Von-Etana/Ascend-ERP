<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebLeadCapture extends Model
{
    protected $fillable = [
        'lead_type',
        'client_name',
        'job_title',
        'company_name',
        'website',
        'country',
        'phone',
        'email',
        'preferred_contact_method',
        'city_location',
        'property_type',
        'installation_address',
        'system_interest',
        'product_interest',
        'daily_generator_hours',
        'monthly_fuel_spend_ngn',
        'monthly_sales_volume',
        'customer_type',
        'roof_mounting_type',
        'estimated_budget_ngn',
        'purchasing_timeline',
        'financing_preference',
        'referral_code',
        'ai_lead_score',
        'ai_qualification_summary',
        'special_notes',
        'source_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'estimated_budget_ngn' => 'float',
            'monthly_fuel_spend_ngn' => 'float',
            'daily_generator_hours' => 'integer',
            'ai_lead_score' => 'integer',
        ];
    }
}
