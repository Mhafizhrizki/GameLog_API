import React, { useState } from 'react';

const AddToTrackerModal = ({ game, onClose, onSave }) => {
  const [status, setStatus] = useState('playing');
  const [rating, setRating] = useState(0);
  const [isSaving, setIsSaving] = useState(false);

  if (!game) return null;

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsSaving(true);
    await onSave({
      rawg_id: game.id || game.rawg_id, // RAWG search returns id, we save it as rawg_id
      title: game.name || game.title,
      status,
      personal_rating: parseInt(rating, 10)
    });
    setIsSaving(false);
  };

  return (
    <div style={{
      position: 'fixed', top: 0, left: 0, width: '100vw', height: '100vh',
      backgroundColor: 'rgba(15, 23, 42, 0.8)', backdropFilter: 'blur(4px)',
      display: 'flex', justifyContent: 'center', alignItems: 'center', zIndex: 100,
      padding: '1rem'
    }}>
      <div className="glass-card animate-fade-in" style={{
        width: '100%', maxWidth: '400px', padding: '2rem', position: 'relative'
      }}>
        <button 
          onClick={onClose}
          style={{
            position: 'absolute', top: '1rem', right: '1rem', background: 'none', border: 'none',
            color: 'var(--text-secondary)', fontSize: '1.5rem', cursor: 'pointer'
          }}
        >
          &times;
        </button>

        <h2 style={{ fontSize: '1.4rem', marginBottom: '1.5rem' }}>Add to Tracker</h2>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '1.5rem' }}>
          {game.name || game.title}
        </p>

        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
          <div>
            <label style={{ display: 'block', marginBottom: '0.5rem', fontSize: '0.9rem' }}>Status</label>
            <select 
              value={status} 
              onChange={(e) => setStatus(e.target.value)}
              className="input-field"
              style={{ appearance: 'none' }}
            >
              <option value="playing">Playing</option>
              <option value="wishlist">Wishlist</option>
              <option value="completed">Completed</option>
            </select>
          </div>

          <div>
            <label style={{ display: 'block', marginBottom: '0.5rem', fontSize: '0.9rem' }}>Personal Rating (0-5)</label>
            <input 
              type="number" 
              min="0" max="5" 
              value={rating} 
              onChange={(e) => setRating(e.target.value)}
              className="input-field"
            />
          </div>

          <div style={{ display: 'flex', gap: '1rem', marginTop: '1rem' }}>
            <button type="button" onClick={onClose} className="btn btn-secondary" style={{ flexGrow: 1 }}>
              Cancel
            </button>
            <button type="submit" disabled={isSaving} className="btn btn-primary" style={{ flexGrow: 1 }}>
              {isSaving ? 'Saving...' : 'Save'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default AddToTrackerModal;
