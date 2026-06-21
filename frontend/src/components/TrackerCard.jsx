import React, { useState } from 'react';

const TrackerCard = ({ gameLog, onEdit, onDelete }) => {
  const [isHovered, setIsHovered] = useState(false);

  const getStatusColor = (status) => {
    switch(status) {
      case 'completed': return 'var(--success)';
      case 'playing': return 'var(--accent-secondary)';
      case 'wishlist': return 'var(--text-secondary)';
      default: return 'var(--text-secondary)';
    }
  };

  const renderStars = (rating) => {
    const stars = [];
    for (let i = 1; i <= 5; i++) {
      stars.push(
        <span key={i} style={{ color: i <= rating ? '#fbbf24' : 'var(--bg-tertiary)', fontSize: '1.2rem' }}>
          ★
        </span>
      );
    }
    return stars;
  };

  return (
    <div 
      className="glass-card"
      style={{
        display: 'flex',
        flexDirection: 'column',
        padding: '1.25rem',
        transform: isHovered ? 'translateY(-4px)' : 'none',
        boxShadow: isHovered ? 'var(--shadow-glow)' : 'var(--shadow-md)',
      }}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
    >
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '1rem' }}>
        <h3 style={{ fontSize: '1.1rem', lineHeight: '1.3', paddingRight: '1rem' }}>{gameLog.title}</h3>
        <span style={{
          backgroundColor: `${getStatusColor(gameLog.status)}20`,
          color: getStatusColor(gameLog.status),
          padding: '0.25rem 0.5rem',
          borderRadius: 'var(--radius-sm)',
          fontSize: '0.75rem',
          fontWeight: '600',
          textTransform: 'uppercase',
          letterSpacing: '0.05em'
        }}>
          {gameLog.status}
        </span>
      </div>

      <div style={{ marginBottom: '1.5rem', display: 'flex', gap: '0.2rem' }}>
        {renderStars(gameLog.personal_rating)}
      </div>

      <div style={{ display: 'flex', gap: '0.5rem', marginTop: 'auto' }}>
        <button 
          onClick={() => onEdit(gameLog)}
          className="btn btn-secondary"
          style={{ flexGrow: 1, padding: '0.5rem' }}
        >
          Edit
        </button>
        <button 
          onClick={() => onDelete(gameLog.id)}
          className="btn btn-danger"
          style={{ flexGrow: 1, padding: '0.5rem' }}
        >
          Delete
        </button>
      </div>
    </div>
  );
};

export default TrackerCard;
