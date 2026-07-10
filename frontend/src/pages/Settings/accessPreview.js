import { buildCapabilityGroups } from './accessCapabilities.js';

const uniq = (items) => [...new Set(items)];

const mergeFieldPermissions = (entries = []) => {
  const merged = [];

  for (const entry of entries) {
    if (!entry?.entity) continue;

    const existing = merged.find((item) => item.entity === entry.entity);
    if (!existing) {
      merged.push({
        entity: entry.entity,
        deniedFields: uniq(entry.deniedFields || []).sort(),
      });
      continue;
    }

    existing.deniedFields = uniq([...(existing.deniedFields || []), ...(entry.deniedFields || [])]).sort();
  }

  return merged.sort((left, right) => left.entity.localeCompare(right.entity));
};

export const buildAccessPreview = ({
  catalog = [],
  roles = [],
  selectedRoleIds = [],
  directPermissions = [],
  directFieldPermissions = [],
}) => {
  const selectedRoles = roles.filter((role) => selectedRoleIds.includes(role._id));
  const effectivePermissions = uniq([
    ...selectedRoles.flatMap((role) => role.permissions || []),
    ...directPermissions,
  ]).sort();

  const moduleCounts = new Map();
  for (const permission of effectivePermissions) {
    const catalogEntry = catalog.find((item) => item.key === permission);
    const moduleName = catalogEntry?.module || permission.split('.')[0] || 'other';
    moduleCounts.set(moduleName, (moduleCounts.get(moduleName) || 0) + 1);
  }

  const moduleSummary = Array.from(moduleCounts.entries())
    .map(([module, count]) => ({ module, count }))
    .sort((left, right) => left.module.localeCompare(right.module));

  const fieldPermissions = mergeFieldPermissions([
    ...selectedRoles.flatMap((role) => role.fieldPermissions || []),
    ...directFieldPermissions,
  ]);

  const capabilityGroups = buildCapabilityGroups(effectivePermissions);

  return {
    effectivePermissions,
    moduleSummary,
    fieldPermissions,
    capabilityGroups,
  };
};
