import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const RegisterPage = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
  });
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  
  const { register } = useAuth();
  const navigate = useNavigate();

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.id]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (formData.password !== formData.password_confirmation) {
      return setError('Passwords do not match');
    }

    setIsLoading(true);

    const result = await register(formData);
    
    if (result.success) {
      navigate('/discover');
    } else {
      setError(result.message);
      setIsLoading(false);
    }
  };

  return (
    <div style={{
      minHeight: 'calc(100vh - 80px)',
      display: 'flex',
      justifyContent: 'center',
      alignItems: 'center',
      padding: '2rem'
    }}>
      <div className="glass-card animate-fade-in" style={{
        width: '100%',
        maxWidth: '450px',
        padding: '2.5rem'
      }}>
        <h2 style={{ fontSize: '1.8rem', marginBottom: '0.5rem', textAlign: 'center' }}>
          Create an Account
        </h2>
        <p style={{ color: 'var(--text-secondary)', textAlign: 'center', marginBottom: '2rem' }}>
          Join GameLog and start tracking your games
        </p>

        {error && (
          <div style={{
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            border: '1px solid var(--danger)',
            color: 'var(--danger)',
            padding: '0.75rem',
            borderRadius: 'var(--radius-sm)',
            marginBottom: '1.5rem',
            fontSize: '0.9rem',
            textAlign: 'center'
          }}>
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
          <div>
            <label htmlFor="name" style={{ display: 'block', marginBottom: '0.5rem', fontSize: '0.9rem' }}>
              Full Name
            </label>
            <input
              type="text"
              id="name"
              className="input-field"
              value={formData.name}
              onChange={handleChange}
              placeholder="John Doe"
              required
            />
          </div>

          <div>
            <label htmlFor="email" style={{ display: 'block', marginBottom: '0.5rem', fontSize: '0.9rem' }}>
              Email Address
            </label>
            <input
              type="email"
              id="email"
              className="input-field"
              value={formData.email}
              onChange={handleChange}
              placeholder="you@example.com"
              required
            />
          </div>

          <div>
            <label htmlFor="password" style={{ display: 'block', marginBottom: '0.5rem', fontSize: '0.9rem' }}>
              Password
            </label>
            <input
              type="password"
              id="password"
              className="input-field"
              value={formData.password}
              onChange={handleChange}
              placeholder="Minimum 8 characters"
              required
              minLength="8"
            />
          </div>

          <div>
            <label htmlFor="password_confirmation" style={{ display: 'block', marginBottom: '0.5rem', fontSize: '0.9rem' }}>
              Confirm Password
            </label>
            <input
              type="password"
              id="password_confirmation"
              className="input-field"
              value={formData.password_confirmation}
              onChange={handleChange}
              placeholder="Repeat your password"
              required
              minLength="8"
            />
          </div>

          <button 
            type="submit" 
            className="btn btn-primary" 
            style={{ width: '100%', padding: '0.8rem', marginTop: '0.5rem' }}
            disabled={isLoading}
          >
            {isLoading ? 'Creating account...' : 'Create Account'}
          </button>
        </form>

        <p style={{ textAlign: 'center', marginTop: '2rem', fontSize: '0.9rem', color: 'var(--text-secondary)' }}>
          Already have an account? <Link to="/login" style={{ color: 'var(--accent-primary)', textDecoration: 'none' }}>Log in here</Link>
        </p>
      </div>
    </div>
  );
};

export default RegisterPage;
