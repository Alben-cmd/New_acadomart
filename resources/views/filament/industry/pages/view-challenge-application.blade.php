<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif

    <x-filament::section class="mt-6">
        <x-slot name="heading">
            Application Status
        </x-slot>

        <form wire:submit="saveStatus" class="space-y-6">
            {{ $this->statusForm }}

            <x-filament::actions
                :actions="$this->getStatusFormActions()"
            />
        </form>
    </x-filament-panels::section>
</x-filament-panels::page>
