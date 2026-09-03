<?php

use Livewire\Component;
use Statamic\Facades\Entry;

new class extends Component
{
    public int $limit = 2;
    public string $textLightHoverColorClass = '';
    public string $backgroundHoverColorClass = '';

    public function loadMore(): void
    {
        $this->limit += 2;
    }

    public function with(): array
    {
        $query = Entry::query()
            ->where('collection', 'requests_for_qualifications')
            ->whereStatus('published');

        return [
            'requests' => (clone $query)
                ->orderBy('updated_at', 'desc')
                ->limit($this->limit)
                ->get(),
            'hasMore' => $this->limit < (clone $query)->count(),
        ];
    }
};

?>

<div class="mt-8 space-y-9 lg:mt-18">
  @foreach($requests as $request)
    @php
      $description = $request->augmentedValue('description')->value();
      $resources = $request->augmentedValue('resources')->value() ?? [];
    @endphp

    <article class="border-t border-current py-4">
      <h3 class="text-2xl leading-none sm:text-3xl">
        {{ $request->get('title') }}
      </h3>

      @if($description)
        <div class="mt-6 font-typewriter text-lg leading-tight sm:text-xl [&_p]:mb-4 [&_p:last-child]:mb-0">
          {!! $description !!}
        </div>
      @endif

      @if(count($resources))
        <div class="mt-8 border-t border-current font-typewriter text-lg leading-none sm:text-xl">
          @foreach($resources as $resource)
            @php
              $resourceName = $resource['name'] ?? null;
              $resourceType = $resource['resource_type'] ?? null;
              $resourceUrl = $resourceType === 'file'
                ? data_get($resource, 'file.0.url')
                : ($resource['url'] ?? null);
            @endphp

            @if($resourceName && $resourceUrl)
              <div class="flex items-center justify-between gap-4 border-b border-current py-4">
                <span>{{ $resourceName }}</span>
                <a href="{{ $resourceUrl }}" class="underline uppercase transition {{ $textLightHoverColorClass }}" target="_blank" rel="noopener">
                  {{ __('View') }}
                </a>
              </div>
            @elseif($resourceName)
              <div class="border-b border-current py-4">
                {{ $resourceName }}
              </div>
            @endif
          @endforeach
        </div>
      @endif
    </article>
  @endforeach

  @if($hasMore)
    <button
      type="button"
      wire:click="loadMore"
      wire:loading.attr="disabled"
      wire:target="loadMore"
      class="flex w-full items-center justify-center border border-current p-4 font-typewriter text-lg uppercase leading-none underline transition cursor-pointer disabled:opacity-70 sm:text-xl {{ $backgroundHoverColorClass }} hover:text-brand-white"
    >
      <span wire:loading.remove wire:target="loadMore">
        {{ __('Past RFQs') }}
      </span>

      <span wire:loading wire:target="loadMore" class="size-5 animate-spin rounded-full border-2 border-current border-t-transparent" aria-label="{{ __('Loading') }}"></span>
    </button>
  @endif
</div>
