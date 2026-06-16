<?php

namespace Database\Seeders;

use App\Enums\ContractType;
use App\Enums\DentalRole;
use App\Enums\EmploymentStatus;
use App\Enums\RoleType;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class BranchEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Branches
        $b1 = Branch::firstOrCreate(['code' => 'CN-0001'], [
            'name'      => 'Nha khoa Smile - Quận 1',
            'address'   => '123 Nguyễn Huệ, Quận 1, TP.HCM',
            'phone'     => '028 1234 5678',
            'is_active' => true,
        ]);

        $b2 = Branch::firstOrCreate(['code' => 'CN-0002'], [
            'name'      => 'Nha khoa Smile - Quận 7',
            'address'   => '456 Nguyễn Thị Thập, Quận 7, TP.HCM',
            'phone'     => '028 8765 4321',
            'is_active' => true,
        ]);

        // Departments
        $deptMap = [];
        $depts   = ['Bác sĩ tổng quát', 'Chỉnh nha', 'Implant', 'Phụ tá', 'Lễ tân', 'Tư vấn', 'Kế toán', 'Kho vật tư', 'CSKH'];
        foreach ($depts as $name) {
            $deptMap[$name] = Department::firstOrCreate(
                ['name' => $name, 'branch_id' => $b1->id],
                ['is_active' => true]
            );
        }

        $managerUser = User::where('email', 'manager@dental.local')->first();
        if ($managerUser) {
            $b1->update(['manager_id' => $managerUser->id]);
            $managerUser->update(['branch_id' => $b1->id]);
        }

        $staff = [
            [
                'full_name'         => 'BS. Nguyễn Văn Minh',
                'role_type'         => RoleType::Doctor,
                'branch'            => $b1,
                'email'             => 'doctor@dental.local',
                'specialization'    => 'Implant & Thẩm mỹ',
                'license_number'    => 'CCHN-12345',
                'position'          => 'Bác sĩ chính',
                'department'        => $deptMap['Implant'] ?? null,
                'contract_type'     => ContractType::FullTime,
                'employment_status' => EmploymentStatus::Active,
                'dental_role'       => DentalRole::Doctor,
                'dental_specialty'  => 'implant',
                'dentist_license_code' => 'BS-001',
                'clinical_permission'  => 'primary_doctor',
                'work_schedule'        => 'Full day',
                'default_kpi_rate'     => 15.00,
                'support_step_rate'    => 5.00,
                'base_salary'          => 25000000,
                'social_insurance_enabled' => true,
                'years_of_experience'  => 10,
                'start_date'           => '2020-01-15',
                'standard_working_days' => 26,
            ],
            [
                'full_name'         => 'BS. Trần Thị Hoa',
                'role_type'         => RoleType::Doctor,
                'branch'            => $b2,
                'email'             => null,
                'specialization'    => 'Niềng răng',
                'license_number'    => 'CCHN-67890',
                'position'          => 'Bác sĩ chỉnh nha',
                'department'        => null,
                'contract_type'     => ContractType::FullTime,
                'employment_status' => EmploymentStatus::Active,
                'dental_role'       => DentalRole::Doctor,
                'dental_specialty'  => 'orthodontics',
                'dentist_license_code' => 'BS-002',
                'clinical_permission'  => 'primary_doctor',
                'work_schedule'        => 'Ca sáng',
                'default_kpi_rate'     => 14.00,
                'base_salary'          => 22000000,
                'social_insurance_enabled' => true,
                'years_of_experience'  => 8,
                'start_date'           => '2021-03-01',
                'standard_working_days' => 26,
            ],
            [
                'full_name'         => 'BS. Lê Quang Dũng',
                'role_type'         => RoleType::Doctor,
                'branch'            => $b1,
                'email'             => null,
                'specialization'    => 'Nội nha',
                'license_number'    => 'CCHN-11111',
                'position'          => 'Bác sĩ',
                'department'        => $deptMap['Bác sĩ tổng quát'] ?? null,
                'contract_type'     => ContractType::FullTime,
                'employment_status' => EmploymentStatus::Active,
                'dental_role'       => DentalRole::Doctor,
                'dental_specialty'  => 'endodontics',
                'clinical_permission'  => 'primary_doctor',
                'work_schedule'        => 'Ca chiều',
                'default_kpi_rate'     => 13.00,
                'base_salary'          => 20000000,
                'social_insurance_enabled' => true,
                'years_of_experience'  => 6,
                'start_date'           => '2022-06-01',
                'standard_working_days' => 26,
            ],
            [
                'full_name'         => 'Phụ tá Nguyễn Thị Hoa',
                'role_type'         => RoleType::Assistant,
                'branch'            => $b1,
                'email'             => 'assistant@dental.local',
                'specialization'    => null,
                'license_number'    => null,
                'position'          => 'Phụ tá nha khoa',
                'department'        => $deptMap['Phụ tá'] ?? null,
                'contract_type'     => ContractType::FullTime,
                'employment_status' => EmploymentStatus::Active,
                'dental_role'       => DentalRole::Assistant,
                'clinical_permission'  => 'assistant',
                'work_schedule'        => 'Full day',
                'support_step_rate'    => 8.00,
                'base_salary'          => 9000000,
                'social_insurance_enabled' => true,
                'xray_scan_skill'      => 'basic',
                'start_date'           => '2022-09-01',
                'standard_working_days' => 26,
                'lunch_allowance'      => 500000,
                'travel_allowance'     => 300000,
            ],
            [
                'full_name'         => 'Lễ tân Trần Thị Ngọc',
                'role_type'         => RoleType::Receptionist,
                'branch'            => $b1,
                'email'             => 'receptionist@dental.local',
                'specialization'    => null,
                'license_number'    => null,
                'position'          => 'Lễ tân trưởng',
                'department'        => $deptMap['Lễ tân'] ?? null,
                'contract_type'     => ContractType::FullTime,
                'employment_status' => EmploymentStatus::Active,
                'dental_role'       => DentalRole::Receptionist,
                'clinical_permission'  => 'none',
                'work_schedule'        => 'Full day',
                'base_salary'          => 8000000,
                'social_insurance_enabled' => true,
                'start_date'           => '2021-11-01',
                'lunch_allowance'      => 500000,
                'phone_allowance'      => 200000,
                'standard_working_days' => 26,
            ],
            [
                'full_name'         => 'Tư vấn Phạm Thị Lan',
                'role_type'         => RoleType::Consultant,
                'branch'            => $b1,
                'email'             => 'consultant@dental.local',
                'specialization'    => null,
                'license_number'    => null,
                'position'          => 'Tư vấn viên',
                'department'        => $deptMap['Tư vấn'] ?? null,
                'contract_type'     => ContractType::FullTime,
                'employment_status' => EmploymentStatus::Active,
                'dental_role'       => DentalRole::Consultant,
                'clinical_permission'  => 'none',
                'base_salary'          => 9000000,
                'social_insurance_enabled' => true,
                'start_date'           => '2022-02-01',
                'lunch_allowance'      => 500000,
                'travel_allowance'     => 300000,
                'standard_working_days' => 26,
            ],
            [
                'full_name'         => 'Thu ngân Hoàng Thị Linh',
                'role_type'         => RoleType::Cashier,
                'branch'            => $b1,
                'email'             => 'cashier@dental.local',
                'specialization'    => null,
                'license_number'    => null,
                'position'          => 'Kế toán viên',
                'department'        => $deptMap['Kế toán'] ?? null,
                'contract_type'     => ContractType::FullTime,
                'employment_status' => EmploymentStatus::Active,
                'dental_role'       => DentalRole::Accountant,
                'base_salary'       => 10000000,
                'social_insurance_enabled' => true,
                'start_date'        => '2021-05-01',
                'standard_working_days' => 26,
            ],
            [
                'full_name'         => 'Kế toán Vũ Thị Thủy',
                'role_type'         => RoleType::Accountant,
                'branch'            => $b1,
                'email'             => 'accountant@dental.local',
                'specialization'    => null,
                'license_number'    => null,
                'position'          => 'Kế toán viên',
                'department'        => $deptMap['Kế toán'] ?? null,
                'contract_type'     => ContractType::FullTime,
                'employment_status' => EmploymentStatus::Active,
                'dental_role'       => DentalRole::Accountant,
                'base_salary'       => 11000000,
                'social_insurance_enabled' => true,
                'start_date'        => '2020-08-01',
                'standard_working_days' => 26,
            ],
            [
                'full_name'         => 'Quản lý CN1',
                'role_type'         => RoleType::Manager,
                'branch'            => $b1,
                'email'             => 'manager@dental.local',
                'specialization'    => null,
                'license_number'    => null,
                'position'          => 'Quản lý phòng khám',
                'department'        => null,
                'contract_type'     => ContractType::FullTime,
                'employment_status' => EmploymentStatus::Active,
                'dental_role'       => DentalRole::Manager,
                'clinical_permission'  => 'none',
                'base_salary'          => 18000000,
                'responsibility_allowance' => 2000000,
                'social_insurance_enabled' => true,
                'start_date'           => '2019-06-01',
                'standard_working_days' => 26,
            ],
        ];

        foreach ($staff as $s) {
            $userId = null;
            if (! empty($s['email'])) {
                $user = User::where('email', $s['email'])->first();
                if ($user) {
                    $userId = $user->id;
                    $user->update(['branch_id' => $s['branch']->id]);
                }
            }

            $existing = Employee::withTrashed()
                ->where('branch_id', $s['branch']->id)
                ->where('full_name', $s['full_name'])
                ->first();

            if (! $existing) {
                Employee::create([
                    'code'               => Employee::generateCode(),
                    'user_id'            => $userId,
                    'branch_id'          => $s['branch']->id,
                    'department_id'      => $s['department']?->id,
                    'full_name'          => $s['full_name'],
                    'phone'              => null,
                    'role_type'          => $s['role_type']->value,
                    'specialization'     => $s['specialization'],
                    'license_number'     => $s['license_number'],
                    'is_active'          => true,
                    'position'           => $s['position'] ?? null,
                    'contract_type'      => $s['contract_type']?->value,
                    'employment_status'  => $s['employment_status']?->value ?? EmploymentStatus::Active->value,
                    'dental_role'        => $s['dental_role']?->value,
                    'dental_specialty'   => $s['dental_specialty'] ?? null,
                    'dentist_license_code' => $s['dentist_license_code'] ?? null,
                    'clinical_permission'  => $s['clinical_permission'] ?? null,
                    'work_schedule'        => $s['work_schedule'] ?? null,
                    'default_kpi_rate'     => $s['default_kpi_rate'] ?? null,
                    'support_step_rate'    => $s['support_step_rate'] ?? null,
                    'base_salary'          => $s['base_salary'] ?? 0,
                    'social_insurance_enabled' => $s['social_insurance_enabled'] ?? false,
                    'years_of_experience'  => $s['years_of_experience'] ?? null,
                    'xray_scan_skill'      => $s['xray_scan_skill'] ?? null,
                    'start_date'           => $s['start_date'] ?? null,
                    'standard_working_days' => $s['standard_working_days'] ?? 26,
                    'lunch_allowance'      => $s['lunch_allowance'] ?? 0,
                    'travel_allowance'     => $s['travel_allowance'] ?? 0,
                    'phone_allowance'      => $s['phone_allowance'] ?? 0,
                    'responsibility_allowance' => $s['responsibility_allowance'] ?? 0,
                ]);
            }
        }

        $this->command->info('Branches seeded: 2 | Employees seeded: '.Employee::count());
    }
}
