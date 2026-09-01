<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Statamic\Facades\Asset;
use Statamic\Facades\GlobalSet;

class SplitLayout extends Component
{
    public function __construct(
        public mixed $sections = [],
    ) {}

    public function render(): View
    {
        $logo = GlobalSet::findByHandle('site_settings')?->inCurrentSite()?->value('logo');

        return view('layouts.split', [
            'logo' => $logo ? Asset::find('assets::'.$logo) : null,
            'sections' => $this->sections,
        ]);
    }
}
