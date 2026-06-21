import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Navbar = () => {
  const { token, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <nav className="glass" style={{
      position: 'sticky',
      top: 0,
      zIndex: 50,
      padding: '1rem 0'
    }}>
      <div className="container" style={{
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center'
      }}>
        <Link to="/" style={{ 
          textDecoration: 'none', 
          color: 'var(--text-primary)',
          fontSize: '1.5rem',
          fontWeight: '700',
          display: 'flex',
          alignItems: 'center',
          gap: '0.5rem'
        }}>
          <span style={{ color: 'var(--accent-primary)' }}>GameLog</span> Tracker
        </Link>

        <div style={{ display: 'flex', gap: '1.5rem', alignItems: 'center' }}>
          {token ? (
            <>
              <Link to="/discover" style={{ color: 'var(--text-secondary)', textDecoration: 'none', fontWeight: '500' }}>
                Discover
              </Link>
              <Link to="/library" style={{ color: 'var(--text-secondary)', textDecoration: 'none', fontWeight: '500' }}>
                My Library
              </Link>
              <Link to="/statistics" style={{ color: 'var(--text-secondary)', textDecoration: 'none', fontWeight: '500' }}>
                Statistics
              </Link>
              <button onClick={handleLogout} className="btn btn-secondary" style={{ padding: '0.4rem 1rem' }}>
                Logout
              </button>
            </>
          ) : (
            <>
              <Link to="/login" className="btn btn-secondary">Login</Link>
              <Link to="/register" className="btn btn-primary">Register</Link>
            </>
          )}
        </div>
      </div>
      
      <style>{`
        nav a:hover {
          color: var(--text-primary) !important;
        }
      `}</style>
    </nav>
  );
};

export default Navbar;
