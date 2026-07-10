import EntityListWorkbench from './EntityListWorkbench';
import { CUSTOMER_STAGE_OPTIONS, peopleColumns } from './crmWorkbenches.jsx';

export default function PeopleWorkbench() {
  return (
    <EntityListWorkbench
      title="People Workspace"
      description="Track contacts across customers, companies, deals, and campaigns with a structured CRM people view."
      entity="contact"
      permission="crm.contact.read"
      createLabel="New Person"
      resourceDefs={[{ key: 'companies', entity: 'company' }]}
      formFields={[
        { name: 'firstName', label: 'First Name', required: true },
        { name: 'lastName', label: 'Last Name' },
        { name: 'email', label: 'Email' },
        { name: 'phone', label: 'Phone' },
        { name: 'whatsapp', label: 'WhatsApp' },
        { name: 'company', label: 'Company', type: 'select', resourceKey: 'companies' },
        { name: 'lifecycleStage', label: 'Lifecycle Stage', type: 'select', options: CUSTOMER_STAGE_OPTIONS, initialValue: 'lead' },
      ]}
      columns={[peopleColumns]}
    />
  );
}
