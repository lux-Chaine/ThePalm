import { UserModel } from '../Models/UserModel';

export class UserController {
  constructor() {
    this.users = [];
    this.loading = false;
    this.error = null;
  }

  async fetchUsers(params = {}) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await UserModel.getAll(params);
      this.users = response.data.map(UserModel.formatUser);
      return response;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async fetchUserById(id) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await UserModel.getById(id);
      return UserModel.formatUser(response.data);
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async createUser(userData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await UserModel.create(userData);
      const newUser = UserModel.formatUser(response.data);
      this.users.push(newUser);
      return newUser;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async updateUser(id, userData) {
    this.loading = true;
    this.error = null;
    
    try {
      const response = await UserModel.update(id, userData);
      const updatedUser = UserModel.formatUser(response.data);
      const index = this.users.findIndex(u => u.id === id);
      if (index !== -1) {
        this.users[index] = updatedUser;
      }
      return updatedUser;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  async deleteUser(id) {
    this.loading = true;
    this.error = null;
    
    try {
      await UserModel.delete(id);
      this.users = this.users.filter(u => u.id !== id);
      return true;
    } catch (error) {
      this.error = error.message;
      throw error;
    } finally {
      this.loading = false;
    }
  }

  getUsers() {
    return this.users;
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
