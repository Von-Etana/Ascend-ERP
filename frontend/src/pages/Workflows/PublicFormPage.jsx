import { useEffect, useState } from 'react';
import axios from 'axios';
import { Alert, Button, Card, Form, Input, Result, Skeleton, Space, Typography, message } from 'antd';
import { useParams } from 'react-router-dom';
import { BASE_URL } from '@/config/serverApiConfig';
import { buildSubmissionPayload } from './publicForms';

const { Title, Text } = Typography;

const FIELD_LABELS = {
  firstName: 'First Name',
  lastName: 'Last Name',
  name: 'Name',
  email: 'Email',
  phone: 'Phone',
  whatsapp: 'WhatsApp',
  message: 'Message',
  title: 'Title',
};

export default function PublicFormPage() {
  const { slug } = useParams();
  const [formConfig, setFormConfig] = useState(null);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState('');
  const [form] = Form.useForm();

  useEffect(() => {
    const load = async () => {
      setLoading(true);
      setError('');
      try {
        const response = await axios.get(`${BASE_URL}public/forms/${slug}`);
        setFormConfig(response.data?.result || null);
      } catch (loadError) {
        setError(loadError.response?.data?.message || 'Unable to load form.');
      }
      setLoading(false);
    };

    load();
  }, [slug]);

  const submit = async () => {
    const values = await form.validateFields();
    setSubmitting(true);
    setError('');
    try {
      await axios.post(`${BASE_URL}public/forms/${slug}/submit`, buildSubmissionPayload(formConfig?.fields || [], values));
      setSuccess(true);
      message.success('Form submitted');
    } catch (submitError) {
      setError(submitError.response?.data?.message || 'Unable to submit form.');
    }
    setSubmitting(false);
  };

  if (loading) {
    return (
      <div style={{ maxWidth: 720, margin: '48px auto', padding: '0 16px' }}>
        <Card>
          <Skeleton active paragraph={{ rows: 6 }} />
        </Card>
      </div>
    );
  }

  if (success) {
    return (
      <div style={{ maxWidth: 720, margin: '48px auto', padding: '0 16px' }}>
        <Result
          status="success"
          title={formConfig?.successMessage || 'Thanks, your submission has been received.'}
        />
      </div>
    );
  }

  return (
    <div style={{ maxWidth: 720, margin: '48px auto', padding: '0 16px' }}>
      <Card>
        <Space direction="vertical" size={20} style={{ width: '100%' }}>
          <div>
            <Title level={2} style={{ marginBottom: 4 }}>
              {formConfig?.name || 'Public Form'}
            </Title>
            <Text type="secondary">{formConfig?.description || 'Submit your details below.'}</Text>
          </div>

          {error && <Alert type="warning" showIcon message={error} />}

          <Form form={form} layout="vertical" onFinish={submit}>
            {(formConfig?.fields || []).map((field) => (
              <Form.Item
                key={field}
                name={field}
                label={FIELD_LABELS[field] || field}
                rules={[
                  { required: ['firstName', 'name', 'email'].includes(field), message: 'This field is required.' },
                  ...(field === 'email' ? [{ type: 'email', message: 'Enter a valid email address.' }] : []),
                ]}
              >
                {field === 'message' ? <Input.TextArea rows={4} /> : <Input />}
              </Form.Item>
            ))}

            <Button type="primary" htmlType="submit" loading={submitting}>
              Submit
            </Button>
          </Form>
        </Space>
      </Card>
    </div>
  );
}
