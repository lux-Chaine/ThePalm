import { apiClient } from './api.js';

export class UserModel {
  static async getAll(params = {}) {
    const queryParams = new URLSearchParams(params).toString();
    const endpoint = `/users${queryParams ? `?${queryParams}` : ''}`;
    return await apiClient.get(endpoint);
  }

  static async getById(id) {
    return await apiClient.get(`/users/${id}`);
  }

  static async create(userData) {
    return await apiClient.post('/users', userData);
  }

  static async update(id, userData) {
    return await apiClient.put(`/users/${id}`, userData);
  }

  static async delete(id) {
    return await apiClient.delete(`/users/${id}`);
  }

  static formatUser(user) {
    return {
      id: user.id,
      name: user.name,
      email: user.email,
      role: user.role,
      createdAt: user.created_at,
      updatedAt: user.updated_at,
    };
  }
}
