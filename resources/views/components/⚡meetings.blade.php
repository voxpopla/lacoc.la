<?php

use Carbon\CarbonInterface;
use Livewire\Component;
use Statamic\Facades\Asset;
use Statamic\Facades\Entry;

new class extends Component
{
    public int $pastLimit = 5;
    public string $backgroundDarkColorClass = '';
    public string $backgroundHoverColorClass = '';
    public string $borderDarkColorClass = '';
    public string $textDarkHoverColorClass = '';
    public string $textLightHoverColorClass = '';

    public function loadMore(): void
    {
        $this->pastLimit += 5;
    }

    public function with(): array
    {
        $baseQuery = Entry::query()
            ->where('collection', 'meetings')
            ->whereStatus('published');

        $upcoming = (clone $baseQuery)
            ->where('date', '>=', now())
            ->orderBy('date')
            ->get();

        $pastQuery = (clone $baseQuery)
            ->where('date', '<', now());

        return [
            'upcomingMeetings' => $upcoming,
            'pastMeetings' => (clone $pastQuery)
                ->orderBy('date', 'desc')
                ->limit($this->pastLimit)
                ->get(),
            'hasMorePastMeetings' => $this->pastLimit < (clone $pastQuery)->count(),
        ];
    }

    public function formatMeetingDate($date): string
    {
        if ($date instanceof CarbonInterface) {
            return $date->format('F j, Y g:i A');
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
  @foreach($upcomingMeetings as $meeting)
    @include('components.partials.meeting-card', ['meeting' => $meeting])
  @endforeach

  @foreach($pastMeetings as $meeting)
    @include('components.partials.meeting-card', ['meeting' => $meeting])
  @endforeach

  @if($hasMorePastMeetings)
    <button
      type="button"
      wire:click="loadMore"
      wire:loading.attr="disabled"
      wire:target="loadMore"
      class="mt-7 flex w-full items-center justify-center border border-current p-4 font-typewriter text-lg uppercase leading-none underline transition cursor-pointer disabled:opacity-70 sm:text-xl {{ $backgroundHoverColorClass }} hover:text-brand-white"
    >
      <span wire:loading.remove wire:target="loadMore">
        {{ __('Past Meetings') }}
      </span>

      <span wire:loading wire:target="loadMore" class="size-5 animate-spin rounded-full border-2 border-current border-t-transparent" aria-label="{{ __('Loading') }}"></span>
    </button>
  @endif
</div>
