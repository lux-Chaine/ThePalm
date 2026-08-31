export class UserModel {
  constructor(data = {}) {
    this.id = data.id || null;
    this.username = data.username || '';
    this.email = data.email || '';
    this.firstName = data.first_name || '';
    this.lastName = data.last_name || '';
    this.role = data.role || 'user'; // admin, manager, receptionist, user
    this.isActive = data.is_active !== false;
    this.permissions = data.permissions || [];
    this.lastLogin = data.last_login || null;
    this.createdAt = data.created_at || null;
    this.updatedAt = data.updated_at || null;
  }

  static fromAPI(data) {
    return new UserModel({
      id: data.id,
      username: data.username,
      email: data.email,
      first_name: data.first_name,
      last_name: data.last_name,
      role: data.role,
      is_active: data.is_active,
      permissions: data.permissions || [],
      last_login: data.last_login,
      created_at: data.created_at,
      updated_at: data.updated_at
    });
  }

  toAPI() {
    return {
      id: this.id,
      username: this.username,
      email: this.email,
      first_name: this.firstName,
      last_name: this.lastName,
      role: this.role,
      is_active: this.isActive,
      permissions: this.permissions
    };
  }

  get fullName() {
    return `${this.firstName} ${this.lastName}`.trim();
  }

  get initials() {
    return `${this.firstName.charAt(0)}${this.lastName.charAt(0)}`.toUpperCase();
  }

  get roleLabel() {
    const roleLabels = {
      admin: 'Administrator',
      manager: 'Manager',
      receptionist: 'Receptionist',
      user: 'User'
    };
    return roleLabels[this.role] || this.role;
  }

  hasPermission(permission) {
    return this.permissions.includes(permission);
  }
}
