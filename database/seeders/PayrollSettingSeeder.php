<?php

namespace Database\Seeders;

use App\Models\PayrollSetting;
use Illuminate\Database\Seeder;

class PayrollSettingSeeder extends Seeder
{
    public function run(): void
    {
        PayrollSetting::updateOrCreate(
            ['effective_from' => '2024-01-01'],
            [
                'effective_to'                         => null,
                'active'                               => true,
                'employee_social_insurance_rate'       => 8.00,
                'employee_health_insurance_rate'       => 1.50,
                'employee_unemployment_insurance_rate' => 1.00,
                'company_social_insurance_rate'        => 17.50,
                'company_health_insurance_rate'        => 3.00,
                'company_unemployment_insurance_rate'  => 1.00,
                'union_fee_rate'                       => 2.00,
                'family_deduction_amount'              => 11_000_000,
                'dependent_deduction_amount'           => 4_400_000,
            ]
        );

        $this->command->info('PayrollSetting seeded (VN 2024 rates).');
    }
}
