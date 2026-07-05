<div class="p-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm hover:shadow-md transition duration-200 flex flex-col md:flex-row justify-between items-start gap-4 h-full w-full overflow-hidden whitespace-normal">
    <!-- Left Section: Content -->
    <div class="flex-1 min-w-0 space-y-4 flex flex-col">
        <!-- Title and Badges -->
        <div>
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white line-clamp-2">
                    {{ $record->title }}
                </h3>
                <div class="flex items-center gap-2 md:hidden">
                    <span class="px-3 py-1 bg-black text-white text-xs font-semibold rounded-full dark:bg-white dark:text-black">
                        {{ $record->type ?? 'Full-time' }}
                    </span>
                </div>
            </div>
            
            <!-- Metadata: Company, Location, Duration -->
            <div class="mt-2 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
                <!-- Company -->
                <div class="flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21v-7.5a2.25 2.25 0 0 1 2.25-2.25h12a2.25 2.25 0 0 1 2.25 2.25V21M3.75 21H21m-16.5 0v-4.5m16.5 4.5V16.5m-3.75 4.5v-4.5m-3.75 4.5v-4.5m-3.75 4.5v-4.5m-3.75 4.5v-4.5m3.75-9.75v-1.5a1.5 1.5 0 0 1 1.5-1.5h1.5a1.5 1.5 0 0 1 1.5 1.5v1.5M7.5 13.5h9v-9H7.5v9Z" />
                    </svg>
                    <span class="font-medium">{{ $record->company->name ?? 'Unknown Company' }}</span>
                </div>
                <!-- Location -->
                @if($record->location)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span>{{ $record->location }}</span>
                    </div>
                @endif
                <!-- Duration -->
                @if($record->duration)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>{{ $record->duration }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Description -->
        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed line-clamp-3">
            {{ Str::limit($record->description, 300) }}
        </p>

        <!-- Requirements / Skills -->
        <div class="space-y-2 flex-1">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Requirements:</h4>
            <div class="flex flex-wrap gap-2 max-w-full">
                @php
                    $hasSkills = $record->skills->isNotEmpty();
                @endphp
                @if($hasSkills)
                    @foreach($record->skills as $skill)
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-850 dark:text-gray-200 text-xs font-semibold rounded-full border border-gray-200 dark:border-gray-700 truncate max-w-full">
                            {{ $skill->name }}
                        </span>
                    @endforeach
                @elseif($record->requirements)
                    <!-- Fallback to text requirements split by comma or listed -->
                    @foreach(array_filter(array_map('trim', explode(',', $record->requirements))) as $req)
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-850 dark:text-gray-200 text-xs font-semibold rounded-full border border-gray-200 dark:border-gray-700 truncate max-w-full">
                            {{ $req }}
                        </span>
                    @endforeach
                @else
                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">No specific requirements listed</span>
                @endif
            </div>
        </div>
        
        <!-- Post Date (Mobile Only) -->
        <div class="text-xs text-gray-400 dark:text-gray-500 md:hidden mt-2">
            Posted {{ $record->created_at->diffForHumans() }}
        </div>
    </div>

    <!-- Right Section: Badges, Actions and Date -->
    <div class="flex-shrink-0 flex flex-col items-end justify-between self-stretch gap-4">
        <!-- Top Right info (Desktop Only) -->
        <div class="hidden md:flex flex-col items-end gap-1">
            <span class="px-3 py-1 bg-black text-white text-xs font-semibold rounded-full dark:bg-white dark:text-black">
                {{ $record->type ?? 'Full-time' }}
            </span>
            <span class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                {{ $record->created_at->diffForHumans() }}
            </span>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 w-full md:w-auto justify-end mt-auto">
            @if ($record->applications()->where('user_id', \Illuminate\Support\Facades\Auth::id())->exists())
                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-400 font-semibold text-sm rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Applied
                </span>
            @else
                <button
                    type="button"
                    wire:click="mountTableAction('apply', '{{ $record->id }}')"
                    class="px-5 py-2 bg-black hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-100 text-white font-semibold text-sm rounded-lg shadow transition duration-200 hover:shadow-md active:scale-95"
                >
                    View
                </button>
            @endif
        </div>
    </div>
</div>
