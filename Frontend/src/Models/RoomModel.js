export class RoomModel {
  constructor(data = {}) {
    this.id = data.id || null;
    this.roomNumber = data.room_number || '';
    this.type = data.type || 'standard'; // standard, superior, junior_suite, executive_suite
    this.size = data.size || 0; // in square meters
    this.bedType = data.bed_type || 'king'; // king, twin
    this.maxOccupancy = data.max_occupancy || 2;
    this.floor = data.floor || 1;
    this.view = data.view || 'city'; // city, garden, pool, panorama
    this.pricePerNight = data.price_per_night || 0;
    this.status = data.status || 'available'; // available, occupied, maintenance, reserved
    this.amenities = data.amenities || [];
    this.description = data.description || '';
    this.images = data.images || [];
    this.createdAt = data.created_at || null;
    this.updatedAt = data.updated_at || null;
  }

  static fromAPI(data) {
    return new RoomModel({
      id: data.id,
      room_number: data.room_number,
      type: data.type,
      size: data.size,
      bed_type: data.bed_type,
      max_occupancy: data.max_occupancy,
      floor: data.floor,
      view: data.view,
      price_per_night: data.price_per_night,
      status: data.status,
      amenities: data.amenities || [],
      description: data.description,
      images: data.images || [],
      created_at: data.created_at,
      updated_at: data.updated_at
    });
  }

  toAPI() {
    return {
      id: this.id,
      room_number: this.roomNumber,
      type: this.type,
      size: this.size,
      bed_type: this.bedType,
      max_occupancy: this.maxOccupancy,
      floor: this.floor,
      view: this.view,
      price_per_night: this.pricePerNight,
      status: this.status,
      amenities: this.amenities,
      description: this.description,
      images: this.images
    };
  }

  get displayName() {
    const typeNames = {
      standard: 'Standard Room',
      superior: 'Superior Room',
      junior_suite: 'Junior Suite',
      executive_suite: 'Executive Suite'
    };
    return typeNames[this.type] || this.type;
  }

  get isAvailable() {
    return this.status === 'available';
  }

  get formattedPrice() {
    return new Intl.NumberFormat('en-EG', {
      style: 'currency',
      currency: 'EGP'
    }).format(this.pricePerNight);
  }
}
