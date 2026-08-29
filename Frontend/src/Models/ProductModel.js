import { apiClient } from './api.js';

export class ProductModel {
  static async getAll(params = {}) {
    const queryParams = new URLSearchParams(params).toString();
    const endpoint = `/products${queryParams ? `?${queryParams}` : ''}`;
    return await apiClient.get(endpoint);
  }

  static async getById(id) {
    return await apiClient.get(`/products/${id}`);
  }

  static async create(productData) {
    return await apiClient.post('/products', productData);
  }

  static async update(id, productData) {
    return await apiClient.put(`/products/${id}`, productData);
  }

  static async delete(id) {
    return await apiClient.delete(`/products/${id}`);
  }

  static formatProduct(product) {
    return {
      id: product.id,
      name: product.name,
      description: product.description,
      price: parseFloat(product.price),
      stockQuantity: product.stock_quantity,
      sku: product.sku,
      isActive: product.is_active,
      createdAt: product.created_at,
      updatedAt: product.updated_at,
    };
  }
}
