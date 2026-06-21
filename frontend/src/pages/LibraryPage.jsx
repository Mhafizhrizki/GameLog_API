import React, { useState, useEffect, useCallback } from 'react';
import { gameLogApi } from '../api/gameLogApi';
import TrackerCard from '../components/TrackerCard';
import EditModal from '../components/Modals/EditModal';
import LoadingSpinner from '../components/LoadingSpinner';

const LibraryPage = () => {
  const [logs, setLogs] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState('');
  
  const [selectedLog, setSelectedLog] = useState(null);
  const [showEditModal, setShowEditModal] = useState(false);

  const fetchLogs = useCallback(async () => {
    setLoading(true);
    try {
      const response = await gameLogApi.getGameLogs(filter);
      if (response.status === 'success') {
        setLogs(response.data);
      }
    } catch (error) {
      console.error('Failed to fetch library', error);
    } finally {
      setLoading(false);
    }
  }, [filter]);

  useEffect(() => {
    fetchLogs();
  }, [fetchLogs]);

  const handleEditClick = (log) => {
    setSelectedLog(log);
    setShowEditModal(true);
  };

  const handleSaveEdit = async (id, data) => {
    try {
      const result = await gameLogApi.updateGameLog(id, data);
      if (result.status === 'success') {
        fetchLogs(); // Refresh list
      } else {
        alert(result.message || 'Failed to update game log');
      }
    } catch (error) {
      alert('Failed to update game log');
    } finally {
      setShowEditModal(false);
      setSelectedLog(null);
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to remove this game from your tracker?')) {
      try {
        const result = await gameLogApi.deleteGameLog(id);
        if (result.status === 'success') {
          // Optimistic update
          setLogs(logs.filter(log => log.id !== id));
        }
      } catch (error) {
        alert('Failed to delete game log');
      }
    }
  };

  const tabs = [
    { value: '', label: 'All Games' },
    { value: 'playing', label: 'Playing' },
    { value: 'completed', label: 'Completed' },
    { value: 'wishlist', label: 'Wishlist' }
  ];

  return (
    <div className="container animate-fade-in" style={{ padding: '2rem 1.5rem' }}>
      
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem', flexWrap: 'wrap', gap: '1rem' }}>
        <div>
          <h1 style={{ fontSize: '2.5rem', marginBottom: '0.5rem' }}>My Library</h1>
          <p style={{ color: 'var(--text-secondary)' }}>Manage your personal game collection.</p>
        </div>

        <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
          {tabs.map(tab => (
            <button
              key={tab.value}
              onClick={() => setFilter(tab.value)}
              className="btn"
              style={{
                backgroundColor: filter === tab.value ? 'var(--accent-primary)' : 'transparent',
                color: filter === tab.value ? 'white' : 'var(--text-secondary)',
                border: filter === tab.value ? '1px solid var(--accent-primary)' : '1px solid var(--glass-border)',
                padding: '0.4rem 1rem'
              }}
            >
              {tab.label}
            </button>
          ))}
        </div>
      </div>

      {loading ? (
        <LoadingSpinner />
      ) : (
        <>
          {logs.length === 0 ? (
            <div className="glass-card" style={{ textAlign: 'center', padding: '4rem 2rem', color: 'var(--text-secondary)' }}>
              <h3>No games found</h3>
              <p style={{ marginTop: '0.5rem' }}>{filter ? `You don't have any games marked as ${filter}.` : "Your library is empty. Go to Discover to add some games!"}</p>
            </div>
          ) : (
            <div style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))',
              gap: '1.5rem'
            }}>
              {logs.map(log => (
                <TrackerCard 
                  key={log.id} 
                  gameLog={log} 
                  onEdit={handleEditClick}
                  onDelete={handleDelete}
                />
              ))}
            </div>
          )}
        </>
      )}

      {showEditModal && (
        <EditModal 
          gameLog={selectedLog}
          onClose={() => setShowEditModal(false)}
          onSave={handleSaveEdit}
        />
      )}

    </div>
  );
};

export default LibraryPage;
