<template>
    <AppLayout :title="`Thu tiền: ${invoice.code}`">
        <div class="max-w-4xl space-y-4">

            <!-- ── Header ───────────────────────────────────────────────────── -->
            <div class="bg-white rounded-xl border border-gray-200 px-5 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <Link :href="route('cashier.invoices.index')" class="text-gray-400 hover:text-gray-600 text-sm">← Hóa đơn</Link>
                            <span class="text-gray-300">/</span>
                            <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ invoice.code }}</span>
                            <StatusBadge :color="invoice.status_color">{{ invoice.status_label }}</StatusBadge>
                            <Link v-if="invoice.plan_id"
                                :href="route('clinical.treatment-plans.show', invoice.plan_id)"
                                class="text-xs text-indigo-500 hover:text-indigo-700">
                                → KHDT
                            </Link>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ invoice.patient }}</h2>
                        <p class="text-sm text-gray-500 mt-0.5">{{ invoice.patient_phone }} · {{ invoice.branch }}</p>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <a :href="route('cashier.invoices.receipt', invoice.id)" target="_blank"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            In phiếu thu
                        </a>
                        <button v-if="invoice.status !== 'cancelled' && invoice.status !== 'paid' && invoice.amount_paid === 0"
                            @click="doCancel"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-red-600 border border-red-200 rounded-lg hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Hủy HĐ
                        </button>
                    </div>
                </div>

                <!-- Financial summary bar — Bambu style -->
                <div class="mt-3 flex flex-wrap gap-4 text-sm bg-slate-800 rounded-xl px-4 py-3">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 text-xs">Tổng phải trả</span>
                        <span class="font-bold text-white text-base tabular-nums">{{ formatVnd(invoice.total) }}</span>
                    </div>
                    <div class="h-4 w-px bg-slate-600 hidden sm:block self-center"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 text-xs">Đã thu</span>
                        <span class="font-bold text-emerald-400 text-base tabular-nums">{{ formatVnd(invoice.amount_paid) }}</span>
                    </div>
                    <div class="h-4 w-px bg-slate-600 hidden sm:block self-center"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 text-xs">Còn nợ</span>
                        <span :class="['font-bold text-base tabular-nums', invoice.amount_due > 0 ? 'text-rose-400' : 'text-emerald-400']">
                            {{ formatVnd(invoice.amount_due) }}
                        </span>
                    </div>
                    <span v-if="invoice.discount > 0" class="flex items-center gap-2 ml-auto">
                        <span class="text-slate-400 text-xs">Giảm giá</span>
                        <span class="font-bold text-amber-300 tabular-nums">-{{ formatVnd(invoice.discount) }}</span>
                    </span>
                    <span :class="['ml-auto self-center text-xs px-2.5 py-1 rounded-full font-semibold border',
                        invoice.amount_due <= 0
                            ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'
                            : 'bg-rose-500/20 text-rose-300 border-rose-500/30']">
                        {{ invoice.amount_due <= 0 ? '✓ Đã thanh toán đủ' : '⚠ Còn nợ' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- LEFT: Payment form + history -->
                <div class="lg:col-span-2 space-y-4">

                    <!-- ── Payment form — Bambu style ──────────────────────── -->
                    <div v-if="invoice.status !== 'paid' && invoice.status !== 'cancelled'"
                        class="bg-white rounded-xl border border-indigo-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Thu tiền
                        </h3>

                        <!-- Payment method selector — 4 big buttons like Bambu -->
                        <div class="mb-4">
                            <label class="text-xs text-gray-500 mb-2 block font-medium uppercase tracking-wide">Hình thức thanh toán *</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <button v-for="m in methods" :key="m.value"
                                    @click="payForm.method = m.value"
                                    :class="['flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 text-sm font-medium transition-all',
                                        payForm.method === m.value
                                            ? methodActiveClass(m.value)
                                            : 'border-gray-100 bg-gray-50 text-gray-500 hover:border-gray-200 hover:bg-gray-100']">
                                    <span class="text-xl">{{ m.icon }}</span>
                                    <span class="text-xs">{{ m.label }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Amount + Date row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="text-xs text-gray-500 mb-1.5 block font-medium">Số tiền thu (₫) *</label>
                                <div class="flex gap-2">
                                    <input v-model="payForm.amount" type="number" :min="canRefund ? undefined : 1"
                                        :placeholder="`Còn nợ: ${formatVnd(invoice.amount_due)}`"
                                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none tabular-nums text-right font-semibold"
                                        @input="debtMode = false" />
                                    <!-- Nút "Nợ" — Bambu style -->
                                    <button @click="setDebtMode"
                                        :class="['px-3 py-2 rounded-lg text-sm font-medium border transition-colors whitespace-nowrap',
                                            debtMode
                                                ? 'bg-rose-100 border-rose-300 text-rose-700'
                                                : 'border-gray-200 text-gray-500 hover:bg-gray-50']">
                                        Nợ
                                    </button>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">
                                    <button @click="fillFullAmount" class="text-indigo-500 hover:text-indigo-700 underline">Thu đủ: {{ formatVnd(invoice.amount_due) }}</button>
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1.5 block font-medium">Ngày thu *</label>
                                <input v-model="payForm.payment_date" type="date"
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Reference + Notes -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="text-xs text-gray-500 mb-1.5 block font-medium">Mã giao dịch / Tham chiếu</label>
                                <input v-model="payForm.reference" type="text" placeholder="Số chuyển khoản, mã QR..."
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1.5 block font-medium">Ghi chú</label>
                                <input v-model="payForm.notes" type="text" placeholder="Tùy chọn..."
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Debt mode notice -->
                        <div v-if="debtMode" class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-lg">
                            <p class="text-sm text-rose-700 flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span><strong>Chế độ ghi nợ:</strong> Bệnh nhân nợ toàn bộ {{ formatVnd(invoice.amount_due) }}. Hệ thống sẽ ghi vào sổ công nợ.</span>
                            </p>
                        </div>

                        <!-- Error display -->
                        <p v-if="payForm.errors.amount" class="text-xs text-red-500 mb-3">{{ payForm.errors.amount }}</p>

                        <!-- Action buttons — Lưu & Thoát | Lưu | Lưu & In (Bambu style) -->
                        <div class="flex flex-wrap gap-2 justify-end pt-3 border-t border-gray-100">
                            <Link :href="route('cashier.invoices.index')"
                                class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">
                                Thoát
                            </Link>
                            <button @click="submitPayment(false)" :disabled="payForm.processing"
                                class="px-4 py-2 text-sm text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 disabled:opacity-50 font-medium">
                                Lưu
                            </button>
                            <button @click="submitPayment(true)" :disabled="payForm.processing"
                                class="inline-flex items-center gap-1.5 px-5 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50 font-medium shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Lưu &amp; In phiếu
                            </button>
                        </div>
                    </div>

                    <!-- ── Payment history ─────────────────────────────────── -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Lịch sử thanh toán
                            </h3>
                            <span class="text-xs text-gray-400">{{ payments.length }} lần</span>
                        </div>
                        <div v-if="payments.length === 0" class="text-center py-8 text-gray-400 text-sm">Chưa có thanh toán</div>
                        <table v-else class="w-full text-sm">
                            <thead class="bg-gray-50/60 text-gray-500 text-xs border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-medium">Ngày</th>
                                    <th class="px-4 py-2.5 text-left font-medium">Hình thức</th>
                                    <th class="px-4 py-2.5 text-right font-medium">Số tiền</th>
                                    <th class="px-4 py-2.5 text-left font-medium hidden sm:table-cell">Tham chiếu</th>
                                    <th class="px-4 py-2.5 text-left font-medium hidden sm:table-cell">Người thu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="p in payments" :key="p.id" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-600">{{ p.payment_date }}</td>
                                    <td class="px-4 py-3">
                                        <span :class="['inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium', methodBadgeClass(p.method)]">
                                            {{ methodIcon(p.method) }} {{ p.method_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right tabular-nums font-semibold"
                                        :class="p.amount < 0 ? 'text-red-600' : 'text-emerald-700'">
                                        {{ p.amount < 0 ? '−' : '' }}{{ formatVnd(Math.abs(p.amount)) }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-400 text-xs hidden sm:table-cell">{{ p.reference ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-500 hidden sm:table-cell">{{ p.creator }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- RIGHT: Invoice summary + Discount -->
                <div class="space-y-4">
                    <!-- Debt summary card -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-3">
                        <h3 class="text-sm font-semibold text-gray-700">Chi tiết hóa đơn</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tổng dịch vụ</span>
                                <span class="tabular-nums">{{ formatVnd(invoice.subtotal) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Giảm giá</span>
                                <span class="text-rose-600 tabular-nums">-{{ formatVnd(invoice.discount) }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-gray-700 font-medium">Thực thu</span>
                                <span class="font-bold tabular-nums">{{ formatVnd(invoice.total) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Đã thanh toán</span>
                                <span class="text-emerald-600 font-semibold tabular-nums">{{ formatVnd(invoice.amount_paid) }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-semibold">Còn nợ</span>
                                <span :class="['font-bold tabular-nums', invoice.amount_due > 0 ? 'text-rose-600' : 'text-emerald-600']">
                                    {{ formatVnd(invoice.amount_due) }}
                                </span>
                            </div>
                        </div>
                        <StatusBadge v-if="debt" :color="debt.status_color" class="w-full justify-center">
                            {{ debt.status_label }}
                        </StatusBadge>
                    </div>

                    <!-- Discount (gated) -->
                    <div v-if="canDiscount && invoice.status !== 'paid' && invoice.status !== 'cancelled'"
                        class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Áp dụng giảm giá</h3>
                        <div class="flex gap-2">
                            <input v-model="discountAmount" type="number" min="0" placeholder="Số tiền giảm (₫)"
                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none tabular-nums" />
                            <button @click="applyDiscount"
                                class="px-3 py-2 text-sm bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium">
                                Áp dụng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/Layout/AppLayout.vue';
import StatusBadge from '@/Components/Shared/StatusBadge.vue';
import { useCurrency } from '@/composables/useCurrency';
import dayjs from 'dayjs';

const { formatVnd } = useCurrency();
const props = defineProps({
    invoice: Object, payments: Array, debt: Object,
    methods: Array, canRefund: Boolean, canDiscount: Boolean,
});

const discountAmount = ref(props.invoice.discount);
const debtMode       = ref(false);

// Enrich methods with icons
const methods = (props.methods ?? []).map(m => ({
    ...m,
    icon: { cash: '💵', transfer: '🏦', card: '💳', ewallet: '📱', installment: '📅', voucher: '🎟️' }[m.value] ?? '💰',
}));

const payForm = useForm({
    amount:       '',
    method:       'cash',
    payment_date: dayjs().format('YYYY-MM-DD'),
    reference:    '',
    notes:        '',
});

function fillFullAmount() {
    payForm.amount = props.invoice.amount_due;
    debtMode.value = false;
}

function setDebtMode() {
    debtMode.value = !debtMode.value;
    if (debtMode.value) {
        payForm.amount   = 0;
        payForm.notes    = 'Ghi nợ';
    } else {
        payForm.amount = '';
        payForm.notes  = '';
    }
}

function submitPayment(printAfter = false) {
    payForm.post(route('cashier.invoices.payments.store', props.invoice.id), {
        onSuccess: () => {
            payForm.reset('amount', 'reference', 'notes');
            debtMode.value = false;
            if (printAfter) {
                window.open(route('cashier.invoices.receipt', props.invoice.id), '_blank');
            }
        },
    });
}

function applyDiscount() {
    router.post(route('cashier.invoices.discount', props.invoice.id), { discount: discountAmount.value });
}

function doCancel() {
    if (confirm('Bạn muốn hủy hóa đơn này?')) {
        router.post(route('cashier.invoices.cancel', props.invoice.id));
    }
}

// Method styling helpers
function methodActiveClass(method) {
    return {
        cash:        'border-emerald-400 bg-emerald-50 text-emerald-700',
        transfer:    'border-blue-400 bg-blue-50 text-blue-700',
        card:        'border-purple-400 bg-purple-50 text-purple-700',
        ewallet:     'border-teal-400 bg-teal-50 text-teal-700',
        installment: 'border-orange-400 bg-orange-50 text-orange-700',
        voucher:     'border-pink-400 bg-pink-50 text-pink-700',
    }[method] ?? 'border-indigo-400 bg-indigo-50 text-indigo-700';
}

function methodBadgeClass(method) {
    return {
        cash:        'bg-emerald-100 text-emerald-700',
        transfer:    'bg-blue-100 text-blue-700',
        card:        'bg-purple-100 text-purple-700',
        ewallet:     'bg-teal-100 text-teal-700',
        installment: 'bg-orange-100 text-orange-700',
        voucher:     'bg-pink-100 text-pink-700',
    }[method] ?? 'bg-gray-100 text-gray-600';
}

function methodIcon(method) {
    return { cash: '💵', transfer: '🏦', card: '💳', ewallet: '📱', installment: '📅', voucher: '🎟️' }[method] ?? '💰';
}
</script>
