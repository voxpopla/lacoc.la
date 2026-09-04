<?php

use Carbon\CarbonInterface;
use Livewire\Component;
use Statamic\Facades\Asset;
use Statamic\Facades\Entry;

new class extends Component
{
    public int $limit = 5;
    public string $textLightHoverColorClass = '';
    public string $backgroundHoverColorClass = '';

    public function loadMore(): void
    {
        $this->limit += 5;
    }

    public function with(): array
    {
        $query = Entry::query()
            ->where('collection', 'news')
            ->whereStatus('published');

        return [
            'newsEntries' => (clone $query)
                ->orderBy('date', 'desc')
                ->limit($this->limit)
                ->get(),
            'hasMore' => $this->limit < (clone $query)->count(),
        ];
    }

    public function formatDate($date): string
    {
        if ($date instanceof CarbonInterface) {
            return $date->format('F j, Y');
        }

        return $date ? (string) $date : '';
    }

    public function linkUrl($link): ?string
    {
        $linkType = $this->fieldValue($link, 'link_type');

        if ($linkType !== 'file') {
            return $this->fieldValue($link, 'url');
        }

        $file = $this->fieldValue($link, 'file');

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

    public function linkTarget($link): ?string
    {
        return $this->fieldValue($link, 'open_in_new_tab') ? '_blank' : null;
    }

    public function linkRel($link): ?string
    {
        return $this->fieldValue($link, 'open_in_new_tab') ? 'noopener' : null;
    }

    public function fieldValue($data, string $key)
    {
        $value = $data[$key] ?? null;

        return is_object($value) && method_exists($value, 'value')
            ? $value->value()
            : $value;
    }
};

?>

<div class="mt-8 lg:mt-18">
  @foreach($newsEntries as $entry)
    @php
      $date = $entry->augmentedValue('date')->value();
      $link = $entry->augmentedValue('link')->value() ?? [];
      $linkUrl = $this->linkUrl($link);
    @endphp

    <article class="flex items-end justify-between gap-6 border-b border-current py-4 font-spectral text-lg leading-tight sm:text-xl">
      <div>
        <h3>
          {{ $entry->get('title') }}
        </h3>

        @if($date)
          <div>
            {{ $this->formatDate($date) }}
          </div>
        @endif
      </div>

      @if($linkUrl)
        <a
          href="{{ $linkUrl }}"
          class="shrink-0 underline uppercase transition {{ $textLightHoverColorClass }}"
          @if($this->linkTarget($link)) target="{{ $this->linkTarget($link) }}" @endif
          @if($this->linkRel($link)) rel="{{ $this->linkRel($link) }}" @endif
        >
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
      class="mt-7 flex w-full items-center justify-center border border-current p-4 font-spectral text-lg uppercase leading-none underline transition cursor-pointer disabled:opacity-70 sm:text-xl {{ $backgroundHoverColorClass }} hover:text-brand-white"
    >
      <span wire:loading.remove wire:target="loadMore">
        {{ __('Load More') }}
      </span>

      <span wire:loading wire:target="loadMore" class="size-5 animate-spin rounded-full border-2 border-current border-t-transparent" aria-label="{{ __('Loading') }}"></span>
    </button>
  @endif
</div>
