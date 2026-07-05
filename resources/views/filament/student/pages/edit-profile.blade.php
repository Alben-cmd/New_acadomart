<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        @if ($isEditing)
            <x-filament::actions
                :actions="$this->getFormActions()"
            />
        @endif
    </form>
</x-filament-panels::page>
