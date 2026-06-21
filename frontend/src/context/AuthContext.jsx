import React, { createContext, useState, useEffect, useContext } from 'react';
import { authApi } from '../api/authApi';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token') || null);
  const [loading, setLoading] = useState(true);

  // When token changes, we don't fetch user automatically here unless we had a /me endpoint,
  // but for simplicity, we'll rely on login response setting the user.
  // We can just check if token exists to determine auth status initially.
  useEffect(() => {
    // If we have a token but no user, we assume logged in for now.
    // In a real app, you might fetch user profile here.
    if (token) {
      // Decode user from somewhere or just know we are authenticated
      setUser({ authenticated: true });
    } else {
      setUser(null);
    }
    setLoading(false);
  }, [token]);

  const login = async (credentials) => {
    try {
      const response = await authApi.login(credentials);
      if (response.status === 'success') {
        const { user, token } = response.data;
        localStorage.setItem('token', token);
        setToken(token);
        setUser(user);
        return { success: true };
      }
      return { success: false, message: response.message || 'Login failed' };
    } catch (error) {
      return { 
        success: false, 
        message: error.response?.data?.message || 'Login failed' 
      };
    }
  };

  const register = async (data) => {
    try {
      const response = await authApi.register(data);
      if (response.status === 'success') {
        const { user, token } = response.data;
        localStorage.setItem('token', token);
        setToken(token);
        setUser(user);
        return { success: true };
      }
      return { success: false, message: response.message || 'Registration failed' };
    } catch (error) {
      return { 
        success: false, 
        message: error.response?.data?.message || 'Registration failed' 
      };
    }
  };

  const logout = async () => {
    try {
      if (token) {
        await authApi.logout();
      }
    } catch (error) {
      console.error('Logout error', error);
    } finally {
      localStorage.removeItem('token');
      setToken(null);
      setUser(null);
    }
  };

  return (
    <AuthContext.Provider value={{ user, token, loading, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
