import React from 'react';

const StatCard = ({ title, value, color = 'var(--accent-primary)' }) => {
  return (
    <div className="glass-card" style={{ padding: '1.5rem', display: 'flex', flexDirection: 'column' }}>
      <h3 style={{ fontSize: '0.9rem', color: 'var(--text-secondary)', marginBottom: '0.5rem', fontWeight: '500' }}>
        {title}
      </h3>
      <p style={{ fontSize: '2.5rem', fontWeight: '700', color: color }}>
        {value}
      </p>
    </div>
  );
};

export default StatCard;
