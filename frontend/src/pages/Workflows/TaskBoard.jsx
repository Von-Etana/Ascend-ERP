import { useEffect, useState } from 'react';
import { Button, Card, Form, Input, Select, Space, Table, message } from 'antd';
import { ReloadOutlined } from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { request } from '@/request';
import { WorkflowPage } from './shared';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessEntityAction } from '@/utils/permissions';

export default function TaskBoard() {
  const [tasks, setTasks] = useState([]);
  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);
  const currentAdmin = useSelector(selectCurrentAdmin);
  const canCreateTasks = canAccessEntityAction(currentAdmin, 'task', 'create');

  const load = async () => {
    setLoading(true);
    const response = await request.list({ entity: 'task', options: { page: 1, items: 100 } });
    setTasks(response?.result || []);
    setLoading(false);
  };

  useEffect(() => {
    load();
  }, []);

  const save = async () => {
    const values = await form.validateFields();
    await request.create({
      entity: 'task',
      jsonData: values,
    });
    message.success('Task created');
    form.resetFields();
    load();
  };

  return (
    <WorkflowPage
      title="Task Board"
      description="Create operational follow-ups and reminders tied to any module record."
      permission="tasks.task.read"
      extra={
        <Space>
          <Button icon={<ReloadOutlined />} onClick={load}>Refresh</Button>
          <Button type="primary" disabled={!canCreateTasks} onClick={save}>Create Task</Button>
        </Space>
      }
    >
      <Form form={form} layout="vertical">
        <Form.Item name="title" label="Title" rules={[{ required: true }]}>
          <Input />
        </Form.Item>
        <Form.Item name="description" label="Description">
          <Input.TextArea rows={3} />
        </Form.Item>
        <Form.Item name="priority" label="Priority" initialValue="normal">
          <Select
            options={[
              { value: 'low', label: 'Low' },
              { value: 'normal', label: 'Normal' },
              { value: 'high', label: 'High' },
              { value: 'urgent', label: 'Urgent' },
            ]}
          />
        </Form.Item>
      </Form>
      <Table
        rowKey="_id"
        loading={loading}
        dataSource={tasks}
        pagination={false}
        columns={[
          { title: 'Title', dataIndex: 'title' },
          { title: 'Priority', dataIndex: 'priority' },
          { title: 'Status', dataIndex: 'status' },
        ]}
      />
    </WorkflowPage>
  );
}
