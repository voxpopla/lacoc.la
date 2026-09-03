import A11yDialog from 'a11y-dialog';
Object.assign(window, { A11yDialog });

import intersect from '@alpinejs/intersect';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

function elementPosition(_el) {
  const target = _el,
    target_width = target.offsetWidth,
    target_height = target.offsetHeight;

  let gleft = 0,
    gtop = 0,
    rect = {};

  const moonwalk = function (_parent) {
    if (_parent) {
      gleft += _parent.offsetLeft;
      gtop += _parent.offsetTop;
      moonwalk(_parent.offsetParent);
    } else {
      return (rect = {
        top: target.offsetTop + gtop,
        left: target.offsetLeft + gleft,
        bottom: target.offsetTop + gtop + target_height,
        right: target.offsetLeft + gleft + target_width,
      });
    }
  };
  moonwalk(target.offsetParent);
  return rect;
}
Object.assign(window, { elementPosition });

Alpine.plugin(intersect);
Alpine.store('active_section', '');
Alpine.store('alert_open', true);

Object.assign(window, { Alpine, Livewire });

Livewire.start();

import.meta.webpackHot?.accept(console.error);
