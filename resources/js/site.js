import A11yDialog from 'a11y-dialog';
Object.assign(window, { A11yDialog });

import intersect from '@alpinejs/intersect';
import Precognition from 'laravel-precognition-alpine';
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
Alpine.plugin(Precognition);
Alpine.data('signupForm', ({ form }) => ({
  form,
  submitted: false,
  submissionError: '',

  submit() {
    if (this.form.processing) return;

    this.submitted = false;
    this.submissionError = '';

    this.form.submit({
      onSuccess: (response) => {
        if (response?.data?.redirect) {
          window.location.href = response.data.redirect;
          return;
        }

        this.form.reset();
        this.form.setErrors({});
        this.submitted = true;
      },
      onValidationError: () => {
        this.submissionError = 'Please check the highlighted fields and try again.';
      },
    }).catch((error) => {
      if (error.response?.status !== 422) {
        this.submissionError = error.response?.status === 419
          ? 'Your session has expired. Please refresh the page and try again.'
          : 'We could not submit your form. Please try again.';
      }
    });
  },
}));
Alpine.store('active_section', '');
Alpine.store('alert_open', true);

Object.assign(window, { Alpine, Livewire });

Livewire.start();

import.meta.webpackHot?.accept(console.error);
