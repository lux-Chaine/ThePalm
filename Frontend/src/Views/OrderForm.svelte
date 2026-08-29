<script>
  import { OrderModel } from '../Models/OrderModel';
  import { OrderController } from '../Controllers/OrderController';

  let orderController;
  let loading = false;
  let error = null;
  let success = false;
  let formData = {
    userId: '',
    orderNumber: '',
    totalAmount: '',
    shippingAddress: '',
    notes: ''
  };

  orderController = new OrderController();

  async function handleSubmit() {
    loading = true;
    error = null;
    success = false;
    
    try {
      const orderData = {
        user_id: parseInt(formData.userId),
        order_number: formData.orderNumber,
        total_amount: parseFloat(formData.totalAmount),
        shipping_address: formData.shippingAddress,
        notes: formData.notes || null
      };

      await orderController.createOrder(orderData);
      success = true;
      resetForm();
    } catch (err) {
      error = 'Failed to create order';
      console.error(err);
    } finally {
      loading = false;
    }
  }

  function resetForm() {
    formData = {
      userId: '',
      orderNumber: '',
      totalAmount: '',
      shippingAddress: '',
      notes: ''
    };
  }
</script>

<div class="order-form">
  <h2>Create Order</h2>
  
  {#if error}
    <div class="error">{error}</div>
  {/if}

  {#if success}
    <div class="success">Order created successfully!</div>
  {/if}

  <form on:submit|preventDefault={handleSubmit}>
    <div>
      <label for="userId">User ID:</label>
      <input 
        id="userId" 
        type="number" 
        bind:value={formData.userId} 
        required 
      />
    </div>

    <div>
      <label for="orderNumber">Order Number:</label>
      <input 
        id="orderNumber" 
        type="text" 
        bind:value={formData.orderNumber} 
        required 
      />
    </div>

    <div>
      <label for="totalAmount">Total Amount:</label>
      <input 
        id="totalAmount" 
        type="number" 
        step="0.01" 
        bind:value={formData.totalAmount} 
        required 
      />
    </div>

    <div>
      <label for="shippingAddress">Shipping Address:</label>
      <textarea 
        id="shippingAddress" 
        bind:value={formData.shippingAddress} 
        required
      ></textarea>
    </div>

    <div>
      <label for="notes">Notes (optional):</label>
      <textarea 
        id="notes" 
        bind:value={formData.notes}
      ></textarea>
    </div>

    <button type="submit" disabled={loading}>
      {loading ? 'Creating...' : 'Create Order'}
    </button>
    
    <button type="button" on:click={resetForm}>
      Reset
    </button>
  </form>
</div>

<style>
  .order-form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
  }

  .error {
    color: red;
    padding: 10px;
    background: #fee;
    border: 1px solid #fcc;
    margin-bottom: 15px;
    border-radius: 4px;
  }

  .success {
    color: green;
    padding: 10px;
    background: #efe;
    border: 1px solid #cfc;
    margin-bottom: 15px;
    border-radius: 4px;
  }

  form div {
    margin-bottom: 20px;
  }

  label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
  }

  input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
  }

  textarea {
    min-height: 80px;
    resize: vertical;
  }

  input:focus, textarea:focus {
    outline: none;
    border-color: #007bff;
  }

  button {
    padding: 10px 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    margin-right: 10px;
  }

  button:hover:not(:disabled) {
    background: #0056b3;
  }

  button:disabled {
    background: #ccc;
    cursor: not-allowed;
  }

  button[type="button"] {
    background: #6c757d;
  }

  button[type="button"]:hover {
    background: #545b62;
  }
</style>
