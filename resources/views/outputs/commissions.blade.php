<!-- resources/views/outputs/commissions.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8" x-data="{
                    openMonth: false,
                    month: '{{ $month }}',
                    monthLabel: '{{ Carbon\Carbon::create()->month($month)->translatedFormat('F') }}'
                }">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 dark:border-gray-800 pb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Relatório de
                    Comissões</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase text-[10px] tracking-widest">Apuração
                    mensal de performance (Taxa: 5%)</p>
            </div>

            <form action="{{ route('saidas.commissoes') }}" method="GET" id="filter-form" class="flex items-center gap-3">
                <div class="relative min-w-48">
                    <input type="hidden" name="month" x-model="month">

                    <button type="button" @click="openMonth = !openMonth"
                        class="w-full px-5 py-3 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black text-gray-800 dark:text-gray-50 flex items-center justify-between transition-all outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 uppercase tracking-widest shadow-xs">
                        <span x-text="monthLabel"></span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform"
                            :class="openMonth ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="openMonth" @click.away="openMonth = false" x-cloak x-transition
                        class="absolute z-50 w-full mt-2 bg-white dark:border-gray-800 border dark:bg-gray-950 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5">
                        <div class="p-2 max-h-64 overflow-y-auto">
                            @foreach(range(1, 12) as $m)
                                @php $mName = Carbon\Carbon::create()->month($m)->translatedFormat('F'); @endphp
                                <button type="button"
                                    @click="month = '{{ $m }}'; monthLabel = '{{ $mName }}'; openMonth = false; document.getElementById('filter-form').submit();"
                                    class="w-full px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest rounded-xl transition-all hover:bg-gray-50 dark:hover:bg-gray-900 hover:text-gray-800 dark:hover:text-gray-50"
                                    :class="month == '{{ $m }}' ? 'bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-50' : 'text-gray-400 dark:text-gray-500'">
                                    {{ $mName }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl">
                    <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($employees as $employee)
                <div
                    class="bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl p-8 shadow-xs space-y-6 transition-all hover:border-emerald-500 group">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center font-black text-xs uppercase text-gray-400 group-hover:text-emerald-500 transition-colors">
                            {{ substr($employee->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-gray-800 dark:text-gray-50 uppercase tracking-tight">
                                {{ $employee->name }}
                            </h3>
                            <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">{{ $employee->role }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-gray-50 dark:border-gray-900">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Faturamento
                                Real</span>
                            <span class="text-sm font-black text-gray-800 dark:text-gray-50">R$
                                {{ number_format($employee->total_sales, 2, ',', '.') }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-4 bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                            <span
                                class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-tighter">Comissão
                                (5%)</span>
                            <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">R$
                                {{ number_format($employee->commission, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-1 md:col-span-2 lg:col-span-3 py-20 bg-gray-50/50 dark:bg-gray-900/20 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-3xl flex flex-col items-center justify-center text-center">
                    <div class="p-4 bg-white dark:bg-gray-950 rounded-2xl shadow-sm mb-4">
                        <i data-lucide="award" class="w-8 h-8 text-gray-200"></i>
                    </div>
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Nenhuma comissão apurada</h2>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase">Não existem vendas registradas para o mês de <span
                            class="text-gray-600 dark:text-gray-200" x-text="monthLabel"></span>.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection