import api from './axiosInstance';

export const authApi = {
  /**
   * Daftarkan pengguna baru
   * @param {Object} data - { name, email, password, password_confirmation }
   * @returns Promise
   */
  register: async (data) => {
    const response = await api.post('/v1/register', data);
    return response.data;
  },

  /**
   * Login pengguna
   * @param {Object} credentials - { email, password }
   * @returns Promise
   */
  login: async (credentials) => {
    const response = await api.post('/v1/login', credentials);
    return response.data;
  },

  /**
   * Logout pengguna
   * @returns Promise
   */
  logout: async () => {
    const response = await api.post('/v1/logout');
    return response.data;
  }
};
