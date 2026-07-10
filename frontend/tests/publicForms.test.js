import test from 'node:test';
import assert from 'node:assert/strict';

import {
  buildPublicFormUrl,
  parseFieldList,
  buildSubmissionPayload,
} from '../src/pages/Workflows/publicForms.js';

test('parseFieldList normalizes comma-separated fields', () => {
  assert.deepEqual(parseFieldList(' firstName, email , message, '), ['firstName', 'email', 'message']);
});

test('buildPublicFormUrl joins the current origin with the hosted form route', () => {
  assert.equal(buildPublicFormUrl('https://erp.example.com', 'demo-request'), 'https://erp.example.com/forms/public/demo-request');
});

test('buildSubmissionPayload keeps only configured fields that have values', () => {
  assert.deepEqual(
    buildSubmissionPayload(['firstName', 'email', 'message'], {
      firstName: 'Ada',
      email: 'ada@example.com',
      message: '',
      ignored: 'value',
    }),
    {
      firstName: 'Ada',
      email: 'ada@example.com',
    }
  );
});
