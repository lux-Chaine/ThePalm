export class ReservationModel {
  constructor(data = {}) {
    this.id = data.id || null;
    this.confirmationNumber = data.confirmation_number || '';
    this.guestId = data.guest_id || null;
    this.roomId = data.room_id || null;
    this.checkInDate = data.check_in_date || null;
    this.checkOutDate = data.check_out_date || null;
    this.adults = data.adults || 1;
    this.children = data.children || 0;
    this.status = data.status || 'pending'; // pending, confirmed, checked_in, checked_out, cancelled, no_show
    this.totalAmount = data.total_amount || 0;
    this.paidAmount = data.paid_amount || 0;
    this.paymentStatus = data.payment_status || 'pending'; // pending, partial, paid, refunded
    this.specialRequests = data.special_requests || '';
    this.createdAt = data.created_at || null;
    this.updatedAt = data.updated_at || null;
  }

  static fromAPI(data) {
    return new ReservationModel({
      id: data.id,
      confirmation_number: data.confirmation_number,
      guest_id: data.guest_id,
      room_id: data.room_id,
      check_in_date: data.check_in_date,
      check_out_date: data.check_out_date,
      adults: data.adults,
      children: data.children,
      status: data.status,
      total_amount: data.total_amount,
      paid_amount: data.paid_amount,
      payment_status: data.payment_status,
      special_requests: data.special_requests,
      created_at: data.created_at,
      updated_at: data.updated_at
    });
  }

  toAPI() {
    return {
      id: this.id,
      confirmation_number: this.confirmationNumber,
      guest_id: this.guestId,
      room_id: this.roomId,
      check_in_date: this.checkInDate,
      check_out_date: this.checkOutDate,
      adults: this.adults,
      children: this.children,
      status: this.status,
      total_amount: this.totalAmount,
      paid_amount: this.paidAmount,
      payment_status: this.paymentStatus,
      special_requests: this.specialRequests
    };
  }

  get nights() {
    if (!this.checkInDate || !this.checkOutDate) return 0;
    const checkIn = new Date(this.checkInDate);
    const checkOut = new Date(this.checkOutDate);
    const diffTime = Math.abs(checkOut - checkIn);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
  }

  get remainingAmount() {
    return this.totalAmount - this.paidAmount;
  }

  get formattedTotal() {
    return new Intl.NumberFormat('en-EG', {
      style: 'currency',
      currency: 'EGP'
    }).format(this.totalAmount);
  }

  get formattedPaid() {
    return new Intl.NumberFormat('en-EG', {
      style: 'currency',
      currency: 'EGP'
    }).format(this.paidAmount);
  }

  get formattedRemaining() {
    return new Intl.NumberFormat('en-EG', {
      style: 'currency',
      currency: 'EGP'
    }).format(this.remainingAmount);
  }

  get statusLabel() {
    const statusLabels = {
      pending: 'Pending',
      confirmed: 'Confirmed',
      checked_in: 'Checked In',
      checked_out: 'Checked Out',
      cancelled: 'Cancelled',
      no_show: 'No Show'
    };
    return statusLabels[this.status] || this.status;
  }

  get paymentStatusLabel() {
    const paymentStatusLabels = {
      pending: 'Pending',
      partial: 'Partial',
      paid: 'Paid',
      refunded: 'Refunded'
    };
    return paymentStatusLabels[this.paymentStatus] || this.paymentStatus;
  }
}
