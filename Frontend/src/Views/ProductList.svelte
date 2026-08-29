<script>
  import { onMount } from 'svelte';
  import { ProductModel } from '../Models/ProductModel';
  import { ProductController } from '../Controllers/ProductController';

  let products = [];
  let loading = false;
  let error = null;
  let productController = new ProductController();
  let showForm = false;
  let formData = {
    name: '',
    description: '',
    price: '',
    stockQuantity: '',
    sku: '',
    isActive: true
  };

  onMount(() => {
    loadProducts();
  });

  async function loadProducts() {
    loading = true;
    error = null;
    try {
      await productController.fetchProducts();
      products = productController.getProducts();
    } catch (err) {
      error = 'Failed to load products';
      console.error(err);
    } finally {
      loading = false;
    }
  }

  async function handleSubmit() {
    try {
      const productData = {
        name: formData.name,
        description: formData.description,
        price: parseFloat(formData.price),
        stock_quantity: parseInt(formData.stockQuantity),
        sku: formData.sku,
        is_active: formData.isActive
      };
      
      await productController.createProduct(productData);
      showForm = false;
      resetForm();
      await loadProducts();
    } catch (err) {
      error = 'Failed to create product';
      console.error(err);
    }
  }

  function resetForm() {
    formData = {
      name: '',
      description: '',
      price: '',
      stockQuantity: '',
      sku: '',
      isActive: true
    };
  }
</script>

<div class="product-list">
  <h2>Products</h2>
  
  <button on:click={() => showForm = true}>Add New Product</button>
  
  {#if error}
    <div class="error">{error}</div>
  {/if}

  {#if showForm}
    <div class="form">
      <h3>Create Product</h3>
      <form on:submit|preventDefault={handleSubmit}>
        <div>
          <label>Name:</label>
          <input type="text" bind:value={formData.name} required />
        </div>
        <div>
          <label>Description:</label>
          <textarea bind:value={formData.description} required></textarea>
        </div>
        <div>
          <label>Price:</label>
          <input type="number" step="0.01" bind:value={formData.price} required />
        </div>
        <div>
          <label>Stock Quantity:</label>
          <input type="number" bind:value={formData.stockQuantity} required />
        </div>
        <div>
          <label>SKU:</label>
          <input type="text" bind:value={formData.sku} required />
        </div>
        <div>
          <label>
            <input type="checkbox" bind:checked={formData.isActive} />
            Active
          </label>
        </div>
        <button type="submit">Create</button>
        <button type="button" on:click={() => showForm = false}>Cancel</button>
      </form>
    </div>
  {/if}

  {#if loading}
    <div class="loading">Loading...</div>
  {:else}
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Stock</th>
          <th>SKU</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        {#each products as product}
          <tr>
            <td>{product.id}</td>
            <td>{product.name}</td>
            <td>${product.price.toFixed(2)}</td>
            <td>{product.stockQuantity}</td>
            <td>{product.sku}</td>
            <td>{product.isActive ? 'Active' : 'Inactive'}</td>
          </tr>
        {/each}
      </tbody>
    </table>
  {/if}
</div>

<style>
  .product-list {
    padding: 20px;
  }

  .error {
    color: red;
    padding: 10px;
    background: #fee;
    border: 1px solid #fcc;
    margin-bottom: 10px;
  }

  .loading {
    padding: 20px;
    color: #666;
  }

  .form {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
  }

  .form div {
    margin-bottom: 15px;
  }

  .form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }

  .form input, .form textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  .form textarea {
    min-height: 80px;
    resize: vertical;
  }

  button {
    padding: 8px 16px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
  }

  button:hover {
    background: #0056b3;
  }

  button[type="button"] {
    background: #6c757d;
  }

  button[type="button"]:hover {
    background: #545b62;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  th {
    background: #f5f5f5;
    font-weight: bold;
  }
</style>
