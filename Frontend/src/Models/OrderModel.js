import { apiClient } from './api.js';

export class OrderModel {
  static async create(orderData) {
    return await apiClient.post('/orders', orderData);
  }

  static async updateStatus(id, status) {
    return await apiClient.put(`/orders/${id}/status`, { status });
  }

  static formatOrder(order) {
    return {
      id: order.id,
      userId: order.user_id,
      orderNumber: order.order_number,
      totalAmount: parseFloat(order.total_amount),
      status: order.status,
      shippingAddress: order.shipping_address,
      notes: order.notes,
      createdAt: order.created_at,
      updatedAt: order.updated_at,
    };
  }
}
