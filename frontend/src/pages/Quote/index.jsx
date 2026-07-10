import dayjs from 'dayjs';
import useLanguage from '@/locale/useLanguage';
import QuoteDataTableModule from '@/modules/QuoteModule/QuoteDataTableModule';
import { useMoney, useDate } from '@/settings';

export default function Quote() {
  const translate = useLanguage();
  const { dateFormat } = useDate();
  const { moneyFormatter } = useMoney();
  const entity = 'quote';

  const config = {
    entity,
    PANEL_TITLE: translate('quote'),
    DATATABLE_TITLE: translate('quote_list'),
    ADD_NEW_ENTITY: translate('add_new_quote'),
    ENTITY_NAME: translate('quote'),
    dataTableColumns: [
      { title: translate('Number'), dataIndex: 'number' },
      { title: translate('Client'), dataIndex: ['client', 'name'] },
      {
        title: translate('Date'),
        dataIndex: 'date',
        render: (date) => date && dayjs(date).format(dateFormat),
      },
      {
        title: translate('Total'),
        dataIndex: 'total',
        render: (total, record) =>
          moneyFormatter({ amount: total || record.value?.amount || 0, currency_code: record.currency || record.value?.currency }),
      },
      { title: translate('Status'), dataIndex: 'status' },
    ],
    searchConfig: { entity: 'client', displayLabels: ['name'], searchFields: 'name' },
    deleteModalLabels: ['number'],
  };

  return <QuoteDataTableModule config={config} />;
}
