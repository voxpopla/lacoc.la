<x-app-layout>
  <div
    x-data
    class="header transition duration-300 lg:fixed lg:left-0 lg:top-0 lg:w-1/2 lg:h-screen lg:flex lg:flex-col lg:border-r-[1.5px] lg:border-black lg:pt-12"
    :class="{
      'lg:pt-12': $store.alert_open,
      'lg:pt-4': !$store.alert_open,
    }"
  >
    @if($logo)
      <div class="p-4 border-b-[1.5px] border-black lg:border-none lg:pt-8 lg:px-9">
        <button class="w-full" @click="scrollTo({ top: 0, behavior: 'smooth' })">
          <img src="{{ $logo->url() }}" alt="{{ $logo->get('alt', config('app.name')) }}" class="w-full">
        </button>
      </div>
    @endif

    @if(count($sections->raw()))
      <div class="hidden lg:block lg:mt-auto">
        <nav class="nav">
          @foreach ($sections as $section)
            @php
              $sectionColor = Statamic\View\Blade\value($section['section_color'] ?? 'yellow');
              $sectionSlug = Str::slug($section['header']);
              $activeColorClasses = config("theme.colors.background.{$sectionColor}", config('theme.colors.background.yellow'));
              $hoverColorClasses = config("theme.colors.background_light_hover.{$sectionColor}", config('theme.colors.background_light_hover.yellow'));
            @endphp

            <a
              href="#{{ $sectionSlug }}"
              class="nav-item nav-item--{{ $sectionColor }} {{ $hoverColorClasses }} block p-4 sm:text-[54px] leading-none border-t-[1.5px] border-black transition duration-300 lg:px-9"
              x-data
              :class="{
                'active {{ $activeColorClasses }}': $store.active_section == '{{ $sectionSlug }}',
              }"
              @click.prevent="
                scrollTo({
                  top: elementPosition(document.getElementById('{{ $sectionSlug }}')).top - 87,
                  behavior: 'smooth'
                })
                $store.active_section = '{{ $sectionSlug }}'
              "
            >
              <div class="w-full">
                {{ $section['header'] }}
              </div>
            </a>
          @endforeach
        </nav>
      </div>
    @endif
  </div>

  <div
    x-data
    class="lg:w-1/2 lg:ml-auto lg:pt-12"
    :class="{
      'lg:pt-12': $store.alert_open,
      'lg:pt-4': !$store.alert_open,
    }"
  >

    {{ $slot }}

    @include('sections.footer')
  </div>
</x-app-layout>
