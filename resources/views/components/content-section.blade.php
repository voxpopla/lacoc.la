@props([
  'title',
  'description' => null,
])

<section {{ $attributes->class('p-4 lg:px-9') }}>
  <h2 class="text-3xl leading-none sm:text-[2.5rem]">
    {{ $title }}
  </h2>

  @if($description)
    <div class="mt-8 font-alpina text-2xl leading-none sm:text-[2.125rem]">
      {{ $description }}
    </div>
  @endif

  {{ $slot }}
</section>
