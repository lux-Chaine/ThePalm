export class InvoiceModel {
  constructor(data = {}) {
    this.id = data.id || null;
    this.invoiceNumber = data.invoice_number || '';
    this.reservationId = data.reservation_id || null;
    this.guestId = data.guest_id || null;
    this.issueDate = data.issue_date || null;
    this.dueDate = data.due_date || null;
    this.subtotal = data.subtotal || 0;
    this.taxAmount = data.tax_amount || 0;
    this.serviceCharge = data.service_charge || 0;
    this.discountAmount = data.discount_amount || 0;
    this.totalAmount = data.total_amount || 0;
    this.paidAmount = data.paid_amount || 0;
    this.status = data.status || 'draft'; // draft, sent, paid, overdue, cancelled
    this.notes = data.notes || '';
    this.items = data.items || [];
    this.createdAt = data.created_at || null;
    this.updatedAt = data.updated_at || null;
  }

  static fromAPI(data) {
    return new InvoiceModel({
      id: data.id,
      invoice_number: data.invoice_number,
      reservation_id: data.reservation_id,
      guest_id: data.guest_id,
      issue_date: data.issue_date,
      due_date: data.due_date,
      subtotal: data.subtotal,
      tax_amount: data.tax_amount,
      service_charge: data.service_charge,
      discount_amount: data.discount_amount,
      total_amount: data.total_amount,
      paid_amount: data.paid_amount,
      status: data.status,
      notes: data.notes,
      items: data.items || [],
      created_at: data.created_at,
      updated_at: data.updated_at
    });
  }

  toAPI() {
    return {
      id: this.id,
      invoice_number: this.invoiceNumber,
      reservation_id: this.reservationId,
      guest_id: this.guestId,
      issue_date: this.issueDate,
      due_date: this.dueDate,
      subtotal: this.subtotal,
      tax_amount: this.taxAmount,
      service_charge: this.serviceCharge,
      discount_amount: this.discountAmount,
      total_amount: this.totalAmount,
      paid_amount: this.paidAmount,
      status: this.status,
      notes: this.notes,
      items: this.items
    };
  }

  get remainingAmount() {
    return this.totalAmount - this.paidAmount;
  }

  get formattedSubtotal() {
    return new Intl.NumberFormat('en-EG', {
      style: 'currency',
      currency: 'EGP'
    }).format(this.subtotal);
  }

  get formattedTax() {
    return new Intl.NumberFormat('en-EG', {
      style: 'currency',
      currency: 'EGP'
    }).format(this.taxAmount);
  }

  get formattedServiceCharge() {
    return new Intl.NumberFormat('en-EG', {
      style: 'currency',
      currency: 'EGP'
    }).format(this.serviceCharge);
  }

  get formattedDiscount() {
    return new Intl.NumberFormat('en-EG', {
      style: 'currency',
      currency: 'EGP'
    }).format(this.discountAmount);
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
      draft: 'Draft',
      sent: 'Sent',
      paid: 'Paid',
      overdue: 'Overdue',
      cancelled: 'Cancelled'
    };
    return statusLabels[this.status] || this.status;
  }
}
