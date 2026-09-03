@php
  $copy = Statamic\Facades\GlobalSet::findByHandle('footer')
    ?->inCurrentSite()
    ?->augmentedValue('copy')
    ?->value();
@endphp

@if($copy)
  <footer class="mt-16 bg-brand-grey p-8 font-typewriter text-lg leading-tight text-brand-black lg:text-xl lg:px-9">
    <div class="[&_p]:whitespace-pre-line">
      {!! $copy !!}
    </div>
  </footer>
@endif
