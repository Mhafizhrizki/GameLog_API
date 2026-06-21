import React, { useState, useEffect, useCallback } from 'react';
import { rawgApi } from '../api/rawgApi';
import { gameLogApi } from '../api/gameLogApi';
import GameCard from '../components/GameCard';
import AddToTrackerModal from '../components/Modals/AddToTrackerModal';
import LoadingSpinner from '../components/LoadingSpinner';

const DiscoverPage = () => {
  const [games, setGames] = useState([]);
  const [loading, setLoading] = useState(true);
  const [query, setQuery] = useState('');
  const [debouncedQuery, setDebouncedQuery] = useState('');
  
  const [selectedGame, setSelectedGame] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [toastMessage, setToastMessage] = useState('');

  // Debounce search input
  useEffect(() => {
    const timer = setTimeout(() => {
      setDebouncedQuery(query);
    }, 500);
    return () => clearTimeout(timer);
  }, [query]);

  const fetchGames = useCallback(async () => {
    setLoading(true);
    try {
      const response = await rawgApi.searchGames(debouncedQuery, 1);
      if (response.data && response.data.results) {
        setGames(response.data.results);
      }
    } catch (error) {
      console.error('Failed to fetch games', error);
    } finally {
      setLoading(false);
    }
  }, [debouncedQuery]);

  useEffect(() => {
    fetchGames();
  }, [fetchGames]);

  const handleAddClick = (game) => {
    setSelectedGame(game);
    setShowModal(true);
  };

  const handleSaveToTracker = async (data) => {
    try {
      const result = await gameLogApi.addGameLog(data);
      if (result.status === 'success') {
        setToastMessage(`Successfully added ${data.title} to your tracker!`);
        setTimeout(() => setToastMessage(''), 3000);
      } else {
        alert(result.message || 'Failed to add game');
      }
    } catch (error) {
      // Check if it's a validation error (e.g. already in tracker)
      alert(error.response?.data?.message || 'Failed to add game to tracker');
    } finally {
      setShowModal(false);
      setSelectedGame(null);
    }
  };

  return (
    <div className="container animate-fade-in" style={{ padding: '2rem 1.5rem' }}>
      
      {toastMessage && (
        <div style={{
          position: 'fixed', bottom: '2rem', right: '2rem', zIndex: 1000,
          backgroundColor: 'var(--success)', color: 'white', padding: '1rem 2rem',
          borderRadius: 'var(--radius-md)', boxShadow: 'var(--shadow-lg)'
        }} className="animate-fade-in">
          {toastMessage}
        </div>
      )}

      <div style={{ marginBottom: '2rem' }}>
        <h1 style={{ fontSize: '2.5rem', marginBottom: '0.5rem' }}>Discover Games</h1>
        <p style={{ color: 'var(--text-secondary)' }}>Search and add games to your tracker from the RAWG database.</p>
      </div>

      <div style={{ marginBottom: '2rem', maxWidth: '600px' }}>
        <input
          type="text"
          className="input-field"
          placeholder="Search games (e.g. The Witcher 3)..."
          value={query}
          onChange={(e) => setQuery(e.target.value)}
        />
      </div>

      {loading ? (
        <LoadingSpinner />
      ) : (
        <>
          {games.length === 0 ? (
            <div style={{ textAlign: 'center', padding: '4rem 0', color: 'var(--text-secondary)' }}>
              No games found. Try a different search term.
            </div>
          ) : (
            <div style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fill, minmax(250px, 1fr))',
              gap: '1.5rem'
            }}>
              {games.map(game => (
                <GameCard 
                  key={game.id} 
                  game={game} 
                  onAdd={handleAddClick} 
                />
              ))}
            </div>
          )}
        </>
      )}

      {showModal && (
        <AddToTrackerModal 
          game={selectedGame}
          onClose={() => setShowModal(false)}
          onSave={handleSaveToTracker}
        />
      )}

    </div>
  );
};

export default DiscoverPage;
