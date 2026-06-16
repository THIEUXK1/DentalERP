<template>
    <AppLayout :title="patient ? 'Sửa khách hàng' : 'Thêm khách hàng'">
        <div class="max-w-3xl mx-auto bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">
                {{ patient ? 'Cập nhật hồ sơ khách hàng' : 'Tạo hồ sơ khách hàng mới' }}
            </h2>
            <form @submit.prevent="submit" class="space-y-5">
                <!-- Basic info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <FormInput label="Họ tên" :error="form.errors.full_name" required>
                        <input v-model="form.full_name" type="text" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" />
                    </FormInput>
                    <FormInput label="Số điện thoại" :error="form.errors.phone" required>
                        <input v-model="form.phone" type="tel" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" />
                    </FormInput>
                    <FormInput label="Email" :error="form.errors.email">
                        <input v-model="form.email" type="email" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" />
                    </FormInput>
                    <FormInput label="Ngày sinh" :error="form.errors.dob">
                        <input v-model="form.dob" type="date" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" />
                    </FormInput>
                    <FormInput label="Giới tính" :error="form.errors.gender">
                        <select v-model="form.gender" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            <option value="">-- Chọn --</option>
                            <option value="male">Nam</option>
                            <option value="female">Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                    </FormInput>
                    <FormInput label="Nguồn khách" :error="form.errors.source">
                        <select v-model="form.source" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            <option value="">-- Chọn --</option>
                            <option v-for="s in sources" :key="s.value" :value="s.value">{{ s.label }}</option>
                        </select>
                    </FormInput>
                    <FormInput label="Chi nhánh" :error="form.errors.branch_id">
                        <select v-model="form.branch_id" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            <option :value="null">-- Chọn --</option>
                            <option v-for="b in branches" :key="b.id" :value="b.id">{{ b.name }}</option>
                        </select>
                    </FormInput>
                    <FormInput label="Người liên hệ khẩn cấp" :error="form.errors.emergency_contact">
                        <input v-model="form.emergency_contact" type="text" placeholder="Tên — SĐT" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" />
                    </FormInput>
                </div>

                <FormInput label="Địa chỉ" :error="form.errors.address">
                    <input v-model="form.address" type="text" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" />
                </FormInput>

                <!-- Medical info -->
                <div class="border-t pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Tiểu sử bệnh
                    </h3>

                    <!-- Quick checkboxes —— giống Bambu -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <label v-for="flag in MEDICAL_FLAGS" :key="flag.key"
                            :class="['flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs cursor-pointer transition-colors select-none',
                                form.medical_flags.includes(flag.key)
                                    ? 'bg-rose-50 border-rose-300 text-rose-700 font-medium'
                                    : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300']">
                            <input type="checkbox" :value="flag.key" v-model="form.medical_flags" class="hidden" />
                            <span :class="['w-3.5 h-3.5 rounded border flex items-center justify-center flex-shrink-0',
                                form.medical_flags.includes(flag.key) ? 'bg-rose-500 border-rose-500' : 'border-gray-300']">
                                <svg v-if="form.medical_flags.includes(flag.key)" class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            {{ flag.label }}
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <FormInput label="Dị ứng (chi tiết)" :error="form.errors.allergies">
                            <textarea v-model="form.allergies" rows="2" placeholder="Liệt kê các dị ứng cụ thể..."
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" />
                        </FormInput>
                        <FormInput label="Ghi chú bệnh lý khác" :error="form.errors.medical_history">
                            <textarea v-model="form.medical_history" rows="2" placeholder="Bệnh lý khác cần lưu ý..."
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" />
                        </FormInput>
                    </div>
                </div>

                <FormInput label="Ghi chú" :error="form.errors.notes">
                    <textarea v-model="form.notes" rows="2" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" />
                </FormInput>

                <div class="flex justify-end gap-3 pt-2">
                    <Link :href="route('patients.index')" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Hủy</Link>
                    <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm text-white bg-primary-600 rounded-lg hover:bg-primary-700 disabled:opacity-50">
                        {{ patient ? 'Cập nhật' : 'Tạo hồ sơ' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Components/Layout/AppLayout.vue';
import FormInput from '@/Components/Shared/FormInput.vue';

const MEDICAL_FLAGS = [
    { key: 'chay_mau_lau',   label: 'Chảy máu lâu' },
    { key: 'phan_ung_thuoc', label: 'Phản ứng thuốc' },
    { key: 'di_ung_khop',    label: 'Dị ứng, thấp khớp' },
    { key: 'cao_ha',         label: 'Cao HA' },
    { key: 'tim_mach',       label: 'Tim mạch' },
    { key: 'tieu_duong',     label: 'Tiểu đường' },
    { key: 'da_day',         label: 'Dạ dày, tiêu hóa' },
    { key: 'benh_phoi',      label: 'Bệnh phổi' },
    { key: 'truyen_nhiem',   label: 'Bệnh truyền nhiễm' },
];

const props = defineProps({ patient: Object, branches: Array, sources: Array });

const form = useForm({
    full_name:         props.patient?.full_name ?? '',
    phone:             props.patient?.phone ?? '',
    email:             props.patient?.email ?? '',
    dob:               props.patient?.dob ?? '',
    gender:            props.patient?.gender ?? '',
    address:           props.patient?.address ?? '',
    source:            props.patient?.source ?? '',
    allergies:         props.patient?.allergies ?? '',
    medical_history:   props.patient?.medical_history ?? '',
    medical_flags:     props.patient?.medical_flags ?? [],
    emergency_contact: props.patient?.emergency_contact ?? '',
    branch_id:         props.patient?.branch_id ?? null,
    notes:             props.patient?.notes ?? '',
    is_active:         props.patient?.is_active ?? true,
});

function submit() {
    if (props.patient) {
        form.put(route('patients.update', props.patient.id));
    } else {
        form.post(route('patients.store'));
    }
}
</script>
