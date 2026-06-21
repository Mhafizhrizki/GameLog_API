import React, { useState, useEffect } from 'react';
import { statisticsApi } from '../api/statisticsApi';
import StatCard from '../components/StatCard';
import LoadingSpinner from '../components/LoadingSpinner';

const StatisticsPage = () => {
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchStats = async () => {
      try {
        const response = await statisticsApi.getUserStatistics();
        if (response.status === 'success') {
          setStats(response.data);
        }
      } catch (error) {
        console.error('Failed to fetch statistics', error);
      } finally {
        setLoading(false);
      }
    };

    fetchStats();
  }, []);

  if (loading) return <LoadingSpinner fullScreen />;

  if (!stats) {
    return (
      <div className="container" style={{ padding: '4rem 1.5rem', textAlign: 'center' }}>
        <h2 style={{ color: 'var(--danger)' }}>Failed to load statistics</h2>
      </div>
    );
  }

  return (
    <div className="container animate-fade-in" style={{ padding: '2rem 1.5rem' }}>
      
      <div style={{ marginBottom: '3rem' }}>
        <h1 style={{ fontSize: '2.5rem', marginBottom: '0.5rem' }}>Your Statistics</h1>
        <p style={{ color: 'var(--text-secondary)' }}>A summary of your gaming journey.</p>
      </div>

      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
        gap: '1.5rem'
      }}>
        <StatCard title="Total Games" value={stats.total_games} color="var(--text-primary)" />
        <StatCard title="Playing" value={stats.playing_count} color="var(--accent-secondary)" />
        <StatCard title="Completed" value={stats.completed_count} color="var(--success)" />
        <StatCard title="Wishlist" value={stats.wishlist_count} color="var(--text-secondary)" />
        <StatCard 
          title="Average Rating" 
          value={stats.average_rating ? Number(stats.average_rating).toFixed(1) : '0'} 
          color="#fbbf24" 
        />
      </div>

    </div>
  );
};

export default StatisticsPage;
