import { RoomModel } from '../Models/RoomModel.js';

export class RoomController {
  constructor() {
    this.rooms = [];
    this.loading = false;
    this.error = null;
    this.apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1';
  }

  async fetchRooms(filters = {}) {
    this.loading = true;
    this.error = null;
    
    try {
      const queryParams = new URLSearchParams(filters).toString();
      const response = await fetch(`${this.apiBaseUrl}/rooms${queryParams ? `?${queryParams}` : ''}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      this.rooms = data.data?.map(room => RoomModel.fromAPI(room)) || [];
      return this.rooms;
    } catch (error) {
      this.error = error.message;
      console.error('Error fetching rooms:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async fetchRoomById(id) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/rooms/${id}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      return RoomModel.fromAPI(data.data);
    } catch (error) {
      this.error = error.message;
      console.error('Error fetching room:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async createRoom(roomData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/rooms`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(roomData)
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      const newRoom = RoomModel.fromAPI(data.data);
      this.rooms.push(newRoom);
      return newRoom;
    } catch (error) {
      this.error = error.message;
      console.error('Error creating room:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async updateRoom(id, roomData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/rooms/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(roomData)
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      const updatedRoom = RoomModel.fromAPI(data.data);
      const index = this.rooms.findIndex(room => room.id === id);
      if (index !== -1) {
        this.rooms[index] = updatedRoom;
      }
      return updatedRoom;
    } catch (error) {
      this.error = error.message;
      console.error('Error updating room:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  getAvailableRooms(checkInDate, checkOutDate) {
    return this.rooms.filter(room => room.isAvailable);
  }

  getRoomsByType(type) {
    return this.rooms.filter(room => room.type === type);
  }

  getRoomsByPriceRange(minPrice, maxPrice) {
    return this.rooms.filter(room => 
      room.pricePerNight >= minPrice && room.pricePerNight <= maxPrice
    );
  }
}
