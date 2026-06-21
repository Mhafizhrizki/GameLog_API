import React, { useState } from 'react';

const GameCard = ({ game, onAdd }) => {
  const [isHovered, setIsHovered] = useState(false);

  return (
    <div 
      className="glass-card"
      style={{
        display: 'flex',
        flexDirection: 'column',
        overflow: 'hidden',
        height: '100%',
        transform: isHovered ? 'translateY(-4px)' : 'none',
        boxShadow: isHovered ? 'var(--shadow-glow)' : 'var(--shadow-md)',
      }}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
    >
      <div style={{ position: 'relative', paddingTop: '56.25%', backgroundColor: 'var(--bg-tertiary)' }}>
        {game.background_image ? (
          <img 
            src={game.background_image} 
            alt={game.name}
            style={{
              position: 'absolute',
              top: 0,
              left: 0,
              width: '100%',
              height: '100%',
              objectFit: 'cover'
            }}
          />
        ) : (
          <div style={{ position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <span style={{ color: 'var(--text-secondary)' }}>No Image</span>
          </div>
        )}
      </div>

      <div style={{ padding: '1.25rem', display: 'flex', flexDirection: 'column', flexGrow: 1 }}>
        <h3 style={{ fontSize: '1.1rem', marginBottom: '0.5rem', lineHeight: '1.3' }}>{game.name}</h3>
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', marginBottom: '1rem', marginTop: 'auto' }}>
          <span style={{ color: '#fbbf24' }}>★</span>
          <span style={{ fontSize: '0.9rem', color: 'var(--text-secondary)' }}>{game.rating || 0}</span>
          <span style={{ fontSize: '0.8rem', color: 'var(--text-secondary)', marginLeft: 'auto' }}>
            {game.released ? new Date(game.released).getFullYear() : 'N/A'}
          </span>
        </div>

        <button 
          onClick={() => onAdd(game)}
          className="btn btn-primary"
          style={{ width: '100%' }}
        >
          Add to Tracker
        </button>
      </div>
    </div>
  );
};

export default GameCard;
