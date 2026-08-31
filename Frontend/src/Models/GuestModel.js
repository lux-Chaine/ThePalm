export class GuestModel {
  constructor(data = {}) {
    this.id = data.id || null;
    this.firstName = data.first_name || '';
    this.lastName = data.last_name || '';
    this.email = data.email || '';
    this.phone = data.phone || '';
    this.nationality = data.nationality || '';
    this.idNumber = data.id_number || '';
    this.idType = data.id_type || 'passport'; // passport, national_id
    this.dateOfBirth = data.date_of_birth || null;
    this.address = data.address || '';
    this.city = data.city || '';
    this.country = data.country || '';
    this.preferences = data.preferences || [];
    this.notes = data.notes || '';
    this.createdAt = data.created_at || null;
    this.updatedAt = data.updated_at || null;
  }

  static fromAPI(data) {
    return new GuestModel({
      id: data.id,
      first_name: data.first_name,
      last_name: data.last_name,
      email: data.email,
      phone: data.phone,
      nationality: data.nationality,
      id_number: data.id_number,
      id_type: data.id_type,
      date_of_birth: data.date_of_birth,
      address: data.address,
      city: data.city,
      country: data.country,
      preferences: data.preferences || [],
      notes: data.notes,
      created_at: data.created_at,
      updated_at: data.updated_at
    });
  }

  toAPI() {
    return {
      id: this.id,
      first_name: this.firstName,
      last_name: this.lastName,
      email: this.email,
      phone: this.phone,
      nationality: this.nationality,
      id_number: this.idNumber,
      id_type: this.idType,
      date_of_birth: this.dateOfBirth,
      address: this.address,
      city: this.city,
      country: this.country,
      preferences: this.preferences,
      notes: this.notes
    };
  }

  get fullName() {
    return `${this.firstName} ${this.lastName}`.trim();
  }

  get initials() {
    return `${this.firstName.charAt(0)}${this.lastName.charAt(0)}`.toUpperCase();
  }
}
