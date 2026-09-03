<?php

use Livewire\Component;
use Statamic\Facades\Asset;
use Statamic\Facades\Entry;

new class extends Component
{
    public int $limit = 5;
    public string $backgroundHoverColorClass = '';
    public string $textColorClass = '';
    public string $textLightHoverColorClass = '';

    public function loadMore(): void
    {
        $this->limit += 5;
    }

    public function with(): array
    {
        $query = Entry::query()
            ->where('collection', 'governance')
            ->whereStatus('published');

        return [
            'governanceEntries' => (clone $query)
                ->orderBy('updated_at', 'desc')
                ->limit($this->limit)
                ->get(),
            'hasMore' => $this->limit < (clone $query)->count(),
        ];
    }

    public function entryUrl($entry): ?string
    {
        if ($entry->get('type') !== 'file') {
            return $entry->get('url');
        }

        $file = $entry->get('file');

        if (is_string($file)) {
            return Asset::find("assets::{$file}")?->url() ?? asset("assets/{$file}");
        }

        if (is_object($file) && method_exists($file, 'url')) {
            return $file->url();
        }

        if ($firstFile = data_get($file, '0')) {
            return is_object($firstFile) && method_exists($firstFile, 'url')
                ? $firstFile->url()
                : data_get($firstFile, 'url');
        }

        return data_get($file, 'url');
    }
};

?>

<div class="mt-10 {{ $textColorClass }} lg:mt-20">
  <h3 class="text-3xl leading-none sm:text-[2.5rem] mb-8">
    {{ __('COC Governance') }}
  </h3>

  @foreach($governanceEntries as $entry)
    @php
      $entryUrl = $this->entryUrl($entry);
    @endphp

    <article class="flex items-end justify-between gap-6 border-b border-current py-4 font-typewriter text-lg leading-tight sm:text-xl">
      <h3>
        {{ $entry->get('title') }}
      </h3>

      @if($entryUrl)
        <a href="{{ $entryUrl }}" class="shrink-0 underline uppercase transition {{ $textLightHoverColorClass }}" target="_blank" rel="noopener">
          {{ __('View') }}
        </a>
      @endif
    </article>
  @endforeach

  @if($hasMore)
    <button
      type="button"
      wire:click="loadMore"
      wire:loading.attr="disabled"
      wire:target="loadMore"
      class="mt-7 flex w-full items-center justify-center border border-current p-4 font-typewriter text-lg uppercase leading-none underline transition cursor-pointer disabled:opacity-70 sm:text-xl {{ $backgroundHoverColorClass }} hover:text-brand-white"
    >
      <span wire:loading.remove wire:target="loadMore">
        {{ __('Load More') }}
      </span>

      <span wire:loading wire:target="loadMore" class="size-5 animate-spin rounded-full border-2 border-current border-t-transparent" aria-label="{{ __('Loading') }}"></span>
    </button>
  @endif
</div>
