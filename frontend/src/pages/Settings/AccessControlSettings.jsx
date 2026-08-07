import { useEffect, useMemo, useState } from 'react';
import {
  Alert,
  Button,
  Card,
  Col,
  Divider,
  Form,
  Modal,
  Row,
  Select,
  Space,
  Statistic,
  Switch,
  Table,
  Tag,
  Tooltip,
  Typography,
  message,
} from 'antd';
import { Link } from 'react-router-dom';
import {
  CheckCircleOutlined,
  CloseCircleOutlined,
  DeleteOutlined,
  EditOutlined,
  PlusOutlined,
  ReloadOutlined,
  SafetyCertificateOutlined,
  ThunderboltOutlined,
} from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { request } from '@/request';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessEntityAction } from '@/utils/permissions';
import { buildAccessPreview } from './accessPreview';

const { Text } = Typography;

export default function AccessControlSettings() {
  const currentAdmin = useSelector(selectCurrentAdmin);
  const canReadRoles = canAccessEntityAction(currentAdmin, 'role', 'read');
  const canCreateRoles = canAccessEntityAction(currentAdmin, 'role', 'create');
  const canUpdateRoles = canAccessEntityAction(currentAdmin, 'role', 'update');
  const canReadPermissions = canAccessEntityAction(currentAdmin, 'permission', 'read');

  const [roles, setRoles] = useState([]);
  const [catalog, setCatalog] = useState([]);
  const [admins, setAdmins] = useState([]);
  const [orgUnits, setOrgUnits] = useState([]);
  const [bootstrapSummary, setBootstrapSummary] = useState(null);
  const [editingAdmin, setEditingAdmin] = useState(null);
  const [loading, setLoading] = useState(false);
  const [bootstrapping, setBootstrapping] = useState(false);
  const [saving, setSaving] = useState(false);
  const [form] = Form.useForm();

  const watchedRoleRefs = Form.useWatch('roleRefs', form) || [];
  const watchedPermissions = Form.useWatch('permissions', form) || [];
  const watchedFieldPermissions = Form.useWatch('fieldPermissions', form) || [];

  const load = async () => {
    if (!canReadRoles && !canReadPermissions) {
      return;
    }

    setLoading(true);
    const [rolesResponse, catalogResponse, adminsResponse, orgUnitsResponse] = await Promise.all([
      canReadRoles ? request.list({ entity: 'role', options: { page: 1, items: 100 } }) : Promise.resolve(null),
      canReadPermissions ? request.get({ entity: 'permission/catalog' }) : Promise.resolve(null),
      canReadRoles ? request.get({ entity: 'role/admin-access' }) : Promise.resolve(null),
      canReadRoles ? request.list({ entity: 'orgunit', options: { page: 1, items: 100 } }) : Promise.resolve(null),
    ]);

    setRoles(rolesResponse?.result || []);
    setCatalog(catalogResponse?.result || []);
    setAdmins(adminsResponse?.result || []);
    setOrgUnits(orgUnitsResponse?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  const bootstrapDefaults = async () => {
    setBootstrapping(true);
    const response = await request.post({
      entity: 'role/bootstrap-defaults',
      jsonData: {},
    });
    setBootstrapping(false);

    if (response?.success) {
      setBootstrapSummary(response.result || null);
      message.success(response.message || 'Default roles bootstrapped');
      load();
    }
  };

  const openEditor = (admin) => {
    setEditingAdmin(admin);
    form.setFieldsValue({
      roleRefs: (admin.roleRefs || []).map((role) => role._id),
      permissions: admin.directPermissions || [],
      fieldPermissions:
        admin.fieldPermissions?.length > 0
          ? admin.fieldPermissions
          : admin.deniedFields?.length
            ? [{ entity: 'global', deniedFields: admin.deniedFields }]
            : [],
      enabled: admin.enabled,
      manager: admin.managerId || undefined,
      orgUnit: admin.orgUnitId || undefined,
    });
  };

  const saveAdminAccess = async () => {
    const values = await form.validateFields();
    setSaving(true);
    const response = await request.patch({
      entity: `role/admin-access/${editingAdmin._id}`,
      jsonData: {
        ...values,
        deniedFields: Array.from(
          new Set((values.fieldPermissions || []).flatMap((entry) => entry.deniedFields || []))
        ),
      },
    });
    setSaving(false);

    if (response?.success) {
      message.success(response.message || 'Access updated');
      setEditingAdmin(null);
      form.resetFields();
      load();
    }
  };

  if (!canReadRoles) {
    return (
      <Alert
        type="warning"
        showIcon
        message="You do not have permission to manage access control settings."
      />
    );
  }

  const modules = Array.from(new Set(catalog.map((permission) => permission.module)));
  const entityOptions = Array.from(
    new Set([
      'global',
      ...catalog.map((permission) => permission.entity).filter(Boolean),
      ...roles.flatMap((role) => (role.fieldPermissions || []).map((entry) => entry.entity)).filter(Boolean),
    ])
  )
    .sort()
    .map((entity) => ({ value: entity, label: entity }));

  const accessPreview = useMemo(
    () =>
      buildAccessPreview({
        catalog,
        roles,
        selectedRoleIds: watchedRoleRefs,
        directPermissions: watchedPermissions,
        directFieldPermissions: watchedFieldPermissions,
      }),
    [catalog, roles, watchedRoleRefs, watchedPermissions, watchedFieldPermissions]
  );

  const getAdminCapabilityPreview = (admin) =>
    buildAccessPreview({
      catalog,
      roles,
      selectedRoleIds: (admin.roleRefs || []).map((role) => role._id),
      directPermissions: admin.directPermissions || [],
      directFieldPermissions: admin.fieldPermissions || [],
    });

  return (
    <Space direction="vertical" size={16} style={{ width: '100%' }}>
      <Space direction="vertical" size={4}>
        <Text strong>Access Control Console</Text>
        <Text type="secondary">
          Bootstrap starter roles, inspect the permission catalog, assign user access, and preview what a
          user can really reach before you save.
        </Text>
      </Space>

      <Row gutter={[16, 16]}>
        <Col xs={24} sm={8}>
          <Card size="small">
            <Statistic title="Tenant Roles" value={roles.length} prefix={<SafetyCertificateOutlined />} />
          </Card>
        </Col>
        <Col xs={24} sm={8}>
          <Card size="small">
            <Statistic title="Permission Keys" value={catalog.length} prefix={<ThunderboltOutlined />} />
          </Card>
        </Col>
        <Col xs={24} sm={8}>
          <Card size="small">
            <Statistic title="Modules" value={modules.length} prefix={<SafetyCertificateOutlined />} />
          </Card>
        </Col>
      </Row>

      <Card
        title="Operator Actions"
        extra={
          <Space>
            <Button icon={<ReloadOutlined />} onClick={load}>
              Refresh
            </Button>
            <Button type="primary" loading={bootstrapping} disabled={!canCreateRoles} onClick={bootstrapDefaults}>
              Bootstrap Defaults
            </Button>
          </Space>
        }
      >
        <Space direction="vertical" size={12} style={{ width: '100%' }}>
          <Text type="secondary">
            The bootstrap action seeds the tenant permission catalog plus starter roles for Admin, Sales,
            Finance, Vendor, and Marketing operators.
          </Text>
          {bootstrapSummary && (
            <Alert
              type="success"
              showIcon
              message={`Seeded ${bootstrapSummary.permissions} permission keys`}
              description={
                bootstrapSummary.roles?.length
                  ? bootstrapSummary.roles
                      .map((role) => `${role.name} (${role.permissionCount} permissions, ${role.dataScope} scope)`)
                      .join(', ')
                  : 'Default roles bootstrapped.'
              }
            />
          )}
          <Space wrap>
            <Button>
              <Link to="/records/role">Open Roles</Link>
            </Button>
            <Button>
              <Link to="/records/permission">Open Permissions</Link>
            </Button>
            <Button>
              <Link to="/records/auditlog">Open Audit Log</Link>
            </Button>
          </Space>
        </Space>
      </Card>

      <Card title="Current Roles">
        <Table
          rowKey={(role) => role._id || role.key}
          loading={loading}
          dataSource={roles}
          pagination={false}
          columns={[
            { title: 'Role', dataIndex: 'name' },
            { title: 'Key', dataIndex: 'key', render: (value) => <Tag>{value}</Tag> },
            { title: 'Scope', dataIndex: 'dataScope', render: (value) => <Tag color="blue">{value}</Tag> },
            {
              title: 'Permissions',
              dataIndex: 'permissions',
              render: (value) => (Array.isArray(value) ? value.length : 0),
            },
          ]}
        />
      </Card>

      <Card title="User Role Assignments">
        <Table
          rowKey="_id"
          loading={loading}
          dataSource={admins}
          pagination={{ pageSize: 8 }}
          columns={[
            { title: 'Admin', dataIndex: 'name' },
            { title: 'Email', dataIndex: 'email' },
            {
              title: 'Assigned Roles',
              dataIndex: 'roleRefs',
              render: (value) =>
                (value || []).length ? (
                  <Space wrap>
                    {value.map((role) => (
                      <Tag key={role._id || role.key}>{role.name}</Tag>
                    ))}
                  </Space>
                ) : (
                  <Text type="secondary">No custom roles</Text>
                ),
            },
            {
              title: 'Field Restrictions',
              dataIndex: 'fieldPermissions',
              render: (value) => (Array.isArray(value) ? value.length : 0),
            },
            {
              title: 'Route Access',
              render: (_, record) => {
                const routes = getAdminCapabilityPreview(record).capabilityGroups.routes || [];
                const allowedRoutes = routes.filter((item) => item.allowed);

                return (
                  <Tooltip title={allowedRoutes.map((item) => item.label).join(', ') || 'No route access'}>
                    <Tag color={allowedRoutes.length ? 'green' : 'default'}>{allowedRoutes.length} routes</Tag>
                  </Tooltip>
                );
              },
            },
            {
              title: 'Actions',
              render: (_, record) => {
                const preview = getAdminCapabilityPreview(record);
                const allowedActions = [
                  ...(preview.capabilityGroups.workflows || []),
                  ...(preview.capabilityGroups.ai || []),
                ].filter((item) => item.allowed);

                return (
                  <Tooltip title={allowedActions.map((item) => item.label).join(', ') || 'No workflow or AI access'}>
                    <Tag color={allowedActions.length ? 'blue' : 'default'}>{allowedActions.length} actions</Tag>
                  </Tooltip>
                );
              },
            },
            {
              title: 'Status',
              dataIndex: 'enabled',
              render: (value) => (
                <Tag color={value ? 'green' : 'red'}>{value ? 'Enabled' : 'Disabled'}</Tag>
              ),
            },
            {
              title: '',
              render: (_, record) => (
                <Button
                  icon={<EditOutlined />}
                  disabled={!canUpdateRoles}
                  onClick={() => openEditor(record)}
                >
                  Edit Access
                </Button>
              ),
            },
          ]}
        />
      </Card>

      <Card title="Permission Catalog">
        {canReadPermissions ? (
          <Table
            rowKey="key"
            loading={loading}
            dataSource={catalog}
            pagination={{ pageSize: 10 }}
            columns={[
              { title: 'Module', dataIndex: 'module', render: (value) => <Tag color="purple">{value}</Tag> },
              { title: 'Permission', dataIndex: 'key' },
              { title: 'Action', dataIndex: 'action', render: (value) => <Tag>{value}</Tag> },
            ]}
          />
        ) : (
          <Alert type="info" showIcon message="You can view roles, but permission catalog access is restricted." />
        )}
      </Card>

      <Modal
        title={editingAdmin ? `Edit Access: ${editingAdmin.name}` : 'Edit Access'}
        open={Boolean(editingAdmin)}
        width={760}
        onCancel={() => {
          setEditingAdmin(null);
          form.resetFields();
        }}
        onOk={saveAdminAccess}
        okText="Save Access"
        confirmLoading={saving}
      >
        <Form form={form} layout="vertical">
          <Form.Item name="enabled" label="Enabled" valuePropName="checked" initialValue>
            <Switch />
          </Form.Item>
          <Form.Item name="roleRefs" label="Assigned roles">
            <Select
              mode="multiple"
              options={roles.map((role) => ({
                value: role._id,
                label: `${role.name} (${role.dataScope})`,
              }))}
            />
          </Form.Item>
          <Form.Item name="permissions" label="Direct permissions">
            <Select
              mode="tags"
              options={catalog.map((permission) => ({
                value: permission.key,
                label: permission.key,
              }))}
            />
          </Form.Item>

          <Divider orientation="left" plain>
            Scoped Field Restrictions
          </Divider>

          <Form.List name="fieldPermissions">
            {(fields, { add, remove }) => (
              <Space direction="vertical" size={12} style={{ width: '100%' }}>
                {fields.map((field) => (
                  <Card
                    key={field.key}
                    size="small"
                    title={`Restriction ${field.name + 1}`}
                    extra={
                      <Button type="text" danger icon={<DeleteOutlined />} onClick={() => remove(field.name)} />
                    }
                  >
                    <Row gutter={12}>
                      <Col span={10}>
                        <Form.Item
                          name={[field.name, 'entity']}
                          label="Entity"
                          rules={[{ required: true, message: 'Select an entity' }]}
                        >
                          <Select options={entityOptions} />
                        </Form.Item>
                      </Col>
                      <Col span={14}>
                        <Form.Item
                          name={[field.name, 'deniedFields']}
                          label="Denied fields"
                          rules={[{ required: true, message: 'Add at least one field' }]}
                        >
                          <Select mode="tags" tokenSeparators={[',']} placeholder="margin, bankAccount, taxId" />
                        </Form.Item>
                      </Col>
                    </Row>
                  </Card>
                ))}
                <Button icon={<PlusOutlined />} onClick={() => add({ entity: undefined, deniedFields: [] })}>
                  Add field restriction
                </Button>
              </Space>
            )}
          </Form.List>

          <Form.Item name="manager" label="Manager" style={{ marginTop: 16 }}>
            <Select
              allowClear
              options={admins
                .filter((admin) => admin._id !== editingAdmin?._id)
                .map((admin) => ({ value: admin._id, label: admin.name }))}
            />
          </Form.Item>
          <Form.Item name="orgUnit" label="Org unit">
            <Select
              allowClear
              options={orgUnits.map((orgUnit) => ({ value: orgUnit._id, label: orgUnit.name }))}
            />
          </Form.Item>

          <Card size="small" title="Live Access Preview" style={{ marginTop: 24, borderRadius: '8px' }}>
            <Space direction="vertical" size={16} style={{ width: '100%' }}>
              <div>
                <Text strong style={{ display: 'block', marginBottom: 8, fontSize: '13px', color: '#555' }}>
                  Module Coverage
                </Text>
                <Space wrap>
                  {accessPreview.moduleSummary.length ? (
                    accessPreview.moduleSummary.map((item) => (
                      <Tag key={item.module} color="purple" style={{ border: 'none', background: '#f9f0ff', padding: '2px 8px' }}>
                        <span style={{ fontWeight: 600 }}>{item.module.toUpperCase()}</span>: {item.count}
                      </Tag>
                    ))
                  ) : (
                    <Text type="secondary" style={{ fontSize: '13px' }}>No module access yet.</Text>
                  )}
                </Space>
              </div>

              <Divider style={{ margin: '4px 0' }} />

              <div>
                <Text strong style={{ display: 'block', marginBottom: 8, fontSize: '13px', color: '#555' }}>
                  Routes You Can Open
                </Text>
                <Space wrap>
                  {accessPreview.capabilityGroups.routes?.map((capability) => (
                    <Tag
                      key={capability.key}
                      color={capability.allowed ? 'green' : 'error'}
                      icon={capability.allowed ? <CheckCircleOutlined /> : <CloseCircleOutlined />}
                      style={{ padding: '2px 8px' }}
                    >
                      {capability.label}
                    </Tag>
                  ))}
                </Space>
              </div>

              <div>
                <Text strong style={{ display: 'block', marginBottom: 8, fontSize: '13px', color: '#555' }}>
                  Workflow Actions You Can Run
                </Text>
                <Space wrap>
                  {accessPreview.capabilityGroups.workflows?.map((capability) => (
                    <Tag
                      key={capability.key}
                      color={capability.allowed ? 'blue' : 'error'}
                      icon={capability.allowed ? <CheckCircleOutlined /> : <CloseCircleOutlined />}
                      style={{ padding: '2px 8px' }}
                    >
                      {capability.label}
                    </Tag>
                  ))}
                </Space>
              </div>

              <div>
                <Text strong style={{ display: 'block', marginBottom: 8, fontSize: '13px', color: '#555' }}>
                  AI Actions You Can Use
                </Text>
                <Space wrap>
                  {accessPreview.capabilityGroups.ai?.map((capability) => (
                    <Tag
                      key={capability.key}
                      color={capability.allowed ? 'purple' : 'error'}
                      icon={capability.allowed ? <CheckCircleOutlined /> : <CloseCircleOutlined />}
                      style={{ padding: '2px 8px' }}
                    >
                      {capability.label}
                    </Tag>
                  ))}
                </Space>
              </div>

              <Divider style={{ margin: '4px 0' }} />

              <div>
                <Text type="secondary" style={{ display: 'block', marginBottom: 8, fontSize: '12px' }}>
                  Raw Effective Permissions (Secondary Detail)
                </Text>
                <Space wrap>
                  {accessPreview.effectivePermissions.length ? (
                    accessPreview.effectivePermissions.map((permission) => (
                      <Tag key={permission} color="default" style={{ fontSize: '11px', opacity: 0.75 }}>
                        {permission}
                      </Tag>
                    ))
                  ) : (
                    <Text type="secondary" style={{ fontSize: '12px' }}>No effective permissions yet.</Text>
                  )}
                </Space>
              </div>

              <div>
                <Text type="secondary" style={{ display: 'block', marginBottom: 8, fontSize: '12px' }}>
                  Scoped Field Restrictions
                </Text>
                <Space direction="vertical" style={{ width: '100%' }}>
                  {accessPreview.fieldPermissions.length ? (
                    accessPreview.fieldPermissions.map((entry) => (
                      <Card key={entry.entity} size="small" style={{ background: '#fafafa', border: '1px solid #f0f0f0' }}>
                        <Space wrap>
                          <Tag color="gold" style={{ fontWeight: 600 }}>{entry.entity}</Tag>
                          {entry.deniedFields.map((field) => (
                            <Tag key={`${entry.entity}-${field}`} style={{ fontSize: '11px' }}>{field}</Tag>
                          ))}
                        </Space>
                      </Card>
                    ))
                  ) : (
                    <Text type="secondary" style={{ fontSize: '12px' }}>No scoped field restrictions configured.</Text>
                  )}
                </Space>
              </div>
            </Space>
          </Card>
        </Form>
      </Modal>
    </Space>
  );
}
