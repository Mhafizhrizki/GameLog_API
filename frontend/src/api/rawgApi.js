import api from './axiosInstance';

export const rawgApi = {
  /**
   * Cari game di RAWG melalui proxy Backend Laravel
   * @param {string} query - Keyword pencarian
   * @param {number} page - Halaman ke berapa
   * @returns Promise
   */
  searchGames: async (query = '', page = 1) => {
    const response = await api.get('/v1/games/search', {
      params: {
        search: query,
        page: page
      }
    });
    return response.data;
  }
};
