<script>
  import UserList from './Views/UserList.svelte';
  import ProductList from './Views/ProductList.svelte';
  import OrderForm from './Views/OrderForm.svelte';

  let currentView = 'users';

  function setView(view) {
    currentView = view;
  }
</script>

<div class="app">
  <nav class="navbar">
    <h1>Palm Application</h1>
    <div class="nav-links">
      <button 
        class:active={currentView === 'users'} 
        on:click={() => setView('users')}
      >
        Users
      </button>
      <button 
        class:active={currentView === 'products'} 
        on:click={() => setView('products')}
      >
        Products
      </button>
      <button 
        class:active={currentView === 'orders'} 
        on:click={() => setView('orders')}
      >
        Orders
      </button>
    </div>
  </nav>

  <main class="content">
    {#if currentView === 'users'}
      <UserList />
    {:else if currentView === 'products'}
      <ProductList />
    {:else if currentView === 'orders'}
      <OrderForm />
    {/if}
  </main>
</div>

<style>
  :global(body) {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background: #f5f5f5;
  }

  .app {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  .navbar {
    background: #fff;
    padding: 1rem 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .navbar h1 {
    margin: 0;
    color: #333;
    font-size: 1.5rem;
  }

  .nav-links {
    display: flex;
    gap: 1rem;
  }

  .nav-links button {
    padding: 0.5rem 1rem;
    background: transparent;
    color: #666;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
  }

  .nav-links button:hover {
    background: #f0f0f0;
  }

  .nav-links button.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
  }

  .content {
    flex: 1;
    padding: 2rem;
  }
</style>
