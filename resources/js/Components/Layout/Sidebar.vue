<template>
    <aside :class="['fixed inset-y-0 left-0 z-30 flex flex-col bg-gray-900 transition-all duration-200', collapsed ? 'w-16' : 'w-60']">
        <!-- Logo -->
        <div class="flex h-16 items-center justify-between px-4 border-b border-gray-800">
            <span v-if="!collapsed" class="text-white font-bold text-sm">Dental ERP</span>
            <button @click="$emit('toggle')" class="text-gray-400 hover:text-white p-1 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-3 space-y-0.5 px-2">
            <template v-for="group in visibleGroups" :key="group.label">
                <p v-if="!collapsed && group.items.length" class="text-xs text-gray-500 uppercase px-2 pt-3 pb-1 tracking-wider">
                    {{ group.label }}
                </p>
                <NavItem v-for="item in group.items" :key="item.route" :item="item" :collapsed="collapsed" />
            </template>
        </nav>
    </aside>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import NavItem from './NavItem.vue';
import { usePermission } from '@/composables/usePermission';

defineProps({ collapsed: Boolean });
defineEmits(['toggle']);

const { hasPermission: can, hasAnyRole } = usePermission();
const page = usePage();
const isHkd = computed(() => (page.props.app?.accounting_regime ?? 'TT152_HKD') === 'TT152_HKD');

const navGroups = computed(() => [
    {
        label: 'Tổng quan',
        items: [
            { label: 'Dashboard', route: 'dashboard', icon: 'home', show: true },
        ],
    },
    {
        label: 'CRM & Khách hàng',
        items: [
            { label: 'Khách hàng', route: 'patients.index', icon: 'users', show: can('patients.view') },
            { label: 'Lead', route: 'crm.leads.index', icon: 'funnel', show: can('leads.view') },
            { label: 'Follow-up Tasks', route: 'crm.tasks.index', icon: 'clipboard', show: can('leads.view') },
            { label: 'Mẫu tin nhắn', route: 'crm.messages.templates', icon: 'clipboard', show: can('leads.manage') },
            { label: 'Lịch sử nhắn', route: 'crm.messages.log', icon: 'receipt', show: can('leads.manage') },
            { label: 'Quy tắc CSKH', route: 'crm.care-rules.index', icon: 'clipboard', show: can('leads.manage') },
        ],
    },
    {
        label: 'Lịch hẹn',
        items: [
            { label: 'Lịch hẹn', route: 'schedule.appointments.index', icon: 'calendar', show: can('appointments.view') },
        ],
    },
    {
        label: 'Điều trị',
        items: [
            { label: 'Kế hoạch điều trị', route: 'clinical.treatment-plans.index', icon: 'clipboard', show: can('treatment_plans.view') },
            { label: 'Mẫu lâm sàng', route: 'clinical.templates.index', icon: 'clipboard', show: can('clinical_notes.create') },
        ],
    },
    {
        label: 'Nha khoa chuyên sâu',
        items: [
            { label: 'Phiếu khám', route: 'dental.examinations.index', icon: 'clipboard', show: can('dental.view') },
            { label: 'Thực hiện công đoạn', route: 'dental.examinations.index', icon: 'tooth', show: can('treatment_plans.edit') },
            { label: 'KPI chuyên môn', route: 'dental.kpi.index', icon: 'chart', show: can('dental.kpi.view') },
            { label: 'Danh mục bệnh', route: 'dental.conditions.index', icon: 'clipboard', show: can('dental.manage') },
        ],
    },
    {
        label: 'Thu ngân',
        items: [
            { label: 'Hóa đơn', route: 'cashier.invoices.index', icon: 'receipt', show: can('cashier.view') },
            { label: 'Công nợ', route: 'cashier.debts.index', icon: 'chart', show: can('cashier.view') },
            { label: 'Phiếu chi', route: 'cashier.expenses.index', icon: 'receipt', show: can('expenses.view') },
        ],
    },
    {
        label: 'Báo cáo',
        items: [
            { label: 'Doanh thu', route: 'reports.revenue', icon: 'chart', show: can('reports.financial') },
            { label: 'Lịch hẹn', route: 'reports.appointments', icon: 'calendar', show: can('reports.view') },
            { label: 'Công nợ', route: 'reports.debt', icon: 'receipt', show: can('reports.financial') },
            { label: 'CRM & Lead', route: 'reports.crm', icon: 'funnel', show: can('reports.view') },
            { label: 'Lãi / Lỗ', route: 'reports.profit-loss', icon: 'chart', show: can('reports.financial') },
            { label: 'Thu / Chi', route: 'reports.cashflow', icon: 'chart', show: can('reports.financial') },
            { label: 'Hiệu suất NV', route: 'reports.performance', icon: 'chart', show: can('reports.financial') },
        ],
    },
    {
        label: 'Nhân sự',
        items: [
            { label: 'Nhân viên', route: 'employees.index', icon: 'id-card', show: can('employees.view') },
            { label: 'Hợp đồng LĐ', route: 'hr.contracts.index', icon: 'receipt', show: can('employees.manage') },
            { label: 'Máy chấm công', route: 'hr.attendance-devices.index', icon: 'building', show: can('employees.manage') },
            { label: 'Ca làm việc', route: 'hr.work-shifts.index', icon: 'calendar', show: can('employees.manage') },
            { label: 'Bảng chấm công', route: 'hr.attendance.index', icon: 'calendar', show: can('employees.manage') },
            { label: 'Chấm công ngày', route: 'hr.timesheets.index', icon: 'clipboard', show: can('employees.manage') },
            { label: 'Nghỉ phép', route: 'hr.leaves.index', icon: 'calendar', show: can('employees.manage') },
            { label: 'Phiếu lương', route: 'hr.salary-slips.index', icon: 'receipt', show: can('employees.manage') },
            { label: 'Hoa hồng', route: 'hr.commissions.index', icon: 'chart', show: can('commissions.view') },
            { label: 'Đánh giá NV', route: 'hr.reviews.index', icon: 'chart', show: can('employees.manage') },
            { label: 'KPI nhân viên', route: 'hr.kpis.index', icon: 'chart', show: can('employees.manage') },
            { label: 'Tài sản cố định', route: 'hr.fixed-assets.index', icon: 'building', show: can('fixed_assets.view') },
        ],
    },
    {
        label: 'Kế toán',
        items: [
            { label: 'Bảng lương', route: 'accounting.payrolls.index', icon: 'receipt', show: can('accounting.view') },
            { label: 'Nhà cung cấp', route: 'accounting.suppliers.index', icon: 'users', show: can('accounting.view') },
            { label: 'Hóa đơn mua', route: 'accounting.purchase-invoices.index', icon: 'receipt', show: can('accounting.view') },
            { label: 'Chuyển quỹ', route: 'accounting.fund-transfers.index', icon: 'chart', show: can('accounting.view') },
            { label: 'Báo cáo thuế', route: 'reports.vat', icon: 'receipt', show: can('accounting.view') },
            { label: 'Sổ cái', route: 'reports.general-ledger', icon: 'chart', show: can('accounting.view') },
        ],
    },
    {
        label: 'Kế toán HKD (TT152)',
        show: isHkd.value,
        items: [
            { label: 'Hồ sơ HKD', route: 'hkd.profile.index', icon: 'id-card', show: can('hkd.view') },
            { label: 'Doanh thu', route: 'hkd.revenue.index', icon: 'chart', show: can('hkd.view') },
            { label: 'Chi phí', route: 'hkd.expenses.index', icon: 'receipt', show: can('hkd.view') },
            { label: 'Hàng hóa (S2d)', route: 'hkd.inventory.index', icon: 'building', show: can('hkd.view') },
            { label: 'Tiền mặt/NH (S2e)', route: 'hkd.cash.index', icon: 'chart', show: can('hkd.view') },
            { label: 'Thuế khác (S3a)', route: 'hkd.other-taxes.index', icon: 'receipt', show: can('hkd.view') },
            { label: 'Chốt kỳ', route: 'hkd.periods.index', icon: 'calendar', show: can('hkd.manage') },
            { label: 'Xuất sổ', route: 'hkd.reports.index', icon: 'chart', show: can('hkd.view') },
        ],
    },
    {
        label: 'Quản lý',
        items: [
            { label: 'Chi nhánh', route: 'branches.index', icon: 'building', show: can('branches.view') },
            { label: 'Bộ phận', route: 'core.departments.index', icon: 'users', show: can('branches.manage') },
            { label: 'Nguồn quỹ', route: 'core.fund-accounts.index', icon: 'chart', show: can('branches.manage') },
            { label: 'Dịch vụ', route: 'catalog.services.index', icon: 'tooth', show: can('services.view') },
            { label: 'Bảng giá', route: 'catalog.price-lists.index', icon: 'receipt', show: can('services.view') },
            { label: 'Ghế nha', route: 'dental-chairs.index', icon: 'building', show: hasAnyRole('owner', 'admin') },
        ],
    },
    {
        label: 'Labo',
        items: [
            { label: 'Danh sách Labo', route: 'lab.labs.index', icon: 'building', show: can('labo.view') },
            { label: 'Đơn đặt xưởng', route: 'lab.orders.index', icon: 'receipt', show: can('labo.view') },
            { label: 'Bảo hành', route: 'lab.warranties.index', icon: 'clipboard', show: can('labo.view') },
            { label: 'Công nợ Labo', route: 'lab.payables', icon: 'receipt', show: can('labo.view') },
        ],
    },
    {
        label: 'Admin',
        items: [
            { label: 'Người dùng', route: 'admin.users.index', icon: 'users', show: can('admin.users') },
            { label: 'Vai trò', route: 'admin.roles.index', icon: 'id-card', show: can('admin.roles') },
            { label: 'Cấu hình', route: 'admin.settings.index', icon: 'settings', show: can('settings.view') },
            { label: 'Audit Log', route: 'admin.activity-log.index', icon: 'chart', show: can('admin.audit_log') },
        ],
    },
]);

const visibleGroups = computed(() =>
    navGroups.value
        .filter(g => g.show !== false)
        .map(g => ({ ...g, items: g.items.filter(i => i.show !== false) }))
        .filter(g => g.items.length > 0)
);
</script>
