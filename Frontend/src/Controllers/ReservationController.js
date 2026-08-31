import { ReservationModel } from '../Models/ReservationModel.js';

export class ReservationController {
  constructor() {
    this.reservations = [];
    this.loading = false;
    this.error = null;
    this.apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1';
  }

  async fetchReservations(filters = {}) {
    this.loading = true;
    this.error = null;
    
    try {
      const queryParams = new URLSearchParams(filters).toString();
      const response = await fetch(`${this.apiBaseUrl}/reservations${queryParams ? `?${queryParams}` : ''}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      this.reservations = data.data?.map(res => ReservationModel.fromAPI(res)) || [];
      return this.reservations;
    } catch (error) {
      this.error = error.message;
      console.error('Error fetching reservations:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async fetchReservationById(id) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/reservations/${id}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      return ReservationModel.fromAPI(data.data);
    } catch (error) {
      this.error = error.message;
      console.error('Error fetching reservation:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async createReservation(reservationData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/reservations`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(reservationData)
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      const newReservation = ReservationModel.fromAPI(data.data);
      this.reservations.push(newReservation);
      return newReservation;
    } catch (error) {
      this.error = error.message;
      console.error('Error creating reservation:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async updateReservationStatus(id, status) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/reservations/${id}/status`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status })
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      const updatedReservation = ReservationModel.fromAPI(data.data);
      const index = this.reservations.findIndex(res => res.id === id);
      if (index !== -1) {
        this.reservations[index] = updatedReservation;
      }
      return updatedReservation;
    } catch (error) {
      this.error = error.message;
      console.error('Error updating reservation status:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  getReservationsByStatus(status) {
    return this.reservations.filter(res => res.status === status);
  }

  getReservationsByDateRange(startDate, endDate) {
    return this.reservations.filter(res => {
      const resDate = new Date(res.checkInDate);
      return resDate >= new Date(startDate) && resDate <= new Date(endDate);
    });
  }

  getTodaysCheckIns() {
    const today = new Date().toISOString().split('T')[0];
    return this.reservations.filter(res => res.checkInDate === today);
  }

  getTodaysCheckOuts() {
    const today = new Date().toISOString().split('T')[0];
    return this.reservations.filter(res => res.checkOutDate === today);
  }
}
