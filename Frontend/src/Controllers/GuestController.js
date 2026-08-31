import { GuestModel } from '../Models/GuestModel.js';

export class GuestController {
  constructor() {
    this.guests = [];
    this.loading = false;
    this.error = null;
    this.apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1';
  }

  async fetchGuests(filters = {}) {
    this.loading = true;
    this.error = null;
    
    try {
      const queryParams = new URLSearchParams(filters).toString();
      const response = await fetch(`${this.apiBaseUrl}/guests${queryParams ? `?${queryParams}` : ''}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      this.guests = data.data?.map(guest => GuestModel.fromAPI(guest)) || [];
      return this.guests;
    } catch (error) {
      this.error = error.message;
      console.error('Error fetching guests:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async fetchGuestById(id) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/guests/${id}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      return GuestModel.fromAPI(data.data);
    } catch (error) {
      this.error = error.message;
      console.error('Error fetching guest:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async searchGuests(query) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/guests/search?q=${encodeURIComponent(query)}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      this.guests = data.data?.map(guest => GuestModel.fromAPI(guest)) || [];
      return this.guests;
    } catch (error) {
      this.error = error.message;
      console.error('Error searching guests:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async createGuest(guestData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/guests`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(guestData)
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      const newGuest = GuestModel.fromAPI(data.data);
      this.guests.push(newGuest);
      return newGuest;
    } catch (error) {
      this.error = error.message;
      console.error('Error creating guest:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async updateGuest(id, guestData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/guests/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(guestData)
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      const updatedGuest = GuestModel.fromAPI(data.data);
      const index = this.guests.findIndex(guest => guest.id === id);
      if (index !== -1) {
        this.guests[index] = updatedGuest;
      }
      return updatedGuest;
    } catch (error) {
      this.error = error.message;
      console.error('Error updating guest:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async deleteGuest(id) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await fetch(`${this.apiBaseUrl}/guests/${id}`, {
        method: 'DELETE'
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      this.guests = this.guests.filter(guest => guest.id !== id);
      return true;
    } catch (error) {
      this.error = error.message;
      console.error('Error deleting guest:', error);
      throw error;
    } finally {
      this.loading = false;
    }
  }

  searchGuestsByName(name) {
    const searchTerm = name.toLowerCase();
    return this.guests.filter(guest => 
      guest.fullName.toLowerCase().includes(searchTerm)
    );
  }

  searchGuestsByEmail(email) {
    return this.guests.filter(guest => 
      guest.email.toLowerCase().includes(email.toLowerCase())
    );
  }

  searchGuestsByPhone(phone) {
    return this.guests.filter(guest => 
      guest.phone.includes(phone)
    );
  }
}
