import { Tag } from 'antd';
import { currencyOptions } from '@/utils/currencyList';
import { DEFAULT_CURRENCY_CODE, DEFAULT_TIMEZONE } from '@/constants/platformDefaults';
import EntityListWorkbench from './EntityListWorkbench';

const CURRENCY_OPTIONS = currencyOptions();

const renderFlag = (value, positive = 'Yes', negative = 'No') => (
  <Tag color={value ? 'green' : 'default'}>{value ? positive : negative}</Tag>
);

const formatCurrencyValue = (value, fallbackCurrency = DEFAULT_CURRENCY_CODE) =>
  value?.amount ? `${value.currency || fallbackCurrency} ${value.amount}`.trim() : '-';

export function SupplierWorkbench() {
  return (
    <EntityListWorkbench
      title="Suppliers"
      description="Manage supplier records, contact details, and procurement readiness from one focused workspace."
      entity="supplier"
      permission="supplier.read"
      createLabel="Add Supplier"
      formFields={[
        { name: 'name', label: 'Supplier Name', required: true },
        { name: 'email', label: 'Email' },
        { name: 'phone', label: 'Phone' },
        { name: 'address', label: 'Address', type: 'textarea', rows: 2 },
        { name: 'paymentTerms', label: 'Payment Terms' },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          initialValue: 'active',
          options: [
            { value: 'prospect', label: 'Prospect' },
            { value: 'active', label: 'Active' },
            { value: 'inactive', label: 'Inactive' },
          ],
        },
      ]}
      columns={[
        { title: 'Supplier', dataIndex: 'name' },
        { title: 'Email', dataIndex: 'email' },
        { title: 'Phone', dataIndex: 'phone' },
        { title: 'Payment Terms', dataIndex: 'paymentTerms' },
        {
          title: 'Status',
          dataIndex: 'status',
          render: (value) => <Tag color={value === 'active' ? 'green' : value === 'prospect' ? 'gold' : 'default'}>{value || 'active'}</Tag>,
        },
      ]}
    />
  );
}

export function BranchWorkbench() {
  return (
    <EntityListWorkbench
      title="Branches"
      description="Track branch locations, contact details, and operational ownership for the tenant."
      entity="branch"
      permission="branch.read"
      createLabel="Add Branch"
      resourceDefs={[{ key: 'admins', entity: 'admin' }]}
      formFields={[
        { name: 'name', label: 'Branch Name', required: true },
        { name: 'code', label: 'Code' },
        { name: 'email', label: 'Email' },
        { name: 'phone', label: 'Phone' },
        { name: 'address', label: 'Address', type: 'textarea', rows: 2 },
        {
          name: 'manager',
          label: 'Manager',
          type: 'select',
          resourceKey: 'admins',
          optionLabel: 'name',
        },
      ]}
      columns={[
        { title: 'Branch', dataIndex: 'name' },
        { title: 'Code', dataIndex: 'code' },
        { title: 'Manager', render: (_, record) => record.manager?.name || '-' },
        { title: 'Email', dataIndex: 'email' },
        { title: 'Phone', dataIndex: 'phone' },
      ]}
    />
  );
}

export function CompanyWorkspaceWorkbench() {
  return (
    <EntityListWorkbench
      title="Company Workspaces"
      description="Define branch workspaces, operating currencies, and workspace status for enterprise operations."
      entity="companyworkspace"
      permission="companyworkspace.read"
      createLabel="Add Workspace"
      resourceDefs={[
        { key: 'branches', entity: 'branch' },
        { key: 'currencies', entity: 'currency', optionLabel: 'code' },
      ]}
      formFields={[
        { name: 'name', label: 'Workspace Name', required: true },
        { name: 'code', label: 'Code' },
        {
          name: 'branch',
          label: 'Branch',
          type: 'select',
          resourceKey: 'branches',
        },
        { name: 'timezone', label: 'Timezone', initialValue: DEFAULT_TIMEZONE },
        {
          name: 'defaultCurrency',
          label: 'Default Currency',
          type: 'select',
          resourceKey: 'currencies',
          optionLabel: 'code',
          optionValue: 'code',
          initialValue: DEFAULT_CURRENCY_CODE,
          options: CURRENCY_OPTIONS,
        },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          initialValue: 'active',
          options: [
            { value: 'draft', label: 'Draft' },
            { value: 'active', label: 'Active' },
            { value: 'archived', label: 'Archived' },
          ],
        },
      ]}
      columns={[
        { title: 'Workspace', dataIndex: 'name' },
        { title: 'Code', dataIndex: 'code' },
        { title: 'Branch', render: (_, record) => record.branch?.name || '-' },
        { title: 'Timezone', dataIndex: 'timezone' },
        { title: 'Currency', dataIndex: 'defaultCurrency' },
        {
          title: 'Status',
          dataIndex: 'status',
          render: (value) => <Tag color={value === 'active' ? 'green' : value === 'draft' ? 'gold' : 'default'}>{value || 'active'}</Tag>,
        },
      ]}
    />
  );
}

export function CurrencyWorkbench() {
  return (
    <EntityListWorkbench
      title="Currencies"
      description="Manage supported currencies, exchange rates, and tenant defaults for finance workflows."
      entity="currency"
      permission="currency.read"
      createLabel="Add Currency"
      formFields={[
        { name: 'code', label: 'Code', required: true },
        { name: 'name', label: 'Name', required: true },
        { name: 'symbol', label: 'Symbol' },
        { name: 'exchangeRate', label: 'Exchange Rate', type: 'number', min: 0 },
        { name: 'isDefault', label: 'Default Currency', type: 'switch', initialValue: false },
      ]}
      columns={[
        { title: 'Code', dataIndex: 'code' },
        { title: 'Name', dataIndex: 'name' },
        { title: 'Symbol', dataIndex: 'symbol' },
        { title: 'Exchange Rate', dataIndex: 'exchangeRate' },
        { title: 'Default', dataIndex: 'isDefault', render: (value) => renderFlag(value, 'Default', 'Optional') },
      ]}
    />
  );
}

export function PaymentModeWorkbench() {
  return (
    <EntityListWorkbench
      title="Payment Modes"
      description="Configure the payment methods operators can use across invoice, payment, and reconciliation flows."
      entity="paymentmode"
      permission="paymentmode.read"
      createLabel="Add Payment Mode"
      formFields={[
        { name: 'name', label: 'Name', required: true },
        { name: 'description', label: 'Description', type: 'textarea', rows: 2 },
        { name: 'isDefault', label: 'Default Mode', type: 'switch', initialValue: false },
      ]}
      columns={[
        { title: 'Name', dataIndex: 'name' },
        { title: 'Description', dataIndex: 'description' },
        { title: 'Default', dataIndex: 'isDefault', render: (value) => renderFlag(value, 'Default', 'Optional') },
      ]}
    />
  );
}

export function TaxWorkbench() {
  return (
    <EntityListWorkbench
      title="Taxes"
      description="Maintain tax rates and finance defaults used by invoices, offers, and quotes."
      entity="taxes"
      permission="taxes.read"
      createLabel="Add Tax"
      formFields={[
        { name: 'taxName', label: 'Tax Name', required: true },
        { name: 'taxValue', label: 'Rate (%)', type: 'number', min: 0 },
        { name: 'isDefault', label: 'Default Tax', type: 'switch', initialValue: false },
      ]}
      columns={[
        { title: 'Tax', dataIndex: 'taxName' },
        { title: 'Rate', dataIndex: 'taxValue', render: (value) => `${value || 0}%` },
        { title: 'Default', dataIndex: 'isDefault', render: (value) => renderFlag(value, 'Default', 'Optional') },
      ]}
    />
  );
}

export function PdfSettingsWorkbench() {
  return (
    <EntityListWorkbench
      title="PDF Settings"
      description="Control document headers, footers, brand accents, and terms for invoice, quote, and offer PDFs."
      entity="pdfsetting"
      permission="pdfsetting.read"
      createLabel="Add PDF Setting"
      formFields={[
        {
          name: 'moduleKey',
          label: 'Document Type',
          type: 'select',
          initialValue: 'invoice',
          options: [
            { value: 'invoice', label: 'Invoice' },
            { value: 'quote', label: 'Quote' },
            { value: 'offer', label: 'Offer' },
          ],
        },
        { name: 'accentColor', label: 'Accent Color', initialValue: '#1677ff' },
        { name: 'headerText', label: 'Header Text', type: 'textarea', rows: 2 },
        { name: 'footerText', label: 'Footer Text', type: 'textarea', rows: 2 },
        { name: 'termsAndConditions', label: 'Terms & Conditions', type: 'textarea', rows: 4 },
        { name: 'enabled', label: 'Enabled', type: 'switch', initialValue: true },
      ]}
      columns={[
        { title: 'Document', dataIndex: 'moduleKey' },
        { title: 'Accent', dataIndex: 'accentColor' },
        { title: 'Header', dataIndex: 'headerText', render: (value) => value || '-' },
        { title: 'Footer', dataIndex: 'footerText', render: (value) => value || '-' },
        { title: 'Enabled', dataIndex: 'enabled', render: (value) => renderFlag(value, 'Enabled', 'Disabled') },
      ]}
    />
  );
}

const formatDate = (value) => {
  if (!value) return '-';
  const date = new Date(value);
  return Number.isNaN(date.getTime()) ? value : date.toLocaleString();
};

export function DocumentsWorkbench() {
  return (
    <EntityListWorkbench
      title="Documents"
      description="Track operational documents, references, and related records without dropping into raw JSON."
      entity="document"
      permission="document.read"
      createLabel="Add Document"
      formFields={[
        { name: 'title', label: 'Title', required: true },
        { name: 'type', label: 'Type' },
        { name: 'url', label: 'Document URL' },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          initialValue: 'active',
          options: [
            { value: 'draft', label: 'Draft' },
            { value: 'active', label: 'Active' },
            { value: 'archived', label: 'Archived' },
          ],
        },
      ]}
      columns={[
        { title: 'Title', dataIndex: 'title' },
        { title: 'Type', dataIndex: 'type', render: (value) => value || '-' },
        {
          title: 'URL',
          dataIndex: 'url',
          render: (value) => (value ? <a href={value} target="_blank" rel="noreferrer">{value}</a> : '-'),
        },
        {
          title: 'Status',
          dataIndex: 'status',
          render: (value) => <Tag color={value === 'active' ? 'green' : value === 'draft' ? 'gold' : 'default'}>{value || 'active'}</Tag>,
        },
      ]}
    />
  );
}

export function ContractsWorkbench() {
  return (
    <EntityListWorkbench
      title="Contracts"
      description="Manage counterparties, timelines, value, and lifecycle status for enterprise agreements."
      entity="contract"
      permission="contract.read"
      createLabel="Add Contract"
      formFields={[
        { name: 'title', label: 'Title', required: true },
        { name: 'counterparty', label: 'Counterparty' },
        { name: 'startDate', label: 'Start Date', type: 'datetime' },
        { name: 'endDate', label: 'End Date', type: 'datetime' },
        { name: 'amount', label: 'Contract Value', type: 'number', min: 0 },
        { name: 'currency', label: 'Currency', type: 'select', initialValue: DEFAULT_CURRENCY_CODE, options: CURRENCY_OPTIONS },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          initialValue: 'draft',
          options: [
            { value: 'draft', label: 'Draft' },
            { value: 'active', label: 'Active' },
            { value: 'expired', label: 'Expired' },
            { value: 'terminated', label: 'Terminated' },
          ],
        },
        { name: 'notes', label: 'Notes', type: 'textarea', rows: 3 },
      ]}
      buildPayload={(values) => ({
        title: values.title,
        counterparty: values.counterparty,
        startDate: values.startDate?.toISOString?.(),
        endDate: values.endDate?.toISOString?.(),
        value:
          values.amount || values.currency
            ? {
                amount: values.amount || 0,
                currency: values.currency || DEFAULT_CURRENCY_CODE,
              }
            : undefined,
        status: values.status,
        notes: values.notes,
      })}
      columns={[
        { title: 'Title', dataIndex: 'title' },
        { title: 'Counterparty', dataIndex: 'counterparty', render: (value) => value || '-' },
        { title: 'Start', dataIndex: 'startDate', render: formatDate },
        { title: 'End', dataIndex: 'endDate', render: formatDate },
        {
          title: 'Value',
          render: (_, record) =>
            formatCurrencyValue(record.value),
        },
        {
          title: 'Status',
          dataIndex: 'status',
          render: (value) => <Tag color={value === 'active' ? 'green' : value === 'draft' ? 'gold' : 'default'}>{value || 'draft'}</Tag>,
        },
      ]}
    />
  );
}

export function BookingWorkbench() {
  return (
    <EntityListWorkbench
      title="Bookings"
      description="Capture inbound booking requests and track their confirmation state alongside appointments."
      entity="calendarbooking"
      permission="calendarbooking.read"
      createLabel="Add Booking"
      resourceDefs={[{ key: 'appointments', entity: 'appointment' }]}
      formFields={[
        {
          name: 'appointment',
          label: 'Linked Appointment',
          type: 'select',
          resourceKey: 'appointments',
          optionLabel: 'title',
        },
        { name: 'inviteeName', label: 'Invitee Name', required: true },
        { name: 'inviteeEmail', label: 'Invitee Email' },
        { name: 'startsAt', label: 'Starts At', type: 'datetime' },
        { name: 'endsAt', label: 'Ends At', type: 'datetime' },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          initialValue: 'requested',
          options: [
            { value: 'requested', label: 'Requested' },
            { value: 'confirmed', label: 'Confirmed' },
            { value: 'cancelled', label: 'Cancelled' },
          ],
        },
      ]}
      buildPayload={(values) => ({
        ...values,
        startsAt: values.startsAt?.toISOString?.(),
        endsAt: values.endsAt?.toISOString?.(),
      })}
      columns={[
        { title: 'Invitee', dataIndex: 'inviteeName' },
        { title: 'Email', dataIndex: 'inviteeEmail' },
        { title: 'Appointment', render: (_, record) => record.appointment?.title || '-' },
        { title: 'Starts', dataIndex: 'startsAt', render: formatDate },
        {
          title: 'Status',
          dataIndex: 'status',
          render: (value) => <Tag color={value === 'confirmed' ? 'green' : value === 'requested' ? 'gold' : 'default'}>{value || 'requested'}</Tag>,
        },
      ]}
    />
  );
}

export function AppointmentWorkbench() {
  return (
    <EntityListWorkbench
      title="Appointments"
      description="Manage scheduled calls, demos, and meetings with timing, location, and booking details."
      entity="appointment"
      permission="appointment.read"
      createLabel="Add Appointment"
      resourceDefs={[{ key: 'contacts', entity: 'contact' }]}
      formFields={[
        { name: 'title', label: 'Title', required: true },
        {
          name: 'contact',
          label: 'Contact',
          type: 'select',
          resourceKey: 'contacts',
          optionLabel: 'firstname',
        },
        { name: 'startsAt', label: 'Starts At', type: 'datetime', required: true },
        { name: 'endsAt', label: 'Ends At', type: 'datetime' },
        { name: 'location', label: 'Location' },
        { name: 'bookingUrl', label: 'Booking URL' },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          initialValue: 'scheduled',
          options: [
            { value: 'scheduled', label: 'Scheduled' },
            { value: 'completed', label: 'Completed' },
            { value: 'cancelled', label: 'Cancelled' },
          ],
        },
      ]}
      buildPayload={(values) => ({
        ...values,
        startsAt: values.startsAt?.toISOString?.(),
        endsAt: values.endsAt?.toISOString?.(),
      })}
      columns={[
        { title: 'Title', dataIndex: 'title' },
        { title: 'Contact', render: (_, record) => record.contact?.firstname || record.contact?.name || '-' },
        { title: 'Starts', dataIndex: 'startsAt', render: formatDate },
        { title: 'Location', dataIndex: 'location', render: (value) => value || '-' },
        {
          title: 'Status',
          dataIndex: 'status',
          render: (value) => <Tag color={value === 'completed' ? 'green' : value === 'scheduled' ? 'blue' : 'default'}>{value || 'scheduled'}</Tag>,
        },
      ]}
    />
  );
}

export function ApiKeysWorkbench() {
  return (
    <EntityListWorkbench
      title="Developer API Keys"
      description="Manage tenant integration keys, exposed scopes, and operational status for partner access."
      entity="apikey"
      permission="apikey.read"
      createLabel="Add API Key"
      formFields={[
        { name: 'name', label: 'Key Name', required: true },
        { name: 'keyPreview', label: 'Key Preview' },
        {
          name: 'scopes',
          label: 'Scopes',
          type: 'tags',
        },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          initialValue: 'active',
          options: [
            { value: 'active', label: 'Active' },
            { value: 'disabled', label: 'Disabled' },
            { value: 'rotated', label: 'Rotated' },
          ],
        },
      ]}
      columns={[
        { title: 'Name', dataIndex: 'name' },
        { title: 'Preview', dataIndex: 'keyPreview', render: (value) => value || '-' },
        {
          title: 'Scopes',
          dataIndex: 'scopes',
          render: (value = []) => value.length ? value.join(', ') : '-',
        },
        { title: 'Last Used', dataIndex: 'lastUsedAt', render: formatDate },
        {
          title: 'Status',
          dataIndex: 'status',
          render: (value) => <Tag color={value === 'active' ? 'green' : value === 'disabled' ? 'red' : 'gold'}>{value || 'active'}</Tag>,
        },
      ]}
    />
  );
}

export function ProductCategoriesWorkbench() {
  return (
    <EntityListWorkbench
      title="Product Categories"
      description="Organize the product catalog into enterprise-ready categories with parent-child structure and visual labels."
      entity="productcategory"
      permission="productcategory.read"
      createLabel="Add Product Category"
      resourceDefs={[{ key: 'categories', entity: 'productcategory' }]}
      formFields={[
        { name: 'name', label: 'Name', required: true },
        { name: 'description', label: 'Description', type: 'textarea', rows: 2 },
        {
          name: 'parent',
          label: 'Parent Category',
          type: 'select',
          resourceKey: 'categories',
        },
        { name: 'color', label: 'Color', initialValue: '#1677ff' },
      ]}
      columns={[
        { title: 'Name', dataIndex: 'name' },
        { title: 'Parent', render: (_, record) => record.parent?.name || '-' },
        { title: 'Color', dataIndex: 'color', render: (value) => value || '-' },
        { title: 'Description', dataIndex: 'description', render: (value) => value || '-' },
      ]}
    />
  );
}

export function ProductsWorkbench() {
  return (
    <EntityListWorkbench
      title="Products"
      description="Manage catalog items, pricing, stock levels, suppliers, and reorder thresholds from one workspace."
      entity="product"
      permission="product.read"
      createLabel="Add Product"
      resourceDefs={[
        { key: 'categories', entity: 'productcategory' },
        { key: 'suppliers', entity: 'supplier' },
      ]}
      formFields={[
        { name: 'name', label: 'Name', required: true },
        { name: 'sku', label: 'SKU' },
        { name: 'description', label: 'Description', type: 'textarea', rows: 2 },
        {
          name: 'category',
          label: 'Category',
          type: 'select',
          resourceKey: 'categories',
        },
        {
          name: 'supplier',
          label: 'Supplier',
          type: 'select',
          resourceKey: 'suppliers',
        },
        { name: 'saleAmount', label: 'Sale Price', type: 'number', min: 0 },
        { name: 'saleCurrency', label: 'Sale Currency', type: 'select', initialValue: DEFAULT_CURRENCY_CODE, options: CURRENCY_OPTIONS },
        { name: 'purchaseAmount', label: 'Purchase Price', type: 'number', min: 0 },
        { name: 'purchaseCurrency', label: 'Purchase Currency', type: 'select', initialValue: DEFAULT_CURRENCY_CODE, options: CURRENCY_OPTIONS },
        { name: 'stockQuantity', label: 'Stock Quantity', type: 'number', min: 0, initialValue: 0 },
        { name: 'reorderLevel', label: 'Reorder Level', type: 'number', min: 0, initialValue: 0 },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          initialValue: 'active',
          options: [
            { value: 'draft', label: 'Draft' },
            { value: 'active', label: 'Active' },
            { value: 'archived', label: 'Archived' },
          ],
        },
      ]}
      buildPayload={(values) => ({
        name: values.name,
        sku: values.sku,
        description: values.description,
        category: values.category,
        supplier: values.supplier,
        salePrice:
          values.saleAmount || values.saleCurrency
            ? { amount: values.saleAmount || 0, currency: values.saleCurrency || DEFAULT_CURRENCY_CODE }
            : undefined,
        purchasePrice:
          values.purchaseAmount || values.purchaseCurrency
            ? { amount: values.purchaseAmount || 0, currency: values.purchaseCurrency || DEFAULT_CURRENCY_CODE }
            : undefined,
        stockQuantity: values.stockQuantity || 0,
        reorderLevel: values.reorderLevel || 0,
        status: values.status,
      })}
      columns={[
        { title: 'Name', dataIndex: 'name' },
        { title: 'SKU', dataIndex: 'sku', render: (value) => value || '-' },
        { title: 'Category', render: (_, record) => record.category?.name || '-' },
        { title: 'Supplier', render: (_, record) => record.supplier?.name || '-' },
        {
          title: 'Sale Price',
          render: (_, record) =>
            formatCurrencyValue(record.salePrice),
        },
        { title: 'Stock', dataIndex: 'stockQuantity', render: (value) => value ?? 0 },
        {
          title: 'Status',
          dataIndex: 'status',
          render: (value) => <Tag color={value === 'active' ? 'green' : value === 'draft' ? 'gold' : 'default'}>{value || 'active'}</Tag>,
        },
      ]}
    />
  );
}

export function ExpensesWorkbench() {
  return (
    <EntityListWorkbench
      title="Expenses"
      description="Track operational spend, ledger-linked accounts, and payment status without exposing raw finance payloads."
      entity="expense"
      permission="expense.read"
      createLabel="Add Expense"
      resourceDefs={[
        { key: 'vendors', entity: 'vendor' },
        { key: 'accounts', entity: 'account' },
        { key: 'taxProfiles', entity: 'taxprofile' },
      ]}
      formFields={[
        { name: 'title', label: 'Title', required: true },
        {
          name: 'vendor',
          label: 'Vendor',
          type: 'select',
          resourceKey: 'vendors',
        },
        {
          name: 'account',
          label: 'Account',
          type: 'select',
          resourceKey: 'accounts',
        },
        { name: 'amountValue', label: 'Amount', type: 'number', min: 0, required: true },
        { name: 'amountCurrency', label: 'Currency', type: 'select', initialValue: DEFAULT_CURRENCY_CODE, options: CURRENCY_OPTIONS },
        { name: 'date', label: 'Date', type: 'datetime' },
        {
          name: 'taxProfile',
          label: 'Tax Profile',
          type: 'select',
          resourceKey: 'taxProfiles',
        },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          initialValue: 'draft',
          options: [
            { value: 'draft', label: 'Draft' },
            { value: 'approved', label: 'Approved' },
            { value: 'paid', label: 'Paid' },
          ],
        },
      ]}
      buildPayload={(values) => ({
        title: values.title,
        vendor: values.vendor,
        account: values.account,
        amount: {
          amount: values.amountValue || 0,
          currency: values.amountCurrency || DEFAULT_CURRENCY_CODE,
        },
        date: values.date?.toISOString?.(),
        taxProfile: values.taxProfile,
        status: values.status,
      })}
      columns={[
        { title: 'Title', dataIndex: 'title' },
        { title: 'Vendor', render: (_, record) => record.vendor?.name || '-' },
        { title: 'Account', render: (_, record) => record.account?.name || '-' },
        {
          title: 'Amount',
          render: (_, record) => formatCurrencyValue(record.amount),
        },
        { title: 'Date', dataIndex: 'date', render: formatDate },
        {
          title: 'Status',
          dataIndex: 'status',
          render: (value) => <Tag color={value === 'paid' ? 'green' : value === 'approved' ? 'blue' : 'gold'}>{value || 'draft'}</Tag>,
        },
      ]}
    />
  );
}

export function ExpenseCategoriesWorkbench() {
  return (
    <EntityListWorkbench
      title="Expense Categories"
      description="Create structured spend categories for finance reporting and budget organization."
      entity="expensecategory"
      permission="expensecategory.read"
      createLabel="Add Expense Category"
      formFields={[
        { name: 'name', label: 'Name', required: true },
        { name: 'code', label: 'Code' },
        { name: 'description', label: 'Description', type: 'textarea', rows: 2 },
      ]}
      columns={[
        { title: 'Name', dataIndex: 'name' },
        { title: 'Code', dataIndex: 'code', render: (value) => value || '-' },
        { title: 'Description', dataIndex: 'description', render: (value) => value || '-' },
      ]}
    />
  );
}
