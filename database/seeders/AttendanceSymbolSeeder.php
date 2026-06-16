<?php

namespace Database\Seeders;

use App\Models\AttendanceSymbol;
use Illuminate\Database\Seeder;

class AttendanceSymbolSeeder extends Seeder
{
    public function run(): void
    {
        $symbols = [
            ['code' => 'X',  'label' => 'Đi làm',           'color' => 'green',  'is_paid' => true,  'counts_as_workday' => true,  'counts_as_leave' => false, 'counts_as_unpaid_leave' => false, 'counts_as_overtime' => false, 'default_paid_workday' => 1.0],
            ['code' => 'P',  'label' => 'Nghỉ phép',         'color' => 'blue',   'is_paid' => true,  'counts_as_workday' => false, 'counts_as_leave' => true,  'counts_as_unpaid_leave' => false, 'counts_as_overtime' => false, 'default_paid_workday' => 1.0],
            ['code' => 'KP', 'label' => 'Nghỉ không phép',   'color' => 'red',    'is_paid' => false, 'counts_as_workday' => false, 'counts_as_leave' => false, 'counts_as_unpaid_leave' => true,  'counts_as_overtime' => false, 'default_paid_workday' => 0.0],
            ['code' => 'L',  'label' => 'Nghỉ lễ',           'color' => 'purple', 'is_paid' => true,  'counts_as_workday' => false, 'counts_as_leave' => true,  'counts_as_unpaid_leave' => false, 'counts_as_overtime' => false, 'default_paid_workday' => 1.0],
            ['code' => 'CT', 'label' => 'Công tác',          'color' => 'teal',   'is_paid' => true,  'counts_as_workday' => true,  'counts_as_leave' => false, 'counts_as_unpaid_leave' => false, 'counts_as_overtime' => false, 'default_paid_workday' => 1.0],
            ['code' => 'OT', 'label' => 'Tăng ca',           'color' => 'orange', 'is_paid' => true,  'counts_as_workday' => false, 'counts_as_leave' => false, 'counts_as_unpaid_leave' => false, 'counts_as_overtime' => true,  'default_paid_workday' => 0.0],
            ['code' => 'NB', 'label' => 'Nghỉ bù',           'color' => 'violet', 'is_paid' => true,  'counts_as_workday' => false, 'counts_as_leave' => true,  'counts_as_unpaid_leave' => false, 'counts_as_overtime' => false, 'default_paid_workday' => 1.0],
            ['code' => 'O',  'label' => 'Ốm',                'color' => 'yellow', 'is_paid' => true,  'counts_as_workday' => false, 'counts_as_leave' => true,  'counts_as_unpaid_leave' => false, 'counts_as_overtime' => false, 'default_paid_workday' => 1.0],
            ['code' => 'TS', 'label' => 'Thai sản',          'color' => 'pink',   'is_paid' => true,  'counts_as_workday' => false, 'counts_as_leave' => true,  'counts_as_unpaid_leave' => false, 'counts_as_overtime' => false, 'default_paid_workday' => 1.0],
        ];

        foreach ($symbols as $s) {
            AttendanceSymbol::updateOrCreate(['code' => $s['code']], array_merge($s, ['active' => true]));
        }

        $this->command->info('Attendance symbols seeded: ' . count($symbols));
    }
}
