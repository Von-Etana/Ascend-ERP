import {
  ResponsiveContainer,
  LineChart,
  Line,
  BarChart,
  Bar,
  PieChart,
  Pie,
  Cell,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
} from 'recharts';

export default function LiveChart({
  type = 'line', // 'line' | 'bar' | 'donut'
  data = [], // [{ label: 'Jan', value: 10 }]
  series = [], // [{ key: 'value', label: 'Series A', color: '#1677ff' }]
  height = 260,
  currencySymbol = '',
}) {
  if (!data || data.length === 0) {
    return (
      <div style={{ height, display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#94a3b8' }}>
        No data available for chart.
      </div>
    );
  }

  // Format tick values for YAxis
  const formatYAxis = (tick) => {
    if (tick >= 1000000) {
      return `${currencySymbol}${(tick / 1000000).toFixed(1)}M`;
    }
    if (tick >= 1000) {
      return `${currencySymbol}${(tick / 1000).toFixed(0)}k`;
    }
    return `${currencySymbol}${tick}`;
  };

  // Custom Tooltip formatter
  const formatTooltip = (value, name, props) => {
    return [`${currencySymbol}${Number(value).toLocaleString()}`, name];
  };

  // 1. Donut Chart using Recharts Pie
  if (type === 'donut') {
    const defaultColors = ['#1677ff', '#52c41a', '#faad14', '#ff4d4f', '#722ed1'];
    const total = data.reduce((sum, item) => sum + (item.value || 0), 0);

    return (
      <div style={{ width: '100%', height, position: 'relative' }}>
        <ResponsiveContainer width="100%" height="100%">
          <PieChart>
            <Pie
              data={data}
              cx="50%"
              cy="50%"
              innerRadius={height * 0.28}
              outerRadius={height * 0.38}
              paddingAngle={4}
              dataKey="value"
              nameKey="label"
            >
              {data.map((entry, index) => (
                <Cell key={`cell-${index}`} fill={entry.color || defaultColors[index % defaultColors.length]} />
              ))}
            </Pie>
            <Tooltip formatter={formatTooltip} contentStyle={{ background: '#1e293b', border: 'none', borderRadius: '6px', color: '#fff' }} />
            <Legend verticalAlign="bottom" height={36} />
          </PieChart>
        </ResponsiveContainer>
        {/* Total Label in Center */}
        <div
          style={{
            position: 'absolute',
            top: '44%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            textAlign: 'center',
            pointerEvents: 'none',
          }}
        >
          <div style={{ fontSize: '12px', color: '#64748b', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Total</div>
          <div style={{ fontSize: '18px', fontWeight: 'bold', color: '#1e293b', marginTop: 2 }}>
            {currencySymbol}
            {total.toLocaleString()}
          </div>
        </div>
      </div>
    );
  }

  // 2. Bar Chart using Recharts BarChart
  if (type === 'bar') {
    return (
      <div style={{ width: '100%', height }}>
        <ResponsiveContainer width="100%" height="100%">
          <BarChart data={data} margin={{ top: 10, right: 10, left: 10, bottom: 5 }}>
            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
            <XAxis dataKey="label" stroke="#94a3b8" fontSize={11} tickLine={false} />
            <YAxis stroke="#94a3b8" fontSize={11} tickLine={false} tickFormatter={formatYAxis} />
            <Tooltip formatter={formatTooltip} contentStyle={{ background: '#1e293b', border: 'none', borderRadius: '6px', color: '#fff' }} />
            <Legend verticalAlign="top" height={36} />
            {series.map((s, idx) => (
              <Bar
                key={s.key}
                dataKey={s.key}
                name={s.label}
                fill={s.color || '#1677ff'}
                radius={[4, 4, 0, 0]}
                maxBarSize={45}
              />
            ))}
          </BarChart>
        </ResponsiveContainer>
      </div>
    );
  }

  // 3. Line Chart using Recharts LineChart
  return (
    <div style={{ width: '100%', height }}>
      <ResponsiveContainer width="100%" height="100%">
        <LineChart data={data} margin={{ top: 10, right: 10, left: 10, bottom: 5 }}>
          <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
          <XAxis dataKey="label" stroke="#94a3b8" fontSize={11} tickLine={false} />
          <YAxis stroke="#94a3b8" fontSize={11} tickLine={false} tickFormatter={formatYAxis} />
          <Tooltip formatter={formatTooltip} contentStyle={{ background: '#1e293b', border: 'none', borderRadius: '6px', color: '#fff' }} />
          <Legend verticalAlign="top" height={36} />
          {series.map((s, idx) => (
            <Line
              key={s.key}
              type="monotone"
              dataKey={s.key}
              name={s.label}
              stroke={s.color || '#1677ff'}
              strokeWidth={2.5}
              activeDot={{ r: 6 }}
              dot={{ r: 4, strokeWidth: 2 }}
            />
          ))}
        </LineChart>
      </ResponsiveContainer>
    </div>
  );
}
