<div class="space-y-6">
    <!-- Skills Section -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Skills & Expertise</h3>
        <div class="flex flex-wrap gap-2">
            @forelse($student->skills as $skill)
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300 ring-1 ring-inset ring-amber-600/20">
                    {{ $skill->name }} <span class="ml-1.5 text-xs text-amber-500">({{ $skill->pivot->level }})</span>
                </span>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">No skills added yet.</p>
            @endforelse
        </div>
    </div>

    <hr class="border-gray-200 dark:border-gray-800" />

    <!-- Projects Section -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Portfolio Projects</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($student->projects as $project)
                <div class="relative flex flex-col justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm hover:shadow transition-shadow">
                    <div>
                        <div class="flex justify-between items-start gap-2">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $project->title }}</h4>
                            @if($project->is_ongoing)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                    Ongoing
                                </span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-3">{{ $project->description }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-xs">
                        <span class="text-gray-500">
                            {{ $project->start_date?->format('M Y') }} - 
                            {{ $project->is_ongoing ? 'Present' : $project->end_date?->format('M Y') }}
                        </span>
                        <div class="flex gap-3">
                            @if($project->project_url)
                                <a href="{{ $project->project_url }}" target="_blank" class="font-medium text-amber-600 hover:text-amber-500 dark:text-amber-400">Live Demo</a>
                            @endif
                            @if($project->repository_url)
                                <a href="{{ $project->repository_url }}" target="_blank" class="font-medium text-gray-600 hover:text-gray-500 dark:text-gray-400">Code Repo</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400 col-span-2">No projects uploaded yet.</p>
            @endforelse
        </div>
    </div>

    <hr class="border-gray-200 dark:border-gray-800" />

    <!-- Badges Section -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Earned Badges & Certifications</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($student->badges as $badge)
                <div class="flex gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
                    @if($badge->image_path)
                        <img src="/storage/{{ $badge->image_path }}" class="w-12 h-12 object-contain rounded-lg" style="width: 48px; height: 48px; object-fit: contain;" alt="{{ $badge->name }}" />
                    @else
                        <div class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-950 flex items-center justify-center text-amber-600 dark:text-amber-400" style="width: 48px; height: 48px; min-width: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    @endif
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $badge->name }}</h4>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $badge->description }}</p>
                        @if($badge->verification_source)
                            <a href="{{ $badge->verification_source }}" target="_blank" class="mt-2 inline-flex items-center text-xs font-medium text-amber-600 hover:text-amber-500 dark:text-amber-400">
                                Verify Source
                                <svg class="ml-1 w-3 h-3" style="width: 12px; height: 12px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400 col-span-2">No badges earned yet.</p>
            @endforelse
        </div>
    </div>
</div>
