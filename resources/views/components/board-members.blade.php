@php
  use Statamic\Facades\Asset;
  use Statamic\Facades\Entry;

  $members = Entry::query()
    ->where('collection', 'board_members')
    ->whereStatus('published')
    ->orderBy('title')
    ->get();

  $imageUrl = function ($image) {
    if (! $image) {
      return null;
    }

    if (is_string($image)) {
      return Asset::find("assets::{$image}")?->url() ?? asset("assets/{$image}");
    }

    if (is_object($image) && method_exists($image, 'url')) {
      return $image->url();
    }

    if ($firstImage = data_get($image, '0')) {
      return is_object($firstImage) && method_exists($firstImage, 'url')
        ? $firstImage->url()
        : data_get($firstImage, 'url');
    }

    return data_get($image, 'url');
  };
@endphp

<div class="mt-9 grid grid-cols-2 gap-x-3 gap-y-6 sm:grid-cols-3 xl:grid-cols-4 xl:gap-x-4 xl:gap-y-7 2xl:grid-cols-5">
  @foreach($members as $member)
    @php
      $image = $member->augmentedValue('image')->value();
      $url = $imageUrl($image);
    @endphp

    <article>
      @if($url)
        <img src="{{ $url }}" alt="{{ $member->get('title') }}" class="aspect-square w-full object-cover">
      @else
        <div class="aspect-square w-full bg-brand-grey"></div>
      @endif

      <h3 class="mt-2 font-typewriter text-xs leading-none">
        {{ $member->get('title') }}
      </h3>

      @if($member->get('member_title'))
        <div class="mt-1 font-typewriter text-xs leading-none">
          {{ $member->get('member_title') }}
        </div>
      @endif
    </article>
  @endforeach
</div>
