import useLanguage from '@/locale/useLanguage';
import ReadQuoteModule from '@/modules/QuoteModule/ReadQuoteModule';

export default function QuoteRead() {
  const translate = useLanguage();
  return (
    <ReadQuoteModule
      config={{
        entity: 'quote',
        PANEL_TITLE: translate('quote'),
        ENTITY_NAME: translate('quote'),
      }}
    />
  );
}
