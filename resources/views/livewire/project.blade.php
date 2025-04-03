<div wire:init='getProducts'>
    @if($loader)
    <livewire:loader/>
    @else
        @foreach ($projects as $project)
            <p>{{ $project->project_code }}</p>
        @endforeach
    @endif
</div>
