const getPath = (source, path) =>
  String(path || '')
    .split('.')
    .filter(Boolean)
    .reduce((value, key) => (value == null ? undefined : value[key]), source);

const interpolateString = (value, context) =>
  value.replace(/\{\{\s*([^}]+?)\s*\}\}/g, (_, path) => {
    const replacement = getPath(context, path);
    if (replacement == null) return '';
    if (typeof replacement === 'object') return JSON.stringify(replacement);
    return String(replacement);
  });

const interpolateTemplate = (value, context = {}) => {
  if (typeof value === 'string') return interpolateString(value, context);
  if (Array.isArray(value)) return value.map((item) => interpolateTemplate(item, context));
  if (value && typeof value === 'object') {
    return Object.entries(value).reduce((next, [key, item]) => {
      next[key] = interpolateTemplate(item, context);
      return next;
    }, {});
  }
  return value;
};

module.exports = {
  getPath,
  interpolateTemplate,
};
