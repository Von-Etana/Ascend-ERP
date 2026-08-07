import EntityListWorkbench from './EntityListWorkbench';
import { customerColumns } from './crmWorkbenches.jsx';

export default function CustomerWorkbench() {
  return (
    <EntityListWorkbench
      title="Customer Workspace"
      description="Manage customer records, lifecycle stage, lead scores, and company linkage from a focused CRM operator surface."
      entity="client"
      permission="crm.client.read"
      createLabel="New Customer"
      resourceDefs={[{ key: 'companies', entity: 'company' }]}
      formFields={[
        { name: 'name', label: 'Name', required: true },
        { name: 'email', label: 'Email' },
        { name: 'phone', label: 'Phone' },
        { name: 'country', label: 'Country' },
        { name: 'address', label: 'Address', type: 'textarea', rows: 2 },
        { name: 'company', label: 'Company', type: 'select', resourceKey: 'companies' },
      ]}
      columns={[customerColumns]}
    />
  );
}
