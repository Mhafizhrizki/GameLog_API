import React from 'react';
import { Navigate, Outlet } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import LoadingSpinner from './LoadingSpinner';

const ProtectedRoute = () => {
  const { token, loading } = useAuth();

  if (loading) {
    return <LoadingSpinner fullScreen />;
  }

  // If not authenticated, redirect to login page
  if (!token) {
    return <Navigate to="/login" replace />;
  }

  // If authenticated, render child routes
  return <Outlet />;
};

export default ProtectedRoute;
