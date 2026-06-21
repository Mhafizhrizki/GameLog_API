import React from 'react';
import { Link, Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const LandingPage = () => {
  const { token } = useAuth();

  if (token) {
    return <Navigate to="/discover" replace />;
  }

  return (
    <div style={{
      minHeight: 'calc(100vh - 80px)',
      display: 'flex',
      flexDirection: 'column',
      justifyContent: 'center',
      alignItems: 'center',
      textAlign: 'center',
      padding: '2rem'
    }} className="animate-fade-in">
      
      <div style={{ maxWidth: '800px' }}>
        <h1 style={{ 
          fontSize: '3.5rem', 
          fontWeight: '700', 
          marginBottom: '1.5rem',
          lineHeight: '1.2'
        }}>
          Track every game you've ever played. <br />
          <span style={{ color: 'var(--accent-primary)' }}>All in one place.</span>
        </h1>
        
        <p style={{ 
          fontSize: '1.2rem', 
          color: 'var(--text-secondary)',
          marginBottom: '3rem',
          maxWidth: '600px',
          margin: '0 auto 3rem auto',
          lineHeight: '1.6'
        }}>
          GameLog Tracker is your personal gaming diary. Discover new titles, organize your library, and keep track of your playing status effortlessly with a premium dark mode experience.
        </p>
        
        <div style={{ display: 'flex', gap: '1rem', justifyContent: 'center' }}>
          <Link to="/register" className="btn btn-primary" style={{ padding: '0.8rem 2rem', fontSize: '1.1rem' }}>
            Get Started
          </Link>
          <Link to="/login" className="btn btn-secondary" style={{ padding: '0.8rem 2rem', fontSize: '1.1rem' }}>
            I already have an account
          </Link>
        </div>
      </div>

    </div>
  );
};

export default LandingPage;
