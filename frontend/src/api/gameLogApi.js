import api from './axiosInstance';

export const gameLogApi = {
  /**
   * Ambil daftar game logs pengguna
   * @param {string} [status] - Optional filter status (playing, wishlist, completed)
   * @returns Promise
   */
  getGameLogs: async (status = '') => {
    const url = status ? `/v1/gamelogs?status=${status}` : '/v1/gamelogs';
    const response = await api.get(url);
    return response.data;
  },

  /**
   * Tambahkan game baru ke tracker
   * @param {Object} data - { rawg_id, title, status, personal_rating }
   * @returns Promise
   */
  addGameLog: async (data) => {
    const response = await api.post('/v1/gamelogs', data);
    return response.data;
  },

  /**
   * Update status/rating game di tracker
   * @param {number} id - ID GameLog di database
   * @param {Object} data - { status, personal_rating }
   * @returns Promise
   */
  updateGameLog: async (id, data) => {
    const response = await api.put(`/v1/gamelogs/${id}`, data);
    return response.data;
  },

  /**
   * Hapus game dari tracker
   * @param {number} id - ID GameLog di database
   * @returns Promise
   */
  deleteGameLog: async (id) => {
    const response = await api.delete(`/v1/gamelogs/${id}`);
    return response.data;
  }
};
