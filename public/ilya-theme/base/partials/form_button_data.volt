{# form_button_data(button, key, style) #}
{% set baseclass = 'ilya-form-'~ style ~ '-button ilya-form-'~ style~ '-button-'~ key %}

<input{{ rtrim(' '~ ((button['tags'] is defined) ? button['tags'] : null)) }} value="{{ ((button['label'] is defined) ? button['label'] : null) }}" title="{{ ((button['popup'] is defined) ? button['popup'] : null) }}" type="submit"{{ ((style is defined) ? (' class="' ~ baseclass ~ '"') : '') }} />