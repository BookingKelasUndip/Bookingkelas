<div id="{{ $id }}" data-year="{{ $year }}" data-month="{{ $month }}" data-allow-past="{{ $allowPast ? '1' : '0' }}" class="calendar-component">
    <div class="mb-5 flex items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#163683] text-xs font-bold text-white">
                01
            </span>
            <div>
                <h2 class="text-sm font-bold text-slate-900">Pilih Tanggal Bimbingan</h2>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="prev-month flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-slate-800 cursor-pointer">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <span class="month-label min-w-[120px] text-center text-xs font-bold text-slate-800">
                {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}
            </span>
            <button type="button" class="next-month flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-slate-800 cursor-pointer">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-7 text-center text-[10px] font-bold tracking-wider text-slate-400 uppercase pb-2">
        <div>SEN</div>
        <div>SEL</div>
        <div>RAB</div>
        <div>KAM</div>
        <div>JUM</div>
        <div>SAB</div>
        <div class="text-rose-500">MIN</div>
    </div>

    <div class="calendar-grid grid grid-cols-7 gap-1.5 text-center text-xs">
        <!-- JS populated -->
    </div>

    <div class="mt-5 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-4 text-xs">
        <div class="flex items-center gap-2 text-slate-600">
            <span class="h-2 w-2 rounded-full bg-[#163683]"></span>
            <span>Tanggal Terpilih:</span>
            <strong class="selected-date-label font-bold text-slate-900">Pilih Tanggal</strong>
        </div>
        <input type="hidden" name="selected_date" id="{{ $id }}-input">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('{{ $id }}');
    const grid = calendar.querySelector('.calendar-grid');
    const label = calendar.querySelector('.month-label');
    const selectedLabel = calendar.querySelector('.selected-date-label');
    const input = document.getElementById('{{ $id }}-input');
    
    let currentYear = parseInt(calendar.dataset.year);
    let currentMonth = parseInt(calendar.dataset.month);
    const allowPast = calendar.dataset.allowPast === '1';

    function renderCalendar(year, month) {
        fetch(`/api/calendar?year=${year}&month=${month}`)
            .then(res => res.json())
            .then(data => {
                grid.innerHTML = '';
                const dateObj = new Date(year, month - 1, 1);
                label.textContent = dateObj.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
                
                // Add empty slots for start of month
                for (let i = 0; i < data.startDayOfWeek; i++) {
                    const empty = document.createElement('div');
                    empty.className = 'flex h-12 flex-col items-center justify-center rounded-xl text-slate-300';
                    grid.appendChild(empty);
                }

                const today = new Date();
                today.setHours(0,0,0,0);

                for (let day = 1; day <= data.daysInMonth; day++) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'flex h-12 flex-col items-center justify-center rounded-xl text-slate-700 hover:bg-slate-50 cursor-pointer transition';
                    btn.textContent = day;
                    
                    const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const thisDate = new Date(year, month - 1, day);

                    const isSunday = thisDate.getDay() === 0;
                    const isToday = thisDate.getTime() === today.getTime();

                    if (isToday) {
                        btn.className += ' ring-2 ring-[#163683] ring-offset-1';
                    }

                    if ((!allowPast && thisDate < today) || isSunday) {
                        btn.className += ' opacity-30 cursor-not-allowed';
                        btn.disabled = true;
                    }

                    btn.onclick = () => {
                        calendar.querySelectorAll('.calendar-grid button').forEach(b => {
                            b.classList.remove('bg-[#163683]', 'text-white', 'font-bold');
                            b.classList.add('text-slate-700');
                        });
                        btn.classList.remove('text-slate-700');
                        btn.classList.add('bg-[#163683]', 'text-white', 'font-bold');
                        
                        input.value = dateStr;
                        selectedLabel.textContent = thisDate.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

                        document.dispatchEvent(new CustomEvent('calendar:date-selected', {
                            detail: {
                                id: '{{ $id }}',
                                date: dateStr,
                                label: selectedLabel.textContent,
                                shortLabel: thisDate.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short' })
                            }
                        }));
                    };

                    grid.appendChild(btn);
                }
            });
    }

    calendar.querySelector('.prev-month').onclick = () => {
        currentMonth--;
        if (currentMonth < 1) { currentMonth = 12; currentYear--; }
        renderCalendar(currentYear, currentMonth);
    };

    calendar.querySelector('.next-month').onclick = () => {
        currentMonth++;
        if (currentMonth > 12) { currentMonth = 1; currentYear++; }
        renderCalendar(currentYear, currentMonth);
    };

    renderCalendar(currentYear, currentMonth);
});
</script>
