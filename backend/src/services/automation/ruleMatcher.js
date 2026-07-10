const getPath = (source, path) =>
  String(path || '')
    .split('.')
    .filter(Boolean)
    .reduce((value, key) => (value == null ? undefined : value[key]), source);

const compare = (left, operator, right) => {
  switch (operator) {
    case 'eq':
      return left === right;
    case 'ne':
      return left !== right;
    case 'gt':
      return Number(left) > Number(right);
    case 'gte':
      return Number(left) >= Number(right);
    case 'lt':
      return Number(left) < Number(right);
    case 'lte':
      return Number(left) <= Number(right);
    case 'contains':
      return String(left || '').includes(String(right || ''));
    case 'in':
      return Array.isArray(right) && right.includes(left);
    default:
      return false;
  }
};

const eventMatchesRule = (rule = {}, event = {}) => {
  if (!rule.enabled && rule.enabled !== undefined) return false;
  const trigger = rule.trigger || {};
  if (trigger.type !== 'event') return false;
  if (trigger.eventType && trigger.eventType !== event.type) return false;

  return (rule.conditions || []).every((condition) =>
    compare(getPath(event, condition.field), condition.operator || 'eq', condition.value)
  );
};

module.exports = { eventMatchesRule };
