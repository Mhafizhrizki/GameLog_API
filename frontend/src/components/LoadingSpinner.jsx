import React from 'react';

const LoadingSpinner = ({ fullScreen = false }) => {
  const spinner = (
    <div style={{
      display: 'inline-block',
      width: '40px',
      height: '40px',
      border: '3px solid var(--glass-border)',
      borderTop: '3px solid var(--accent-primary)',
      borderRadius: '50%',
      animation: 'spin 1s linear infinite'
    }} />
  );

  if (fullScreen) {
    return (
      <div style={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        height: '100vh',
        width: '100%',
        backgroundColor: 'var(--bg-primary)'
      }}>
        {spinner}
        <style>{`
          @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
          }
        `}</style>
      </div>
    );
  }

  return (
    <div style={{ display: 'flex', justifyContent: 'center', padding: '2rem' }}>
      {spinner}
      <style>{`
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      `}</style>
    </div>
  );
};

export default LoadingSpinner;
