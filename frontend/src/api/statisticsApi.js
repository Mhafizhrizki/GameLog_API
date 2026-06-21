import api from './axiosInstance';

export const statisticsApi = {
  /**
   * Ambil ringkasan statistik aktivitas game pengguna
   * @returns Promise
   */
  getUserStatistics: async () => {
    const response = await api.get('/v1/user/statistics');
    return response.data;
  }
};
