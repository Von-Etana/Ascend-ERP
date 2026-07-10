import EntityListWorkbench from './EntityListWorkbench';
import { COMPANY_TYPE_OPTIONS, companyColumns } from './crmWorkbenches.jsx';

export default function CompanyWorkbench() {
  return (
    <EntityListWorkbench
      title="Company Workspace"
      description="Manage organization records shared across CRM, sales, operations, and partner/vendor relationships."
      entity="company"
      permission="crm.company.read"
      createLabel="New Company"
      formFields={[
        { name: 'name', label: 'Name', required: true },
        { name: 'type', label: 'Type', type: 'select', options: COMPANY_TYPE_OPTIONS, initialValue: 'customer' },
        { name: 'email', label: 'Email' },
        { name: 'phone', label: 'Phone' },
        { name: 'website', label: 'Website' },
        { name: 'industry', label: 'Industry' },
        { name: 'address', label: 'Address', type: 'textarea', rows: 2 },
      ]}
      columns={[companyColumns]}
    />
  );
}
