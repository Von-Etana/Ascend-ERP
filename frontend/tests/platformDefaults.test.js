import test from 'node:test';
import assert from 'node:assert/strict';

import {
  DEFAULT_CURRENCY_CODE,
  DEFAULT_CURRENCY_SYMBOL,
  DEFAULT_MONEY_FORMAT_SETTINGS,
} from '../src/constants/platformDefaults.js';

test('platform defaults use Nigerian naira formatting', () => {
  assert.equal(DEFAULT_CURRENCY_CODE, 'NGN');
  assert.equal(DEFAULT_CURRENCY_SYMBOL, '₦');
  assert.deepEqual(DEFAULT_MONEY_FORMAT_SETTINGS, {
    currency_code: 'NGN',
    default_currency_code: 'NGN',
    currency_symbol: '₦',
    currency_position: 'before',
    thousand_sep: ',',
    decimal_sep: '.',
    cent_precision: 2,
    zero_format: false,
  });
});
