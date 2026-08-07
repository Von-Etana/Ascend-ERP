export const parseFieldList = (value = '') =>
  String(value)
    .split(',')
    .map((item) => item.trim())
    .filter(Boolean);

export const buildPublicFormUrl = (origin, slug) =>
  `${String(origin || '').replace(/\/$/, '')}/forms/public/${slug}`;

export const buildSubmissionPayload = (fields = [], values = {}) =>
  fields.reduce((acc, field) => {
    const value = values[field];
    if (value !== undefined && value !== null && value !== '') {
      acc[field] = value;
    }
    return acc;
  }, {});
