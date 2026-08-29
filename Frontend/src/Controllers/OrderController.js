import { OrderModel } from '../Models/OrderModel';

export class OrderController {
  constructor() {
    this.orders = [];
    this.loading = false;
    this.error = null;
  }

  async createOrder(orderData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await OrderModel.create(orderData);
      const newOrder = OrderModel.formatOrder(response.data);
      this.orders.push(newOrder);
      return newOrder;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async updateOrderStatus(id, status) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await OrderModel.updateStatus(id, status);
      const updatedOrder = OrderModel.formatOrder(response.data);
      const index = this.orders.findIndex(o => o.id === id);
      if (index !== -1) {
        this.orders[index] = updatedOrder;
      }
      return updatedOrder;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  getOrders() {
    return this.orders;
  }

  getOrdersByStatus(status) {
    return this.orders.filter(o => o.status === status);
  }

  getPendingOrders() {
    return this.getOrdersByStatus('pending');
  }

  getCompletedOrders() {
    return this.getOrdersByStatus('completed');
  }

  getCancelledOrders() {
    return this.getOrdersByStatus('cancelled');
  }

  isLoading() {
    return this.loading;
  }

  getError() {
    return this.error;
  }

  clearError() {
    this.error = null;
  }
}
