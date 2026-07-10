import EntityListWorkbench from './EntityListWorkbench';
import { LEAD_STATUS_OPTIONS, leadColumns } from './crmWorkbenches.jsx';

export default function LeadWorkbench() {
  return (
    <EntityListWorkbench
      title="Lead Workspace"
      description="Qualify incoming leads, keep contact and company context visible, and move operators toward the deal pipeline."
      entity="lead"
      permission="crm.lead.read"
      createLabel="New Lead"
      resourceDefs={[
        { key: 'contacts', entity: 'contact' },
        { key: 'companies', entity: 'company' },
      ]}
      formFields={[
        { name: 'title', label: 'Title', required: true },
        { name: 'contact', label: 'Contact', type: 'select', resourceKey: 'contacts' },
        { name: 'company', label: 'Company', type: 'select', resourceKey: 'companies' },
        { name: 'source', label: 'Source' },
        { name: 'status', label: 'Status', type: 'select', options: LEAD_STATUS_OPTIONS, initialValue: 'new' },
        { name: 'score', label: 'Score', type: 'number', min: 0, initialValue: 0 },
      ]}
      columns={[leadColumns]}
    />
  );
}
