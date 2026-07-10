import { Alert } from 'antd';
import { useSelector } from 'react-redux';
import { selectCurrentAdmin } from '@/redux/auth/selectors';
import { canAccessPermission } from '@/utils/permissions';

export default function PermissionGuard({ permission, children, fallback = null }) {
  const currentAdmin = useSelector(selectCurrentAdmin);

  if (canAccessPermission(currentAdmin, permission)) {
    return children;
  }

  return (
    fallback || (
      <Alert
        type="warning"
        showIcon
        message="You do not have permission to access this workflow."
      />
    )
  );
}
