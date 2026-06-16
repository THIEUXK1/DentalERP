<?php

namespace Database\Seeders;

use App\Models\KpiQualityRule;
use Illuminate\Database\Seeder;

class KpiQualityRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'rule_code'      => 'FULL_REFUND',
                'rule_name'      => 'Hoàn trả toàn bộ chi phí',
                'quality_factor' => 0.00,
                'hold_kpi'       => false,
                'reverse_kpi'    => true,
                'trigger_event'  => 'refund',
                'description'    => 'Bệnh nhân được hoàn tiền toàn bộ — đảo ngược KPI đã tích lũy.',
            ],
            [
                'rule_code'      => 'PARTIAL_REFUND',
                'rule_name'      => 'Hoàn trả một phần',
                'quality_factor' => 0.50,
                'hold_kpi'       => false,
                'reverse_kpi'    => false,
                'trigger_event'  => 'refund',
                'description'    => 'Hoàn trả một phần — KPI giảm 50% tương ứng.',
            ],
            [
                'rule_code'      => 'REDO_COMPLAINT',
                'rule_name'      => 'Làm lại do khiếu nại chất lượng',
                'quality_factor' => 0.00,
                'hold_kpi'       => true,
                'reverse_kpi'    => false,
                'trigger_event'  => 'redo',
                'description'    => 'Phải làm lại dịch vụ — treo KPI đến khi hoàn thành lại.',
            ],
            [
                'rule_code'      => 'WARRANTY_REDO',
                'rule_name'      => 'Bảo hành — làm lại trong thời hạn',
                'quality_factor' => 0.00,
                'hold_kpi'       => false,
                'reverse_kpi'    => true,
                'trigger_event'  => 'warranty_claim',
                'description'    => 'Dịch vụ trong thời hạn bảo hành phải làm lại miễn phí — đảo KPI.',
            ],
            [
                'rule_code'      => 'PATIENT_COMPLAINT',
                'rule_name'      => 'Khiếu nại có ghi nhận',
                'quality_factor' => 0.70,
                'hold_kpi'       => false,
                'reverse_kpi'    => false,
                'trigger_event'  => 'complaint',
                'description'    => 'Có khiếu nại từ bệnh nhân nhưng không phải làm lại — giảm 30% KPI.',
            ],
            [
                'rule_code'      => 'MISSING_ATTACHMENT',
                'rule_name'      => 'Thiếu hồ sơ/hình ảnh bắt buộc',
                'quality_factor' => 1.00,
                'hold_kpi'       => true,
                'reverse_kpi'    => false,
                'trigger_event'  => 'missing_attachment',
                'description'    => 'Công đoạn yêu cầu file đính kèm nhưng chưa có — treo KPI đến khi bổ sung.',
            ],
            [
                'rule_code'      => 'PROTOCOL_VIOLATION',
                'rule_name'      => 'Vi phạm quy trình lâm sàng',
                'quality_factor' => 0.50,
                'hold_kpi'       => false,
                'reverse_kpi'    => false,
                'trigger_event'  => 'quality_check_failed',
                'description'    => 'Không tuân thủ quy trình chuẩn — giảm 50% KPI.',
            ],
            [
                'rule_code'      => 'CANCELLED_SERVICE',
                'rule_name'      => 'Hủy dịch vụ sau khi bắt đầu',
                'quality_factor' => 0.00,
                'hold_kpi'       => false,
                'reverse_kpi'    => true,
                'trigger_event'  => 'cancelled',
                'description'    => 'Dịch vụ bị hủy — đảo ngược KPI đã tích lũy.',
            ],
            [
                'rule_code'      => 'QUALITY_CHECK_PASS',
                'rule_name'      => 'Kiểm tra chất lượng đạt xuất sắc',
                'quality_factor' => 1.10,
                'hold_kpi'       => false,
                'reverse_kpi'    => false,
                'trigger_event'  => 'quality_check_passed',
                'description'    => 'Vượt tiêu chuẩn chất lượng — thưởng thêm 10% KPI.',
            ],
            [
                'rule_code'      => 'LATE_COMPLETION',
                'rule_name'      => 'Hoàn thành trễ hơn kế hoạch',
                'quality_factor' => 0.90,
                'hold_kpi'       => false,
                'reverse_kpi'    => false,
                'trigger_event'  => 'late_completion',
                'description'    => 'Trễ hơn kế hoạch điều trị — trừ 10% KPI.',
            ],
        ];

        foreach ($rules as $rule) {
            KpiQualityRule::firstOrCreate(
                ['rule_code' => $rule['rule_code']],
                array_merge($rule, ['is_active' => true])
            );
        }

        $this->command->info('KpiQualityRuleSeeder: '.count($rules).' rules seeded.');
    }
}
