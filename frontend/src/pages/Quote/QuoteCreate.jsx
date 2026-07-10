import useLanguage from '@/locale/useLanguage';
import CreateQuoteModule from '@/modules/QuoteModule/CreateQuoteModule';

export default function QuoteCreate() {
  const translate = useLanguage();
  return (
    <CreateQuoteModule
      config={{
        entity: 'quote',
        PANEL_TITLE: translate('quote'),
        ENTITY_NAME: translate('quote'),
      }}
    />
  );
}
