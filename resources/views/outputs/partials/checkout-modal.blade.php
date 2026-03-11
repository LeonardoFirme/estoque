<!-- resources/views/outputs/partials/checkout-modal.blade.php -->
<div x-show="checkoutModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-6 overflow-hidden">
    <div x-show="checkoutModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-950/80 backdrop-blur-sm"
        @click="if(!printModal) checkoutModal = false">
    </div>

    <div x-show="checkoutModal && !printModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white dark:bg-gray-950 w-full max-w-2xl rounded-4xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-800"
        x-init="$watch('type', value => { if(value === 'withdrawal') { paymentMethod = 'money'; installments = 1; } })">

        <div class="p-10 space-y-8">
            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-8">
                <div>
                    <h2 class="text-3xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase"
                        x-text="type === 'withdrawal' ? 'Retirada de Caixa' : (type === 'return' ? 'Devolução de Item' : (type === 'loss' ? 'Lançar Avaria' : 'Finalizar Venda'))">
                    </h2>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Confirme os dados para
                        finalizar</p>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1"
                        x-text="type === 'withdrawal' ? 'Valor Retirada' : 'Total Líquido'"></span>
                    <div class="flex items-baseline justify-end gap-1 text-emerald-500">
                        <span class="text-xs font-black uppercase">R$</span>
                        <span class="text-4xl font-black tracking-tighter"
                            x-text="subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                    </div>
                </div>
            </div>

            <template x-if="type !== 'sale' || globalDiscount > 0 || cart.some(i => i.discount > 0)">
                <div
                    class="space-y-4 p-8 rounded-3xl border bg-gray-50 dark:bg-gray-900/50 border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="shield-lock" class="w-5 h-5 text-amber-500"></i>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Autorização do
                            Gerente</label>
                    </div>
                    <input type="password" x-model="managerPassword" placeholder="INSIRA SUA SENHA DE ACESSO..."
                        class="w-full px-6 py-5 bg-white dark:bg-gray-950 border-2 border-gray-200 dark:border-gray-800 rounded-2xl text-lg font-black text-gray-800 dark:text-gray-50 outline-none focus:border-emerald-500 transition-all">
                </div>
            </template>

            <div class="grid gap-5" :class="type === 'withdrawal' ? 'grid-cols-1' : 'grid-cols-3'">
                <template x-if="type === 'withdrawal'">
                    <div
                        class="flex items-center justify-center gap-4 p-8 border-2 border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20 rounded-3xl">
                        <i data-lucide="banknote" class="w-8 h-8 text-emerald-500"></i>
                        <span class="text-sm font-black uppercase text-emerald-600 dark:text-emerald-400">Sangria em
                            Dinheiro</span>
                    </div>
                </template>

                <template x-if="type !== 'withdrawal'">
                    <div class="contents">
                        <template
                            x-for="method in [{ id: 'money', label: 'Dinheiro', icon: 'banknote' }, { id: 'debit', label: 'Débito', icon: 'credit-card' }, { id: 'credit', label: 'Crédito', icon: 'layers' }]"
                            :key="method.id">
                            <button type="button" @click="paymentMethod = method.id; installments = 1"
                                :class="paymentMethod === method.id ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-950'"
                                class="flex flex-col items-center justify-center gap-4 p-8 border-2 rounded-3xl transition-all group cursor-pointer border-none outline-none">
                                <i :data-lucide="method.icon"
                                    :class="paymentMethod === method.id ? 'text-emerald-500' : 'text-gray-400'"
                                    class="w-10 h-10 transition-transform group-hover:scale-110"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest"
                                    :class="paymentMethod === method.id ? 'text-emerald-600' : 'text-gray-400'"
                                    x-text="method.label"></span>
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            <div x-show="paymentMethod === 'credit' && type === 'sale'"
                class="space-y-4 bg-gray-50/50 dark:bg-gray-900/30 p-6 rounded-3xl border border-gray-100 dark:border-gray-800">
                <div class="grid grid-cols-2 gap-3 max-h-40 overflow-y-auto pr-2">
                    <template x-for="i in 12" :key="i">
                        <button type="button" @click="installments = i"
                            :class="installments === i ? 'bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950' : 'bg-white dark:bg-gray-950 text-gray-400'"
                            class="flex justify-between items-center p-5 rounded-2xl transition-all border-none cursor-pointer outline-none">
                            <span class="text-[11px] font-black uppercase" x-text="i + 'x'"></span>
                            <span class="text-sm font-black"
                                x-text="'R$ ' + (subtotal / i).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="pt-4 flex gap-5">
                <button type="button" @click="checkoutModal = false; managerPassword = ''"
                    class="flex-1 py-6 bg-white dark:bg-gray-950 text-gray-400 font-black text-[10px] uppercase tracking-widest rounded-2xl border-2 border-gray-100 dark:border-gray-800 cursor-pointer border-none outline-none">Voltar</button>
                <button type="button" @click="printModal = true"
                    class="flex-2 py-6 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-[11px] uppercase tracking-widest rounded-2xl shadow-2xl transition-all flex items-center justify-center gap-3 active:scale-95 cursor-pointer border-none outline-none">
                    <i data-lucide="shield-check" class="w-5 h-5"></i> Finalizar Operação
                </button>
            </div>
        </div>
    </div>

    <div x-show="printModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white dark:bg-gray-900 w-full max-w-sm rounded-4xl shadow-3xl p-10 border border-gray-100 dark:border-gray-800 text-center space-y-8">
        <div
            class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 rounded-3xl flex items-center justify-center mx-auto">
            <i data-lucide="printer" class="w-10 h-10 text-emerald-500"></i>
        </div>
        <h3 class="text-xl font-black text-gray-800 dark:text-gray-50 uppercase tracking-tighter">Imprimir Recibo?</h3>
        <div class="flex flex-col gap-3">
            <button @click="shouldPrint = 'true'; $nextTick(() => submitForm())"
                class="w-full py-5 bg-emerald-500 text-white font-black text-[11px] uppercase tracking-widest rounded-2xl shadow-lg hover:bg-emerald-600 transition-all cursor-pointer border-none outline-none">Sim,
                Imprimir (ENTER)</button>
            <button @click="shouldPrint = 'false'; $nextTick(() => submitForm())"
                class="w-full py-5 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all cursor-pointer border-none outline-none">Não,
                Apenas Salvar</button>
        </div>
    </div>
</div>