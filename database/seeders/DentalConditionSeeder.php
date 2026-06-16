<?php

namespace Database\Seeders;

use App\Models\DentalCondition;
use Illuminate\Database\Seeder;

class DentalConditionSeeder extends Seeder
{
    public function run(): void
    {
        // Enum backing values from DentalConditionGroup:
        // sâu_răng | viêm_tủy | mất_răng | viêm_nha_chu | sai_khớp_cắn | răng_khôn | thẩm_mỹ | răng_trẻ_em | khác
        $conditions = [
            ['name' => 'Sâu răng độ 1 (men)', 'group' => 'sâu_răng'],
            ['name' => 'Sâu răng độ 2 (ngà nông)', 'group' => 'sâu_răng'],
            ['name' => 'Sâu răng độ 3 (ngà sâu)', 'group' => 'sâu_răng'],
            ['name' => 'Sâu răng độ 4 (tủy)', 'group' => 'sâu_răng'],
            ['name' => 'Sâu cổ răng', 'group' => 'sâu_răng'],
            ['name' => 'Sâu răng tái phát', 'group' => 'sâu_răng'],

            ['name' => 'Viêm nướu mãn tính', 'group' => 'viêm_nha_chu'],
            ['name' => 'Viêm nha chu mãn tính', 'group' => 'viêm_nha_chu'],
            ['name' => 'Viêm nha chu tích cực', 'group' => 'viêm_nha_chu'],
            ['name' => 'Túi nha chu > 4mm', 'group' => 'viêm_nha_chu'],
            ['name' => 'Tiêu xương ổ răng', 'group' => 'viêm_nha_chu'],
            ['name' => 'Lung lay răng (độ 1-3)', 'group' => 'viêm_nha_chu'],

            ['name' => 'Viêm tủy cấp có hồi phục', 'group' => 'viêm_tủy'],
            ['name' => 'Viêm tủy cấp không hồi phục', 'group' => 'viêm_tủy'],
            ['name' => 'Hoại tử tủy', 'group' => 'viêm_tủy'],
            ['name' => 'Điều trị tủy thất bại', 'group' => 'viêm_tủy'],
            ['name' => 'Nang chân răng', 'group' => 'viêm_tủy'],

            ['name' => 'Mất răng đơn lẻ', 'group' => 'mất_răng'],
            ['name' => 'Mất nhiều răng liên tiếp', 'group' => 'mất_răng'],
            ['name' => 'Mất răng toàn hàm (bán phần)', 'group' => 'mất_răng'],
            ['name' => 'Mất răng vĩnh viễn chưa mọc (bẩm sinh)', 'group' => 'mất_răng'],

            ['name' => 'Răng mọc lệch/xoay', 'group' => 'sai_khớp_cắn'],
            ['name' => 'Răng chen chúc (Angle I/II/III)', 'group' => 'sai_khớp_cắn'],
            ['name' => 'Hô hàm trên', 'group' => 'sai_khớp_cắn'],
            ['name' => 'Móm hàm dưới', 'group' => 'sai_khớp_cắn'],
            ['name' => 'Cắn sâu', 'group' => 'sai_khớp_cắn'],
            ['name' => 'Cắn hở', 'group' => 'sai_khớp_cắn'],
            ['name' => 'Cắn chéo', 'group' => 'sai_khớp_cắn'],

            ['name' => 'Răng nhiễm màu (tetracycline, florua)', 'group' => 'thẩm_mỹ'],
            ['name' => 'Răng thưa (khe hở trung tâm)', 'group' => 'thẩm_mỹ'],
            ['name' => 'Mòn cổ răng / mòn cơ học', 'group' => 'thẩm_mỹ'],
            ['name' => 'Răng gãy vỡ (chấn thương)', 'group' => 'thẩm_mỹ'],
            ['name' => 'Mặt răng không đều / hình dạng bất thường', 'group' => 'thẩm_mỹ'],
            ['name' => 'Vị trí cần Implant (xương đủ)', 'group' => 'thẩm_mỹ'],
            ['name' => 'Vị trí cần Implant (cần ghép xương)', 'group' => 'thẩm_mỹ'],

            ['name' => 'Răng khôn mọc lệch', 'group' => 'răng_khôn'],
            ['name' => 'Răng khôn gây đau/viêm', 'group' => 'răng_khôn'],
            ['name' => 'Răng khôn chen ép răng kế', 'group' => 'răng_khôn'],

            ['name' => 'Sâu răng sữa', 'group' => 'răng_trẻ_em'],
            ['name' => 'Răng vĩnh viễn chưa mọc đủ', 'group' => 'răng_trẻ_em'],
            ['name' => 'Viêm tủy răng sữa', 'group' => 'răng_trẻ_em'],

            ['name' => 'Rối loạn khớp thái dương hàm (TMD)', 'group' => 'khác'],
            ['name' => 'Nghiến răng (bruxism)', 'group' => 'khác'],
            ['name' => 'Loét miệng tái phát (apthous)', 'group' => 'khác'],
            ['name' => 'Abces nướu / abces quanh chóp', 'group' => 'khác'],
            ['name' => 'Nứt răng dọc', 'group' => 'khác'],
            ['name' => 'Khô miệng (xerostomia)', 'group' => 'khác'],
        ];

        foreach ($conditions as $idx => $data) {
            DentalCondition::firstOrCreate(
                ['name' => $data['name']],
                [
                    'code'       => DentalCondition::generateCode(),
                    'group'      => $data['group'],
                    'sort_order' => $idx + 1,
                    'is_active'  => true,
                ]
            );
        }

        $this->command->info('DentalConditionSeeder: '.count($conditions).' conditions seeded.');
    }
}
