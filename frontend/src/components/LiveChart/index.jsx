import { useState, useMemo } from 'react';

export default function LiveChart({
  type = 'line', // 'line' | 'bar' | 'donut'
  data = [], // [{ label: 'Jan', value: 10, value2: 15 }]
  series = [], // [{ key: 'value', label: 'Series A', color: '#1677ff' }]
  height = 260,
  currencySymbol = '',
}) {
  const [hoverIndex, setHoverIndex] = useState(null);

  // If no data, display empty state
  if (!data || data.length === 0) {
    return (
      <div style={{ height, display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#94a3b8' }}>
        No data available for chart.
      </div>
    );
  }

  // Common dimensions
  const padding = { top: 20, right: 20, bottom: 40, left: 60 };
  const chartHeight = height - padding.top - padding.bottom;

  // Process data for Donut
  if (type === 'donut') {
    const total = data.reduce((sum, item) => sum + (item.value || 0), 0);
    let cumulativePercent = 0;

    const donutSlices = data.map((item, idx) => {
      const percentage = total > 0 ? (item.value / total) * 100 : 0;
      const strokePercent = percentage;
      const strokeOffset = 100 - cumulativePercent;
      cumulativePercent += strokePercent;

      return {
        ...item,
        percentage,
        strokePercent,
        strokeOffset,
      };
    });

    return (
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-around', flexWrap: 'wrap', minHeight: height }}>
        {/* SVG Circle for Donut */}
        <div style={{ position: 'relative', width: height - 60, height: height - 60 }}>
          <svg viewBox="0 0 42 42" width="100%" height="100%">
            <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#f1f5f9" strokeWidth="3" />
            {donutSlices.map((slice, idx) => (
              <circle
                key={idx}
                cx="21"
                cy="21"
                r="15.915"
                fill="transparent"
                stroke={slice.color || ['#1677ff', '#52c41a', '#faad14', '#ff4d4f', '#722ed1'][idx % 5]}
                strokeWidth={hoverIndex === idx ? '4.5' : '3.5'}
                strokeDasharray={`${slice.strokePercent} ${100 - slice.strokePercent}`}
                strokeDashoffset={slice.strokeOffset}
                style={{
                  transform: 'rotate(-90deg)',
                  transformOrigin: '50% 50%',
                  transition: 'stroke-width 0.2s ease, stroke-dashoffset 0.5s ease',
                  cursor: 'pointer',
                }}
                onMouseEnter={() => setHoverIndex(idx)}
                onMouseLeave={() => setHoverIndex(null)}
              />
            ))}
          </svg>
          <div
            style={{
              position: 'absolute',
              top: '50%',
              left: '50%',
              transform: 'translate(-50%, -50%)',
              textAlign: 'center',
              pointerEvents: 'none',
            }}
          >
            {hoverIndex !== null ? (
              <>
                <div style={{ fontSize: 13, color: '#64748b', textTransform: 'uppercase' }}>
                  {donutSlices[hoverIndex].label}
                </div>
                <div style={{ fontSize: 20, fontWeight: 'bold', color: '#1e293b' }}>
                  {currencySymbol}
                  {donutSlices[hoverIndex].value.toLocaleString()}
                </div>
                <div style={{ fontSize: 12, color: '#64748b' }}>
                  {donutSlices[hoverIndex].percentage.toFixed(1)}%
                </div>
              </>
            ) : (
              <>
                <div style={{ fontSize: 13, color: '#64748b' }}>Total</div>
                <div style={{ fontSize: 22, fontWeight: 'bold', color: '#1e293b' }}>
                  {currencySymbol}
                  {total.toLocaleString()}
                </div>
              </>
            )}
          </div>
        </div>

        {/* Legend */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: 8, minWidth: 150 }}>
          {donutSlices.map((slice, idx) => (
            <div
              key={idx}
              style={{
                display: 'flex',
                alignItems: 'center',
                gap: 8,
                padding: '4px 8px',
                borderRadius: 4,
                background: hoverIndex === idx ? '#f8fafc' : 'transparent',
                cursor: 'pointer',
                transition: 'background 0.2s',
              }}
              onMouseEnter={() => setHoverIndex(idx)}
              onMouseLeave={() => setHoverIndex(null)}
            >
              <span
                style={{
                  width: 10,
                  height: 10,
                  borderRadius: '50%',
                  background: slice.color || ['#1677ff', '#52c41a', '#faad14', '#ff4d4f', '#722ed1'][idx % 5],
                  display: 'inline-block',
                }}
              />
              <span style={{ fontSize: 13, fontWeight: hoverIndex === idx ? 'bold' : 'normal', color: '#334155' }}>
                {slice.label}: {currencySymbol}
                {slice.value.toLocaleString()} ({slice.percentage.toFixed(1)}%)
              </span>
            </div>
          ))}
        </div>
      </div>
    );
  }

  // Processing Line and Bar Charts
  // Calculate max values for scale
  const keys = series.map((s) => s.key);
  const maxVal = Math.max(
    ...data.flatMap((item) => keys.map((k) => item[k] || 0)),
    100 // fallback base scale
  );

  // Pad max val slightly
  const yMax = Math.ceil(maxVal * 1.15);

  // X positions and spacing
  const xStep = data.length > 1 ? 80 : 0;
  const chartWidth = Math.max((data.length - 1) * xStep, 400);

  // Helper coordinate mapper
  const getCoordinates = (idx, value) => {
    const x = padding.left + idx * xStep;
    const y = padding.top + chartHeight - (value / yMax) * chartHeight;
    return { x, y };
  };

  // Y-axis grid ticks (4 divisions)
  const ticks = Array.from({ length: 5 }, (_, i) => Math.round((yMax / 4) * i));

  return (
    <div style={{ width: '100%', overflowX: 'auto', position: 'relative' }}>
      <svg width={chartWidth + padding.left + padding.right} height={height}>
        {/* Grid lines & Y Axis labels */}
        {ticks.map((tick, i) => {
          const y = padding.top + chartHeight - (tick / yMax) * chartHeight;
          return (
            <g key={i}>
              <line x1={padding.left} y1={y} x2={padding.left + chartWidth} y2={y} stroke="#f1f5f9" strokeWidth="1" />
              <text
                x={padding.left - 10}
                y={y + 4}
                textAnchor="end"
                style={{ fontSize: 11, fill: '#64748b', fontFamily: 'sans-serif' }}
              >
                {currencySymbol}
                {tick >= 1000 ? `${(tick / 1000).toFixed(0)}k` : tick}
              </text>
            </g>
          );
        })}

        {/* X Axis labels */}
        {data.map((item, idx) => {
          const x = padding.left + idx * xStep;
          const y = padding.top + chartHeight + 20;
          return (
            <text
              key={idx}
              x={x}
              y={y}
              textAnchor="middle"
              style={{ fontSize: 11, fill: '#64748b', fontFamily: 'sans-serif' }}
            >
              {item.label}
            </text>
          );
        })}

        {/* Bar type rendering */}
        {type === 'bar' &&
          series.map((s, sIdx) => {
            const barWidth = Math.max(14, 40 / series.length);
            const offset = (sIdx - (series.length - 1) / 2) * barWidth;

            return data.map((item, idx) => {
              const val = item[s.key] || 0;
              const { x, y } = getCoordinates(idx, val);
              const barHeight = padding.top + chartHeight - y;

              return (
                <g key={`${sIdx}-${idx}`}>
                  <rect
                    x={x + offset - barWidth / 2}
                    y={y}
                    width={barWidth - 2}
                    height={Math.max(barHeight, 0)}
                    fill={s.color || '#1677ff'}
                    style={{
                      transition: 'height 0.4s ease, y 0.4s ease',
                      cursor: 'pointer',
                      opacity: hoverIndex === idx ? 0.95 : 0.85,
                    }}
                    onMouseEnter={() => setHoverIndex(idx)}
                    onMouseLeave={() => setHoverIndex(null)}
                  />
                </g>
              );
            });
          })}

        {/* Line type rendering */}
        {type === 'line' &&
          series.map((s, sIdx) => {
            // Build SVG path
            const points = data.map((item, idx) => getCoordinates(idx, item[s.key] || 0));
            const pathData = points
              .map((p, idx) => `${idx === 0 ? 'M' : 'L'} ${p.x} ${p.y}`)
              .join(' ');

            return (
              <g key={sIdx}>
                {/* Line Path */}
                <path
                  d={pathData}
                  fill="none"
                  stroke={s.color || '#1677ff'}
                  strokeWidth="2.5"
                  style={{
                    transition: 'all 0.3s',
                  }}
                />

                {/* Point Circles */}
                {points.map((p, idx) => (
                  <circle
                    key={idx}
                    cx={p.x}
                    cy={p.y}
                    r={hoverIndex === idx ? 6 : 4}
                    fill="#fff"
                    stroke={s.color || '#1677ff'}
                    strokeWidth="2"
                    style={{
                      transition: 'r 0.2s ease',
                      cursor: 'pointer',
                    }}
                    onMouseEnter={() => setHoverIndex(idx)}
                    onMouseLeave={() => setHoverIndex(null)}
                  />
                ))}
              </g>
            );
          })}

        {/* Hover vertical guidelining */}
        {hoverIndex !== null && (
          <line
            x1={padding.left + hoverIndex * xStep}
            y1={padding.top}
            x2={padding.left + hoverIndex * xStep}
            y2={padding.top + chartHeight}
            stroke="#94a3b8"
            strokeDasharray="4 4"
            strokeWidth="1"
            style={{ pointerEvents: 'none' }}
          />
        )}
      </svg>

      {/* Tooltip Overlay */}
      {hoverIndex !== null && (
        <div
          style={{
            position: 'absolute',
            top: padding.top,
            left: padding.left + hoverIndex * xStep + 16,
            background: 'rgba(30, 41, 59, 0.95)',
            color: '#fff',
            padding: '8px 12px',
            borderRadius: 6,
            boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
            zIndex: 10,
            pointerEvents: 'none',
            fontSize: 12,
            fontFamily: 'sans-serif',
            minWidth: 120,
          }}
        >
          <div style={{ fontWeight: 'bold', marginBottom: 4, borderBottom: '1px solid #475569', paddingBottom: 2 }}>
            {data[hoverIndex].label}
          </div>
          {series.map((s, idx) => (
            <div key={idx} style={{ display: 'flex', justifyContent: 'space-between', gap: 12, marginTop: 2 }}>
              <span style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                <span style={{ width: 8, height: 8, borderRadius: '50%', background: s.color }} />
                {s.label}
              </span>
              <span style={{ fontWeight: 'bold' }}>
                {currencySymbol}
                {(data[hoverIndex][s.key] || 0).toLocaleString()}
              </span>
            </div>
          ))}
        </div>
      )}

      {/* Legends */}
      <div style={{ display: 'flex', justifyContent: 'center', gap: 16, marginTop: 8 }}>
        {series.map((s, idx) => (
          <div key={idx} style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
            <span style={{ width: 12, height: 4, borderRadius: 2, background: s.color, display: 'inline-block' }} />
            <span style={{ fontSize: 12, color: '#475569' }}>{s.label}</span>
          </div>
        ))}
      </div>
    </div>
  );
}
