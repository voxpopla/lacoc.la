@php
  $alert = Statamic\Facades\GlobalSet::findByHandle('alert')?->inCurrentSite();
  $copy = $alert?->value('copy');
  $url = $alert?->value('url');
  $openInNewTab = $alert?->value('open_in_new_tab');
@endphp

@if($copy)
  <div
    x-data
    x-show="$store.alert_open"
    class="fixed inset-x-0 top-0 z-50 border-b border-brand-black bg-beige pl-4 pr-8 py-1.5 text-right font-spectral text-xl leading-none text-brand-black"
  >
    @if($url)
      <a
        href="{{ $url }}"
        class="underline"
        @if($openInNewTab) target="_blank" rel="noopener" @endif
      >
        {{ $copy }}
      </a>
    @else
      {{ $copy }}
    @endif

    <button
      type="button"
      class="absolute right-1 top-1/2 flex size-4 -translate-y-1/2 items-center justify-center border border-current text-xs leading-none"
      aria-label="{{ __('Dismiss alert') }}"
      @click="$store.alert_open = false"
    >
      &times;
    </button>
  </div>
@endif
